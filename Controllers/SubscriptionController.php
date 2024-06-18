<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\packagesStockageDocument;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Jobs\sendFactureMailPdf;
use App\Libraries\StripeInterface;
use App\Http\Models\Payment\Payment;
use App\Http\Models\Package;
use App\Http\Models\UserPackage\UserPackage;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\BoostedAds;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function subscriptionPlan(Request $request, $typeUser = null)
    {
        //$request->type = 1;
        if ($request->type) {
            $type = $request->type;
            switch ($type) {
                case 'filtre':
                    $link = getSearchUrl();
                    break;

                default:
                    $ad_id = $request->session()->get("lastViewAd");
                    $link = adUrl($ad_id);
                    break;
            }
            $action = $type;
        } else {
            $action = "header";
            $link = url()->previous();
        }
        $user_id = Auth::id();
        DB::table('link_vente_source')->insert([
            "user_id" => $user_id,
            "link" => $link,
            "action" => $action,
            "device" => userDevice()
        ]);
        $expiry_date = UserPackage::where('user_id', $user_id)
            ->select('end_date')
            ->orderBy('id', 'DESC')
            ->first();
        if (!empty($expiry_date)) {
            $date1 = strtotime(date('Y-m-d')); //current
            $date2 = strtotime($expiry_date->end_date);
            if ($date1 < $date2) {
                $request->session()->flash('status', __('backend_messages.already_subscribed'));
                return redirect()->route('user.dashboard');
            }
        }
        $packages = Package::where('is_active', '1')->orderByRaw('id ASC')->get();

        $promo = DB::table("user_code_promo")->join("code_promo", "code_promo.id", "user_code_promo.promo_id")->where("user_id", Auth::id())->first();
        if (!is_null($promo) && $promo->type_promo == 2) {
            $date1 = strtotime(date('Y-m-d')); //current
            $date2 = strtotime($promo->end_date_validity);
            if ($date1 <= $date2) {
                $nbPercent = $promo->value;
                foreach ($packages as $key => $package) {
                    $reduction = ($package->amount * $nbPercent) / 100;
                    $packages[$key]->amount = $package->amount - $reduction;
                }
            }
        }
        $typeSubscription = "";
        if (isset($request->type)) {
            //type = 1 -> message
            //type = 2 -> contact facebook
            //type = 3 -> demande de visite
            $typeSubscription = $request->type;
            //  $alert = __("subscription." . $request->type . "_alert");
        }

        countViewSubscription();
        $current = get_current_symbol();
        return view('subscription_plan', compact('packages', "typeSubscription", "typeUser", "current"));
    }

    public function confirmBoostStripe($adId,$ups,$amount)
    {
        //ad_id,payementId
        $request= new Request ;

        $request->merge(['adId' => $adId,'ups'=>$ups,'amount'=>$amount]);
        $intent = DB::table('payment_intent')->where('user_id', Auth::id())->orderBy("date", "desc")->first();

        if (isStripeCheckout()&& $intent) {


            $objStripe = new StripeInterface();
            $charges = $objStripe->getCharges($intent->intent_id);
            $responseArray = $charges->data[0];

            $adDetail = Ads::with('user')->where([['id', $adId], ['user_id', Auth::id()]])->first();

            $paymentId = $this->saveBoostPackage($request, $adId, $responseArray, $adDetail);

            return redirect()->route('boost-paiement-confirmation', [$paymentId]);



        }
        else
        {
            return redirect()->route("user.dashboard");
        }
    }

    public function confirmationStockageSubscription($id, Request $request)
    {
        $intent = DB::table('payment_intent')->where('user_id', Auth::id())->orderBy("date", "desc")->first();
        if (isStripeCheckout() && $intent) {
            $objStripe = new StripeInterface();
            $charges = $objStripe->getCharges($intent->intent_id);
            $responseArray = $charges->data[0];
            $payement = DB::table('payments')->where('charge_id', $responseArray['id'])->exists();
            if (!$payement) {
                $this->savePayment($responseArray);
                $Package = packagesStockageDocument::find($id);
                DB::table('users')
                    ->where('id', Auth::id())
                    ->increment('max_storage', intval($Package->unite));
                return view('proprietaire.subscriptionConfirmationDocumentStorage', ['Package' => $Package]);
            } else {
                return redirect()->route("user.dashboard");
            }
        } else {
            return redirect()->route("user.dashboard");
        }
    }

    public function confirm(Request $request)
    {
        if (isStripeCheckout() && empty($request->session()->get('packageId'))) {
            $intent = DB::table('payment_intent')->where('user_id', Auth::id())->orderBy("date", "desc")->first();

            if ($intent) {
                $objStripe = new StripeInterface();
                $charges = $objStripe->getCharges($intent->intent_id);
                $responseArray = $charges->data[0];
                $paymentId = $this->saveUserPackage($request, $intent->package_id, $responseArray);
            } else {
                return redirect()->route("user.dashboard");
            }
        }
        if (!empty($request->session()->get('packageId')))
            $request->session()->forget('packageId');
        $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', Auth::id())->orderBy('id', 'DESC')->first();
        // Mail to user
        if (!is_null($payment)) {
            $currency = get_current_symbol();
            $packageDetail = [];
            $packageDetail['packageTitle'] = $payment->package->title;
            $packageDetail['packageDuration'] = $payment->package->duration;
            $packageDetail['packageAmount'] = $payment->payment->amount;
            $packageDetail['packageStartDate'] = $payment->start_date;
            $packageDetail['packageEndDate'] = $payment->end_date;
            $packageDetail['unite'] = $payment->package->unite;
            return view('paiement-confirmation', compact("packageDetail", "currency"));
        } else {
            return redirect()->route("user.dashboard");
        }
    }

    public function confirmBoost($payementId, Request $request)
    {
        $current_symbol = get_current_symbol();
        $payment = DB::table("payments")->where("id", $payementId)->first();
        $boostedAds = DB::table('boosted_ads as ba')->join("ads as a", "a.id", "ba.ad_id")->join("upselling as u", "u.id", "ba.boost_id")->join("upselling_tarif as ut", "ut.id", "ba.tarif_id")->where("ba.payment_id", $payment->id)->get();
        // Mail to user
        if (!is_null($payment)) {
            return view('boost-paiement-confirmation', compact("payment", "boostedAds", "current_symbol"));
        } else {
            return redirect()->route("user.dashboard");
        }
    }

    public function showPaymentForm(Request $request, $packageId = null)
    {
        if (isset($request->admin)) {
            $adId = $request->adId;
            $ups = $request->ups;
            $upsArray = json_decode(json_decode($ups, true));
            $this->simulateBoost($upsArray, $adId);
            $request->session()->flash('status', 'Ad boosted successfully');
            return redirect("/admin/adList");
        } else {
            $years = $this->buildYears();
            $months = $this->buildMonths();

            $currency = get_current_symbol();

            if (!is_null($packageId) && !empty($request->EncryptedKey)) {
                countViewAchat();
                if ($packageId == 0) {
                    $adId = $request->adId;
                    $amount = $request->session()->get('paymentAmount');
                    $ups = $request->ups;
                    $objStripe = new StripeInterface();
                    /*$intent = $objStripe->createIntent($amount + amountTVA($amount));*/
                    if(!$amount){
                        return redirect('/subscription_plan');
                    }
                    $intent = $objStripe->createIntent($amount);

                    if (is_null($adId))
                        return redirect("/subscription_plan");

                    return view('payment_form', compact('adId', 'amount', "ups", "months", "years", "intent", "currency"));
                } else {
                    //prendre les packages active
                    $package = Package::where('is_active', '1')->where("id", $packageId)->orderByRaw('id ASC')->first();
                    $package->amount = getReduction(Conversion_devise($package->amount));
                    $amount = $package->amount;

                    //creation instance stripe
                    $objStripe = new StripeInterface();
                    //definir le montant
                    $intent = $objStripe->createIntent($amount);

                    return view('payment_form', compact('package', "months", "years", "intent", "currency"));
                }
            } else {
                return redirect()->route("user.dashboard");
            }
        }
    }

    public function buildYears()
    {
        $i = 0;
        $years = [];
        for ($i = 0; $i <= 20; $i++) {
            $years[] = array("value" => intval(date("y")) + $i, "label" =>  intval(date("Y")) + $i);
        }
        return $years;
    }

    public function buildMonths()
    {
        $i = 1;
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = array("value" => ($i < 10) ? "0" . $i : $i, "label" =>  __("payment.month" . $i));
        }
        return $months;
    }

    public function verifierPayment(Request $request)
    {
        return $this->processPayment($request);
        $verif_package = DB::table('user_packages')->where('user_id', '=', auth::id())->first();
        $date_now = date('Y-m-d');
        if (!empty($verif_package)) {
            $end_date = $verif_package->end_date;
            $packages = DB::table('packages')->where('id', '=', $verif_package->package_id)->first();

            if ($date_now > $end_date || $verif_package == null) {
                //date packages expiré
                return $this->processPayment($request);
            } else {
                //demander si l'user veux payé une deuxième fois
                Session::flash('verifierPayment', $packages);
                return view('verification_payement', compact('packages'));
            }
        } else {
            //payement première fois
            return $this->processPayment($request);
        }
    }

    public function processPayment($request)
    {
        if ($request->ajax()) {
            $response = array();
            $user_id = Auth::id();
            $packageId = base64_decode($request->packageId);
            $request->session()->put('packageId', $packageId);
            $packageDetail = Package::where('id', $packageId)->select('duration', 'amount')->first();
            $packageDetail->amount = getReduction($packageDetail->amount);
            if (!empty($packageDetail)) {
                $packageEndData = getDateFinPackage($packageId);
            }

            if (!empty($packageDetail->amount)) {
                $amount = $packageDetail->amount * 100;
                $intentId = $request->intent_id;
                $objStripe = new StripeInterface();
                $charges = $objStripe->getCharges($intentId);
                if (!empty($charges->data)) {
                    $responseArray = $charges->data[0];
                    if (!$responseArray['status']) {
                        $response['message'] = $responseArray['message'];
                        $response['status'] = 'error';
                        //payment stripe error
                        $this->savePaymentLog(0, $responseArray['message']);
                        return response()->json($response);
                    }
                } else {
                    $response['message'] = 'error';
                    $response['status'] = 'error';
                    //payment stripe error
                    $this->savePaymentLog(0, $responseArray['message']);
                    return response()->json($response);
                }
                //payment stripe ok
                $this->savePaymentLog(1);
            }
            if ($user_id) {
                $paymentId = $this->saveUserPackage($request, $packageId, $responseArray);
                if ($paymentId) {
                    $response['message'] = __("subscription.paiement_success");
                    $response['status'] = 'success';
                    $response['redirect_url'] = route('paiement-confirmation');
                    return response()->json($response);
                }
            }
        }
    }

    public function saveUserPackage(Request $request, $packageId, $responseArray)
    {
        $user_id = Auth::id();
        $packageEndData = getDateFinPackage($packageId);
        $link = DB::table("link_vente_source")->where("user_id", $user_id)->orderBy('id', "DESC")->first();
        $id_link = 0;
        if (!is_null($link)) {
            $id_link = $link->id;
        }
        $paymentId = $this->savePayment($responseArray, $id_link);
        if ($paymentId) {
            Ads::where('user_id', $user_id)->update(['boosted' => '2']);
            $userPackage = new UserPackage;
            $userPackage->user_id = $user_id;
            $userPackage->package_id = $packageId;
            $userPackage->payment_id = $paymentId;
            $userPackage->start_date = date("Y-m-d");
            $userPackage->end_date = $packageEndData;
            $userPackage->last_ad_id =  $request->session()->get("lastViewAd");
            $userPackage->save();
            $currency_symbol = get_current_symbol();
            dispatch(new sendFactureMailPdf(Auth::user(), App::getLocale(), $currency_symbol));
            // $this->sendPaymentSuccessMail(Auth::user());
            $request->session()->flash('status', __('backend_messages.subscribed_succesfully'));
        }
        return $paymentId;
    }

    public function handlePaypal(Request $request)
    {
        //verifier tva pour paypal
        if (Auth::check()) {
            $user_id = Auth::id();
            if (isset($request->paymentId)) {
                if (isset($request->packageId)) {
                    $package = DB::table("packages")->where("id", $request->packageId)->first();
                    $responseArray = ["id" => $request->paymentId, "amount" => Conversion_devise($package->amount)*100 ];
                    $this->saveUserPackage($request, $request->packageId, $responseArray);
                    $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', Auth::id())->orderBy('id', 'DESC')->first();
                    // Mail to user
                    if (!is_null($payment)) {
                        $packageDetail = [];
                        $packageDetail['packageTitle'] = $payment->package->title;
                        $packageDetail['packageDuration'] = $payment->package->duration;
                        $packageDetail['packageAmount'] = ($payment->payment->amount);
                        $packageDetail['packageStartDate'] = $payment->start_date;
                        $packageDetail['packageEndDate'] = $payment->end_date;
                        $packageDetail['unite'] = $payment->package->unite;
                        return view('paiement-confirmation', compact("packageDetail"));
                    } else {
                        return redirect()->route("user.dashboard");
                    }
                }

                if (isset($request->adId)) {
                    $responseArray = ["id" => $request->paymentId, "amount" => $request->amount * 100];
                    $adDetail = Ads::with('user')->where([['id', $request->adId], ['user_id', $user_id]])->first();
                    $paymentId = $this->saveBoostPackage($request, $request->adId, $responseArray, $adDetail);
                    return redirect()->route('boost-paiement-confirmation', [$paymentId]);
                }
            }
        } else {
            return redirect()->route("home");
        }
    }

    public function saveBoostPackage(Request $request, $adId, $responseArray, $adDetail)
    {
        $paymentId = $this->savePayment($responseArray);
        if($request->hasSession())
            $amount = $request->session()->get('paymentAmount');
        else
            $amount = $request->amount;

        $user_id = Auth::id();
        if ($paymentId) {
            $ups = base64_decode($request->ups);
            $upsArray = json_decode(json_decode($ups, true));
            $this->saveBoostedAds($upsArray, $adId, $paymentId);
            $boostUpsInfos = [];
            foreach ($upsArray as $key => $tarif) {
                $tarifInfo = DB::table("upselling_tarif")->where("id", $tarif)->first();
                $upsInfo = DB::table("upselling")->where("id", $key)->first();
                $boostUpsInfos[] = array("upsInfos" => $upsInfo, "tarifInfos" => $tarifInfo);
            }
            $this->sendAdBoostPaymentSuccessMail($adDetail, $amount, $boostUpsInfos);
            if($request->hasSession())
                $request->session()->flash('status', __('backend_messages.success_boost', ['title' => $adDetail->title]));
        }
        return $paymentId;
    }

    private function simulateBoost($ups, $adId)
    {
        $this->saveBoostedAds($ups, $adId, null);
    }

    private function saveBoostedAds($ups, $adId, $paymentId)
    {
        $user_id = Auth::id();
        Ads::where('user_id', $user_id)->where('id', $adId)->update(['boosted' => '1']);
        foreach ($ups as $key => $tarif) {
            $tarifInfo = DB::table("upselling_tarif")->where("id", $tarif)->first();
            $upsInfo = DB::table("upselling")->where("id", $key)->first();
            $boostedAds = new BoostedAds;
            $boostedAds->ad_id = $adId;
            $boostedAds->boost_id = $key;
            $boostedAds->tarif_id = $tarif;
            $boostedAds->payment_id = $paymentId;
            $nbdec = "+" . $tarifInfo->duration;
            $expiryDate = addtoDate(null, $nbdec, $tarifInfo->unit);
            $boostedAds->expiry_date = $expiryDate;
            $boostedAds->save();

            DB::table("ads")->where("id", $adId)->update(["is_" . $upsInfo->label => "1", "date_" . $upsInfo->label => $expiryDate]);
        }
    }

    private function savePayment($responseArray, $id_link = 0)
    {
        $payment = new Payment;
        $payment->charge_id = (!empty($responseArray['id'])) ? $responseArray['id'] : '';
        $payment->object = (!empty($responseArray['object'])) ? $responseArray['object'] : '';
        $payment->amount = (!empty($responseArray['amount'])) ? $responseArray['amount'] : '';
        $payment->amount_refunded = 0.00;
        $payment->application_fee = (!empty($responseArray['application_fee'])) ? $responseArray['application_fee'] : null;
        $payment->balance_transaction = (!empty($responseArray['balance_transaction'])) ?: '';
        $payment->captured = (!empty($responseArray['captured'])) ? $responseArray['captured'] : '';
        $payment->created = (!empty($responseArray['created'])) ? $responseArray['created'] : '';
        $payment->currency = (!empty($responseArray['currency'])) ? $responseArray['currency'] : '';
        $payment->customer = (!empty($responseArray['customer'])) ? $responseArray['customer'] : '';
        $payment->description = (!empty($responseArray['description'])) ? $responseArray['description'] : '';
        $payment->paid = (!empty($responseArray['paid'])) ? $responseArray['paid'] : '';
        $payment->receipt_email = (!empty($responseArray['receipt_email'])) ?: '';
        $payment->receipt_number = (!empty($responseArray['receipt_number'])) ? $responseArray['receipt_number'] : '';
        $payment->failure_code = (!empty($responseArray['failure_code'])) ? $responseArray['failure_code'] : '';
        $payment->failure_message = (!empty($responseArray['failure_message'])) ? $responseArray['failure_message'] : '';
        $payment->invoice = (!empty($responseArray['invoice'])) ? $responseArray['invoice'] : '';
        $payment->status = (!empty($responseArray['status'])) ? $responseArray['status'] : '';
        $payment->id_link = $id_link;
        $payment->save();
        return $payment->id;
    }



    public function sendPaymentSuccessMail($user)
    {
        $subject = __('mail.payment_success');

        $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        // Mail to user
        $packageDetail = [];
        $id_fac = strtotime(now()) - 1644327000;
        $UserName = trim($user->first_name) . " " . trim($user->last_name);
        $packageDetail['userName'] = $UserName;
        $packageDetail['subject'] = $subject;
        $packageDetail['packageTitle'] = $payment->package->title;
        $packageDetail['packageDuration'] = $payment->package->duration;
        $packageDetail['packageAmount'] = $payment->payment->amount;
        $packageDetail['packageStartDate'] = $payment->start_date;
        $packageDetail['packageEndDate'] = $payment->end_date;
        $packageDetail['unite'] = $payment->package->unite;
        $packageDetail['date'] = date('d-M-Y');
        $packageDetail['adress'] = $user->address_register;
        $packageDetail['id_fac'] = $id_fac;
        //$fichier = PDF::loadView('admin.facture.facture', compact('packageDetail'));
        try {
            sendMail($user->email, 'emails.payment', [
                "packageDetail" => $packageDetail,
                "subject" => $this->packageDetail['subject'],
                "lang" => getLangUser($user->id)
            ]);
        } catch (Exception $ex) {
        }
        return true;
    }

    public function boostAdPayment(Request $request)
    {
        if ($request->ajax()) {
            $user_id = Auth::id();
            $response = [];
            if (!empty($user_id)) {
                $adId = base64_decode($request->adId);
                $adDetail = Ads::with('user')->where([['id', $adId], ['user_id', $user_id]])->first();
                if (!empty($adDetail)) {
                    $intentId = $request->intent_id;
                    $objStripe = new StripeInterface();
                    $charges = $objStripe->getCharges($intentId);
                    $responseArray = $charges->data[0];
                    $amount = $request->session()->get('paymentAmount') * 100;
                    if (!$responseArray['status']) {
                        $response['message'] = $responseArray['message'];
                        $response['status'] = 'error';
                        return response()->json($response);
                    }
                    if (!empty($responseArray)) {
                        $paymentId = $this->saveBoostPackage($request, $adId, $responseArray, $adDetail);
                        if ($paymentId) {
                            $response['message'] = __('backend_messages.payment_success');
                            $response['status'] = 'success';
                            $response['redirect_url'] = route('boost-paiement-confirmation', [$paymentId]);
                            return response()->json($response);
                        }
                    }
                } else {
                    $response['message'] = 'Something went wrong!';
                    $response['status'] = 'error';
                    return response()->json($response);
                }
            } else {
                $response['message'] = 'Please login to continue';
                $response['status'] = 'error';
                return response()->json($response);
            }
        }
    }

    private function sendAdBoostPaymentSuccessMail($adDetail, $amount, $boostUpsInfos)
    {
        if (!empty(Auth::user()->email)) {
            try {
                sendMail(Auth::user()->email,'emails.boost_ad_payment',[
                    'adPayment'=> $adDetail,
                    'amount' => $amount,
                    'boostUpsInfos' =>$boostUpsInfos,
                    'lang_title'    => \App::getLocale() . "_title",
                    'lang' => getLangUser(Auth::id()),
                    'subject' => config('app.name', 'TicTacHouse') . ": " . __('boost.boost_ad_payement_detail')
                ]);
            } catch (Exception $ex) {
            }
            return true;
        }
    }

    public function codePromo(Request $request)
    {
        $code = DB::table('code_promo')->where("code", $request->code)->where("end_date_validity", ">", date("Y-m-d"))->first();
        if (!is_null($code)) {
            $user_promo = DB::table("user_code_promo")->where("user_id", Auth::id())->where("end_date", ">=", date("Y-m-d"))->first();
            if (!is_null($user_promo)  && $user_promo->promo_id == $code->id) {
                $response["error"] = "yes";
                $response['message'] = __("backend_messages.code_already_used");
            } else {
                DB::table("user_code_promo")->where("user_id", Auth::id())->delete();
                if (isDateValide(date("Y-m-d"), $code->end_date_validity)) {
                    if ($code->type_promo == 1) {
                        $endDate = getDateByDecalage($code->value);

                        if (!isDateValide($endDate, $code->end_date_validity)) {
                            $endDate = $code->end_date_validity;
                        }
                        DB::table("user_packages")->insert(["user_id" => Auth::id(), "package_id" => 1, "active" => 1, "start_date" => date("Y-m-d"), "end_date" => $endDate, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d")]);

                        DB::table("user_code_promo")->insert(["user_id" => Auth::id(), "promo_id" => $code->id, "end_date" => $endDate]);
                        $response["error"] = "no";
                        Session::put("action_promo", $request->button_clicked);
                        $response["redirect_url"] = "";
                        $response['message'] = __("backend_messages.code_valide", ["date" => date("d-m-Y", strtotime($endDate))]);
                    } else {
                        DB::table("user_code_promo")->insert(["user_id" => Auth::id(), "promo_id" => $code->id]);
                        $response["error"] = "no";
                        $response["redirect_url"] = route("subscription_plan");
                        $response['message'] = __("backend_messages.code_valide_promotion", ['number' => $code->value]);
                    }
                } else {
                    $response["error"] = "yes";
                    $response['message'] = __("backend_messages.code_invalide");
                }
            }
        } else {
            $response["error"] = "yes";
            $response['message'] = __("backend_messages.code_invalide");
        }
        return response()->json($response);
    }

    public function saveAmount(Request $request)
    {
        $request->session()->put('paymentAmount', $request->amount);
        echo "true";
    }

    public function logPayment(Request $request)
    {
        //payment stripe ko
        $this->savePaymentLog(0, $request->messageError);
        echo "true";
    }

    public function savePaymentLog($status, $message = null)
    {
        $data = [
            "login" => Auth::user()->email,
            "message" => $message,
            "is_paiement_ok" => $status,
            "date" => adjust_gmt(date("Y-m-d H:i:s"))
        ];
        DB::table("payment_log")->insert($data);
    }

    public function createCheckoutSession(Request $request)
    {
        $packageId = $request->package_id;
        $packageDetail = Package::where('id', $packageId)->first();
        $packageDetail->amount = getReduction(Conversion_devise($packageDetail->amount));
        $amount = $packageDetail->amount * 100;
        // creation une nouvelle instance de stripe
        $objStripe = new StripeInterface();
        //creation session stripe
        $session = $objStripe->createSession($packageDetail->title, $amount, route("paiement-confirmation"), route("subscription_plan"));
        if (!empty($session->payment_intent));
        DB::table("payment_intent")->insert([
            "user_id" => Auth::id(),
            "intent_id" => $session->payment_intent,
            "package_id" => $packageId
        ]);
        return response()->json(['id' => $session->id]);
    }

    public function createCheckoutSessionStockage(Request $request)
    {
        $package = packagesStockageDocument::find($request->package_id);

        $amountPrice = $package->amount * 100;

        $objStripe = new StripeInterface();

        $session = $objStripe->createSession($package->title, $amountPrice, route("package.confirmation_subscription_documents", ['id' => $package->id]), route("documents.subscription_documents"));
        if (!empty($session->payment_intent));
        DB::table("payment_intent")->insert([
            "user_id" => Auth::id(),
            "intent_id" => $session->payment_intent,
            "package_id" => $package->id
        ]);

        return response()->json(['id' => $session->id]);

    }


    public function createCheckoutSessionBoost(Request $request)
    {

        $amountPrice = $request->session()->get('paymentAmount')*100;
        $name = "BOOST";

        $objStripe = new StripeInterface();

        $session = $objStripe->createSession($name, $amountPrice, route("confirmation-boost-stripe",[base64_decode($request->adId),$request->ups,$amountPrice/100]), route("subscription_plan"));

        if (!empty($session->payment_intent));
        DB::table("payment_intent")->insert([
            "user_id" => Auth::id(),
            "intent_id" => $session->payment_intent,
            "package_id" => 1
        ]);

        return response()->json(['id' => $session->id]);

    }


}
