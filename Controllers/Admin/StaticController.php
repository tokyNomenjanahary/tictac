<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\StaticPage;
use Illuminate\Support\Facades\DB;


class StaticController extends Controller {

    private $perpage;

    public function __construct() {
        $this->perpage = config('app.perpage');

        # $this->middleware('adminauth', ['except' => ['getTraduction']]);
    }

    public function pageListing(Request $request) {

        $all_pages = StaticPage::orderByRaw('title ASC')->paginate($this->perpage);

        return view('admin.static.page_listing', compact('all_pages'));

    }

    private function escapeSTR($str){       
        if(strpos(str_replace("\'",""," $str"),"'")!=false)
            return addslashes($str);
        else
            return $str;
    }

    public function pageTextListing(Request $request) {
        $page_show = "acceuil";
        $search_name = "";

        if(!empty($request->page_show))
        {
            $page_show=$this->escapeSTR($request->page_show);

        }
        $all_pages=DB::table("page_text")
            ->whereRaw("page LIKE '%$page_show%'")
            ->OrderBy("ordre", "asc")
            ->orderBy("text_fr", "asc");
        if($request->page_show=='all')
        {
            $all_pages=DB::table("page_text")
            ->OrderBy("ordre", "asc")
            ->orderBy("text_fr", "asc");
        }
        if(!empty($request->search_name))
        {
            $search_name=$this->escapeSTR($request->search_name);
            $all_pages=DB::table("page_text")
                ->whereRaw("text_fr LIKE '%$search_name%' OR text_en LIKE '%$search_name%'")
                ->OrderBy("ordre", "asc")
                ->orderBy("text_fr", "asc");
        }
        $pages = DB::table("page_text")->distinct("page")->groupBy("page")->orderBy("page", "ASC")->get();

        $all_pages=$all_pages->get();
        $search_name = "";

        return view('admin.static.page_text_listing', compact('all_pages', "pages", "page_show", "search_name"));

    }

    public function editPageText(Request $request)
    {
        $id = $request->id;
        $order = NULL;
        $modifOrder = false;
        if(isset($request->order)) {
            $order = $request->order;
            $text = DB::table('page_text')->where('id', $id)->first();
            if($text->ordre != $order) {
                $modifOrder = true;
            }
        }

        if (getUserAdmin()->user_type_id!=2) 
        {
            DB::table("page_text")->where('id', $id)->update([
            "text_fr_proposition" => $request->text_fr,
            "text_en_proposition" => $request->text_en,
            "ordre"               => $order,
            "valide"              => 2,
                ]);

        # Envoi Mail
        $this->sendMailForValidate();
        }
        else
        {
            DB::table("page_text")->where('id', $id)->update([
                "text_fr" => $request->text_fr,
                "text_en" => $request->text_en,
                "ordre"               => $order,
                "valide"              => 1,
                    ]);
        }
       

        if($modifOrder) {
            $texts = DB::table('page_text')->where("page", $request->page)->where("ordre", ">=", $order)->where("id", "!=", $id)->orderBy("ordre")->get();
            $order = $order + 1;
            $orderMaxDefault = 1000;
            foreach ($texts as $key => $value) {
                if($value->ordre != $orderMaxDefault) {
                   DB::table('page_text')->where("id", $value->id)->update(
                        ["ordre" => $order]
                    );
                   $order++;
                }


            }
            return "order";
        }
        return "true";
    }

    public function addNewPage(Request $request) {

        if ($request->isMethod('post')) {

            $request_array = $request->all();
            $url_slug = str_slug($request->title, '-');

            $request_array['url_slug'] = $url_slug;

            $validator = Validator::make($request_array,[
                            'title' => 'required|min:3|max:100',
                            'description' => 'required',
                            'url_slug' => Rule::unique('static_pages')->where(function ($query) {
                                return $query->where('deleted_at', NULL);
                            }),
                            'meta_title' => 'required|min:3|max:100',
                            'meta_description' => 'max:500',
                            'meta_keywords' => 'max:500',
                            'sort_order' => 'nullable|integer|min:0|max:99'
                        ],
                        [
                            'url_slug.unique'=> 'The url slug for this title has already been taken. Please change the title!'
                        ]
            );

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput($request->all());

            } else {

                $static_page = new StaticPage;
                $static_page->title = $request_array['title'];
                $static_page->description = $request_array['description'];
                $static_page->url_slug = $request_array['url_slug'];
                $static_page->meta_title = $request_array['meta_title'];
                if(!empty($request_array['meta_description'])){
                    $static_page->meta_description = $request_array['meta_description'];
                }
                if(!empty($request_array['meta_keywords'])){
                    $static_page->meta_keywords = $request_array['meta_keywords'];
                }
                if(!empty($request_array['sort_order']) || $request_array['sort_order'] == 0){
                    $static_page->sort_order = $request_array['sort_order'];
                }

                if ($static_page->save()) {

                    $request->session()->flash('status', "Static page has been added successfuly.");
                    return redirect()->back();

                } else {
                    $request->session()->flash('error', 'Some error occurred. Please try again!');
                    return redirect()->back();
                }
            }
        }

