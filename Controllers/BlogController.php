<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\ResizeImageComp;
use App\Http\Models\Blog;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller {
    
    private $perpage;
    
    public function __construct() {
        $this->perpage = config('app.perpage');
    }
    
    public function blogListing(Request $request) {
        $search_query = $request->query('search_title');
        $sort_by = $request->query('sort_by');
        
        if(!empty($sort_by)){
            if($sort_by == 1){
                $orderBy = 'updated_at DESC';
            } else if($sort_by == 2){
                $orderBy = 'is_featured DESC, updated_at DESC';
            } else if($sort_by == 3){
                $orderBy = 'view_count DESC, updated_at DESC';
            } else {
                $orderBy = 'updated_at DESC';
            }
        } else {
            $orderBy = 'updated_at DESC';
        }
        
        if ($search_query) {
            $all_blogs = Blog::where('is_active', '1')->where('blog_title', 'like', '%' . $search_query . '%')->orderByRaw($orderBy)->paginate($this->perpage);
        } else {
            $all_blogs = Blog::where('is_active', '1')->orderByRaw($orderBy)->paginate($this->perpage);
        }
        
        return view('blog.blog_listing', compact('all_blogs', 'search_query', 'sort_by'));
        
    }
    
    public function blogDetail($year, $month, $url_slug, $id, Request $request) {
        
        $blogDetail = Blog::where('url_slug', $url_slug)->where('id', $id)->where('is_active', '1')->first();
        
        if(!empty($blogDetail)){

            if(!empty($blogDetail->meta_description)) {
                $page_description = $blogDetail->meta_description;
            }
            else $page_description =  blogDescription($blogDetail->blog_description);

            if(!empty($blogDetail->meta_title)) {
                $page_title = $blogDetail->meta_title;
            } else {
                $page_title = $blogDetail->blog_title;
            }
            $more_blogs = Blog::where('is_active', '1')->where('id', '!=', $id)->inRandomOrder()->take(10)->get();

            
            $increaseViewCount = Blog::where('id', $id)->update(['view_count' => ($blogDetail->view_count + 1)]);
            
            return view('blog.blog_detail', compact('blogDetail', 'more_blogs', 'page_description', "page_title"));
        } else {
            $request->session()->flash('status', __('backend_messages.no_blog_found'));
            return redirect()->route('home');
        }
        
    }

}
