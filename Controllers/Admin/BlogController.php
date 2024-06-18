<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Middleware\ResizeImageComp;
use App\Http\Models\Blog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class BlogController extends Controller {

    private $perpage;

    public function __construct() {
        $this->perpage = config('app.perpage');
    }

    public function blogListing(Request $request)
    {
        $orderBy = "updated_at DESC";
        $sort = "updated_at";
        if(isset($request->sort))
        {
            if(isset($request->type) && !empty($request->type))
            {
                $type = $request->type;
            }
            else
            {
                $type = "ASC";
            }
            $orderBy = $request->sort . " " . $type;
            $sort = $request->sort;
        }
        if(isset($request->foreign))
        {
            $foreign = $request->foreign;
            $all_blogs = Blog::with('user')->join($foreign, $foreign. ".id", "blogs." . $request->foreign_id)->orderByRaw($orderBy)->paginate($this->perpage);
        }
        else
        {
            $all_blogs = Blog::with('user')->orderByRaw($orderBy)->paginate($this->perpage);
        }


        return view('admin.blog.blog_listing', compact('all_blogs', 'sort'));

    }

    public function addNewBlog(Request $request)
    {
        if ($request->isMethod('post'))
        {

            $request_array = $request->all();
            $url_slug = str_slug($request->blog_title, '-');

            $request_array['url_slug'] = $url_slug;

            $validator = Validator::make($request_array,[
                'blog_title' => 'required|min:3|max:100',
                'blog_meta_title' => 'required|min:3|max:100',
                'blog_meta_description' => 'required|min:3|max:165',
                'blog_description' => 'required',
                'url_slug' => Rule::unique('blogs')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })
            ],
            [
                'url_slug.unique'=> 'The url slug for this title has already been taken. Please change the title!'
                ]
            );

            if ($validator->fails())
            {

                return redirect()->back()->withErrors($validator)->withInput($request->all());

            } else {
                if($request->file('featured_image'))
                {

                    $image = $request->file('featured_image');
                    $destinationPathBlogImage = base_path() . '/storage/uploads/blog_images/';
                    $image_name = rand(999, 99999) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPathBlogImage, $image_name);

                    $resizeObj = new ResizeImageComp();
                    $resizeObj->load(storage_path('uploads/blog_images/') . $image_name);
                    $resizeObj->resizeToWidth(263);
                    $resizeObj->save(storage_path('uploads/blog_images/thumb/thumb_') . $image_name);

                }

                $blog = new Blog;
                $blog->user_id = Session::get('ADMIN_USER')->id;
                $blog->blog_title = $request_array['blog_title'];
                $blog->blog_description = $request_array['blog_description'];
                $blog->meta_title = $request_array['blog_meta_title'];
                $blog->meta_description = $request_array['blog_meta_description'];
                if(!empty($image_name)){
                    $blog->featured_image = $image_name;
                }
                $blog->url_slug = $request_array['url_slug'];

                if ($blog->save())
                {
                    $url = "/" . date("Y") . "/" . date("m") . "/" . $request_array['url_slug'] . "/" . $blog->id;
                    $this->sendMailBlog($request_array['blog_title'], $url);

                    $request->session()->flash('status', "Blog has been added successfuly.");
                    return redirect()->back();

                }
                else
                {
                    $request->session()->flash('error', 'Some error occurred. Please try again!');
                    return redirect()->back();
                }
            }
        }

        return view('admin.blog.add_blog');

    }

    public function editBlog($id, Request $request) {

        $id = base64_decode($id);

        $blog = Blog::find($id);

        if(!empty($blog)) {

            if ($request->isMethod('post')) {
                $request_array = $request->all();

                $url_slug = str_slug($request->blog_title, '-');

                $request_array['url_slug'] = $url_slug;

                $validator = Validator::make($request_array,[
                    'blog_title' => 'required|min:3|max:100',
                    'blog_description' => 'required',
                    'blog_meta_title' => 'required|min:3|max:100',
                    'blog_meta_description' => 'required|min:3|max:165',
                    'url_slug' => Rule::unique('blogs')->ignore($blog->id)->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    })
                ],
                [
                    'url_slug.unique'=> 'The url slug for this title has already been taken. Please change the title!'
                    ]
                );

                if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput($request->all());

                } else {

                    if($request->file('featured_image')) {

                        //Delete Old images
                        if(!empty($blog->featured_image)){
                            if(File::exists(public_path('uploads/blog_images/' . $blog->featured_image))){
                                @unlink(public_path('uploads/blog_images/' . $blog->featured_image));
                            }
                            if(File::exists(public_path('uploads/blog_images/thumb/thumb_' . $blog->featured_image))){
                                @unlink(public_path('uploads/blog_images/thumb/thumb_' . $blog->featured_image));
                            }
                        }

                        $image = $request->file('featured_image');
                        $destinationPathBlogImage = base_path() . '/public/uploads/blog_images/';
                        $image_name = rand(999, 99999) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move($destinationPathBlogImage, $image_name);

                        $resizeObj = new ResizeImageComp();
                        $resizeObj->load(public_path('uploads/blog_images/') . $image_name);
                        $resizeObj->resizeToWidth(263);
                        $resizeObj->save(public_path('uploads/blog_images/thumb/thumb_') . $image_name);

                    }

                    $blog->user_id = Session::get('ADMIN_USER')->id;
                    $blog->blog_title = $request_array['blog_title'];
                    $blog->blog_description = $request_array['blog_description'];
                    $blog->meta_title = $request_array['blog_meta_title'];
                    $blog->meta_description = $request_array['blog_meta_description'];
                    if(!empty($image_name)){
                        $blog->featured_image = $image_name;
                    }
                    $blog->url_slug = $request_array['url_slug'];


                    if ($blog->save()) {
                        $url = "/" . date("Y") . "/" . date("m") . "/" . $request_array['url_slug'] . "/" . $blog->id;
                        $this->sendMailBlog($request_array['blog_title'], $url, "modif");
                        $request->session()->flash('status', "Blog has been updated successfuly.");
                        return redirect()->back();

                    } else {
                        $request->session()->flash('error', 'Some error occurred. Please try again!');
                        return redirect()->back();
                    }
                }
            }

            return view('admin.blog.edit_blog', compact('blog', 'id'));

        } else {
            $request->session()->flash('error', 'No blog found with this id. Please try again!');
            return redirect()->route('admin.bloglisting');
        }

    }

    private function sendMailBlog($titre, $url, $action = "add")
    {

        $subject = "Un article publié";

        try {

            sendMailAdmin("emails.admin.newblog",["subject"=>$subject,"titre"=>$titre,"url"=>$url,"action"=>$action,"ip"=>get_ip()]);

        } catch (Exception $ex) {

        }
        return true;
    }

    public function activateDeactivateBlog($id, $status, Request $request) {

        $id = base64_decode($id);
        $status = base64_decode($status);

        if (!empty($status)) {
            $status = '1';
            $msg = 'Blog activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'Blog deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = Blog::where('id', $id)->update(['is_active' => $status]);

        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function deleteBlog($id, Request $request) {

        $id = base64_decode($id);

        if (!empty($id)) {

            $blog = Blog::find($id);

            if (!empty($blog)) {
                $blog->delete();
                $msg = 'Blog deleted successfuly.';
                $msgType = 'status';
            } else {
                $msg = 'Blog not found!';
                $msgType = 'error';
            }
        } else {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function markBlogAsFeatured(Request $request) {

        if (!empty($request->blog_id)) {

            $queryStatus = Blog::where('id', base64_decode($request->blog_id))->update(['is_featured' => $request->status]);
            if (!empty($queryStatus)) {
                $blogData = Blog::where('id', base64_decode($request->blog_id))->first();
                if (!empty($blogData)) {
                    if (!empty($request->status)) {
                        $response['error'] = 'no';
                        $response['message'] = '"' . $blogData->blog_title . '" blog marked as featured successfuly.';
                    } else {
                        $response['error'] = 'no';
                        $response['message'] = '"' . $blogData->blog_title . '" blog unmarked as featured successfuly.';
                    }
                } else {
                    $response['error'] = 'yes';
                    $response['message'] = 'Not able to save your info, please try again.';
                }
            } else {
                $response['error'] = 'yes';
                $response['message'] = 'Not able to save your info, please try again.';
            }
            return response()->json($response);
        }
    }

}
