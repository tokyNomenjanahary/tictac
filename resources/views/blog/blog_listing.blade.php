@extends('layouts.app')

@section('content')
<section class="inner-page-wrap blog-page-wrap">
    <div class="banner-inner bi-blog">
        <h2>{{ __('Blog') }}</h2>
    </div>
    <!-- More info -->
    <!-- http://www.nextbootstrap.com/2016/01/masonry-layout-for-bootstrap-3.html -->
    <!-- https://gist.github.com/premregmi/b8162eebe67e467b147c -->
    <div class="container blog-wrap">
        <div class="row">
            <form id="searchForm" method="GET">
                <div class="col-sm-6">
                    <div class="visit-sortby pull-left">
                        <label>{{ __('blog.sort_by') }}:</label>
                        <div class="custom-selectbx">
                            <div class="btn-group bootstrap-select dropdown">
                                <select class="selectpicker" tabindex="-98" name="sort_by" id="sortBy" onchange="this.form.submit()">
                                    <option @if($sort_by && $sort_by == 1){{'selected'}}@endif value='1'>{{ __('blog.recent') }}</option>
                                    <option @if($sort_by && $sort_by == 2){{'selected'}}@endif value='2'>{{ __('blog.featured') }}</option>
                                    <option @if($sort_by && $sort_by == 3){{'selected'}}@endif value='3'>{{ __('blog.most_viewed') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="ad-search-bx pull-right">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="{{ __('blog.search_placeholder') }}" name='search_title' @if($search_query) value="{{$search_query}}" @endif />
                            <div class="ad-srch-btn">
                                <input type="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><br>
        <div class="row">
            <div class="col-sm-12">
                @if(!empty($all_blogs) && count($all_blogs) > 0)
                <div class="masonry masonry-columns-4">
                    @foreach($all_blogs as $blog)
                    <a href="{{ route('blogdetail', [date('Y', strtotime($blog->created_at)), date('m', strtotime($blog->created_at)), $blog->url_slug, $blog->id]) }}" title="{{ __('blog.view_more') }}">
                        <div class="masonry-item">
                            <div class="media">
                                @if(!empty($blog->featured_image))
                                <img src="{{URL::asset('uploads/blog_images/thumb/thumb_' . $blog->featured_image)}}" class="img-responsive center-block" alt="{{ $blog->blog_title }}">
                                @endif
                            </div>
                            <h2 class="post-title">{{ $blog->blog_title }}</h2>
                            <p class="post-desc">{{str_limit(strip_tags($blog->blog_description), 150, '...')}}</p>
                            <span class="read-more" title="{{ __('blog.view_more') }}">{{ __('blog.more') }}...</span>
                            @if(!empty($blog->is_featured) && $blog->is_featured == '1')
                            <span class="tag-featured">{{ __('blog.featured') }}</span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="not_found_blog">
                    @if($search_query)
                    {{__('blog.not_found_query')}}
                    @else
                    {{__('blog.not_found')}}
                    @endif
                </div>
                @endif

                @if($all_blogs) @if($search_query && $sort_by){{ $all_blogs->appends(['sort_by' => $sort_by, 'search_title' => $search_query])->links('vendor.pagination.default') }}@elseif($search_query){{ $all_blogs->appends(['search_title' => $search_query])->links('vendor.pagination.default') }}@elseif($sort_by){{ $all_blogs->appends(['sort_by' => $sort_by])->links('vendor.pagination.default') }}@else{{ $all_blogs->links('vendor.pagination.default') }}@endif @endif

            </div>
        </div>
    </div>
    <!-- More info -->
    <!-- http://www.nextbootstrap.com/2016/01/masonry-layout-for-bootstrap-3.html -->
    <!-- https://gist.github.com/premregmi/b8162eebe67e467b147c -->


</section>
@endsection