        return view('admin.static.add_page');

    }

    public function editPage($id, Request $request) {

        $id = base64_decode($id);

        $page = StaticPage::find($id);

        if(!empty($page)) {

            if ($request->isMethod('post')) {

                $request_array = $request->all();
                $url_slug = str_slug($request->title, '-');

                $request_array['url_slug'] = $url_slug;

                $validator = Validator::make($request_array,[
                                'title' => 'required|min:3|max:100',
                                /*'description' => 'required',*/
                                'url_slug' => Rule::unique('static_pages')->ignore($page->id)->where(function ($query) {
                                    return $query->where('deleted_at', NULL);
                                }),
                                'meta_title' => 'required|min:3|max:100',
                                'meta_description' => 'max:500',
                                /*'meta_keywords' => 'max:500',
                                'sort_order' => 'nullable|integer|min:0|max:99'*/
                            ],
                            [
                                'url_slug.unique'=> 'The url slug for this title has already been taken. Please change the title!'
                            ]
                );

                if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput($request->all());

                } else {

                    $page->title = $request_array['title'];
                    /*$page->description = $request_array['description'];*/
                    /*$page->url_slug = $request_array['url_slug'];*/
                    $page->meta_title = $request_array['meta_title'];
                    if(!empty($request_array['meta_description'])){
                        $page->meta_description = $request_array['meta_description'];
                    } else {
                        $page->meta_description = NULL;
                    }
                    /*if(!empty($request_array['meta_keywords'])){
                        $page->meta_keywords = $request_array['meta_keywords'];
                    } else {
                        $page->meta_keywords = NULL;
                    }*/
                    /*if(!empty($request_array['sort_order']) || $request_array['sort_order'] == 0){
                        $page->sort_order = $request_array['sort_order'];
                    } else {
                        $page->sort_order = NULL;
                    }*/

                    if ($page->save()) {

                        $request->session()->flash('status', "Static page has been updated successfuly.");
                        return redirect()->back();

                    } else {
                        $request->session()->flash('error', 'Some error occurred. Please try again!');
                        return redirect()->back();
                    }

                }
            }
            var_dump("expression");
            exit;
            return view('admin.static.add_page', compact('page', 'id'));

        } else {
            $request->session()->flash('error', 'No page found with this id. Please try again!');
            return redirect()->route('admin.pagelisting');
        }

    }

    public function activateDeactivatePage($id, $status, Request $request) {

        $id = base64_decode($id);
        $status = base64_decode($status);

        if (!empty($status)) {
            $status = '1';
            $msg = 'Page activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'Page deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = StaticPage::where('id', $id)->update(['is_active' => $status]);

        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function deletePage($id, Request $request) {

        $id = base64_decode($id);

        if (!empty($id)) {

            $page = StaticPage::find($id);

            if (!empty($page)) {
                $page->delete();
                $msg = 'Page deleted successfuly.';
                $msgType = 'status';
            } else {
                $msg = 'Page not found!';
                $msgType = 'error';
            }
        } else {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function editStaticPage(Request $request)
    {
        $pages = DB::table("static_pages")->whereNotNull("description")->get();
        if(isset($request->page_show)) {
            $page = $request->page_show;
        } else {
            $page = $pages[0]->id;
        }

        $elem = DB::table("static_pages")->where("id", $page)->first();
        return view('admin.static.editStatic', compact("elem", "page", "pages"));
    }

    public function saveStaticPage(Request $request)
    {

        $id = $request->id;
        DB::table("static_pages")->where('id', $id)->update(
            ['description' => $request->description]
        );
        $request->session()->flash("status", "Updated successfuly");
        return redirect()->back();
    }

    public function MotCles(Request $request)
    {
        if(isset($request->id)) {
            DB::table("liste_mot_cles")->where("id", $request->id)->delete();
            return;
        }
        $mots = DB::table("liste_mot_cles")->orderByRaw('mot_cles ASC')->paginate($this->perpage);
        return view('admin.static.mot_cles', compact('mots'));
    }

    public function addMotCles(Request $request)
    {
        if ($request->isMethod('post')) {
            if(isset($request->id)) {
               DB::table("liste_mot_cles")->where("id", $request->id)->update(
                    ['mot_cles' => $request->mot_cles]
                );
               $request->session()->flash("status", "Mot clés supprimés");
               return redirect()->back();
            }
            $title = $request->title;
            if(!empty($title))
            {
                DB::table("liste_mot_cles")->insert(
                    ['mot_cles' => $title]
                );
                $request->session()->flash("status", "Inserer avec succès");
            } else {
                $request->session()->flash('error', 'Mot clés obligatoire');
            }

            return redirect()->back();
        } else {
            return view('admin.static.add_mots');
        }
    }

    public function saveNewTexte(Request $request)
    {
        $id = Session::get('ADMIN_USER')->id ? Session::get('ADMIN_USER')->id : Auth::id();
        DB::table('page_text')->insert(
            [
                'page' => $request->page,
                'index' => $request->index,
                'text_fr_proposition' => $request->text_fr,
                'text_en_proposition' => $request->text_en,
                'url' => $request->url,
                'ordre' => is_numeric($request->ordre) ? $request->ordre : 1000,
                'id_traducteur' => $id,
                'valide' => 2
            ]
        );
        $id = DB::getPdo()->lastInsertId();
        if(is_numeric($request->ordre)) {
            $order = $request->ordre;
            $texts = DB::table('page_text')->where("page", $request->page)->where("ordre", ">=", $request->ordre)
                ->where("id", "!=", $id)->orderBy("ordre")->get();
            $order = $order + 1;
            $orderMaxDefault = 1000;
            foreach ($texts as $key => $value) {
                if($value->ordre != $orderMaxDefault) {
                   DB::table('page_text')->where("id", $value->id)->update(
                        ["ordre" => $order]
                    );
                   $order++;
                }

            }
        }

        # Envoi Mail
        $this->sendMailForValidate();

        $request->session()->flash('status', "Texte enregistré avec succès");
        return redirect()->back();

    }

    public function getTraduction(Request $request)
    {
        $traduction = DB::table('page_text')->where('id', $request->id)->first();
        return response()->json($traduction);
    }

    public function sendMailForValidate()
    {
        # $users = DB::table('users')->where('user_type_id', 9)->get();

        $type = DB::table('type_user')->where('designation', 'Validateur de traduction')->first();
        $users = DB::table('users')->where('user_type_id', $type->id)->get();

        $subject = __("mail.Alerte_traduction");

       
        foreach ($users as $user)
        {
            sendMail($user->email,'emails.traduction.notification',[
                "subject" => $subject,
                "name" => Session::get('ADMIN_USER')->first_name,
                "lang" => getLangUser($user->id)
            ]);
        }

    }

    public function sendMailAnnulation()
    {
        # $users = DB::table('users')->where('user_type_id', 9)->get();

        $type = DB::table('type_user')->where('designation', 'Validateur de traduction')->first();
        $users = DB::table('users')->get();

        $subject = "Alerte d'une annulation d'une proposition de traduction";
        

        foreach ($users as $user)
        {
            sendMail($user->email,'emails.traduction.annulation',[
                "subject" => $subject,
                "name" => Session::get('ADMIN_USER')->first_name,
                "lang" => getLangUser($user->id)
            ]);
        }

    }

    public function cancelPending(Request $request)
    {
        $traduction = DB::table("page_text")->where('id', $request->id)->update([
            "valide"              => 3,
        ]);

        if ($traduction == 1) {
            $request->session()->flash("status", "Annulation avec succès");

            # Envoi Mail Annulation
            $this->sendMailAnnulation();
        } else {
            $request->session()->flash('error', 'Annulation echec');
        }

        return redirect()-> back();
    }

}
