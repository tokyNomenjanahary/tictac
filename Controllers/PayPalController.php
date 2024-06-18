<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use URL;
use Session;
use Redirect;
use Illuminate\Support\Facades\DB;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;



class PayPalController extends Controller
{


    public function postPaymentWithpaypal(Request $request){


        if (!isset($request->adId)) {
            $packageId = base64_decode($request->packageId);
            $package = DB::table('packages')->where("id", $packageId)->first();
            $package->amount = getReduction($package->amount);
            $payAmount = Conversion_devise($package->amount);
            $name = $package->title;
            $description = __('payment.paypal_description') . " : " . $name;
            $return_params = ["packageId" => $packageId];
        } else {
            $packageId = 0;
            $adId = base64_decode($request->adId);
            $payAmount = $request->session()->get('paymentAmount');
            $name = "BOOST";
            $description = __('payment.paypal_description') . ":" . $name;
            $return_params = ["adId" => $adId, "ups" => $request->ups];
        }

        $return_params['amount'] = $payAmount;
        $return_params['currency'] = get_data_currency_code();
        $return_params['status'] = 'succeeded';

        $paymentId = 123;
        Session::put('return_params',$return_params);


        $order_request = new OrdersCreateRequest();
        $order_request->prefer('return=representation');
        $order_request->body = array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'return_url' => url('/payment/paypal/return'),//route('status') . "?" . http_build_query($return_params),
                    'cancel_url' => route('user.dashboard')
                ),
            'purchase_units' =>[
                [
                    "reference_id" => $paymentId,
                    "description" => $description,
                    "amount" => [
                        "value" => $payAmount,
                        "currency_code" => get_data_currency_code(),
                        'breakdown' => [
                            'item_total' =>
                                array(
                                    'currency_code' => get_data_currency_code(),
                                    'value' => $payAmount,
                                ),
                        ]
                    ],
                    'items' =>[
                        [
                            'name' => $name,
                            'description' => $description,
                            'quantity' => '1',
                            'unit_amount' =>
                            array(
                                'currency_code' => get_data_currency_code(),
                                'value' => $payAmount,
                            )
                        ],
                    ],

                ]
            ]
        );

        try {
            $paypal = \Config::get('paypal');

            $clientId = $paypal['client_id'];
            $clientSecret = $paypal['secret'];
            $mode = $paypal['settings']['mode'];

            if($mode == 'live')
                $client = new PayPalHttpClient(new ProductionEnvironment($clientId, $clientSecret));
            else
                $client = new PayPalHttpClient(new SandboxEnvironment($clientId, $clientSecret));

            $response = $client->execute($order_request);
 
            return response()->json($response,200);

        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());

            \Session::flash('error', __('payment.paypal_failed'));
            return Redirect::route('user.dashboard');
        }

    }


    public function getPaypalPaymentStatus(Request $request)
    {
        $order_request = new OrdersCaptureRequest($request->orderID);

        try {
            $paypal = \Config::get('paypal');

            $clientId = $paypal['client_id'];
            $clientSecret = $paypal['secret'];
            $mode = $paypal['settings']['mode'];

            if($mode == 'live')
                $client = new PayPalHttpClient(new ProductionEnvironment($clientId, $clientSecret));
            else
                $client = new PayPalHttpClient(new SandboxEnvironment($clientId, $clientSecret));

            $response = $client->execute($order_request);

            if ($response->result->status == 'COMPLETED') {
                $data_array=Session::get('return_params');
                Session::forget('return_params');
                $data_array['paymentId']=$request->orderID;
               \Log::info('Purchase Code:'. $response->result->purchase_units[0]->payments->captures[0]->id);
                return redirect()->route('handlePaypal', $data_array);
            }

            \Session::flash('error', __('payment.paypal_failed'));
            return Redirect::route('user.dashboard');

        } catch (HttpException $ex) {
            \Log::error($ex->getMessage());

            \Session::flash('error', __('payment.paypal_failed'));
            return Redirect::route('user.dashboard');
        }
    }


    
}

