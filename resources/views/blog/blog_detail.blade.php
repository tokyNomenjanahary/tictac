@extends('layouts.app')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="/css/blog.css" rel="stylesheet">
@endpush

@section('content')
<section class="inner-page-wrap blog-page-wrap">
    <div class="container blog-wrap">
        <div class="blog-wrapper">
            <div class="row">
                <div class="col-md-8 bloginfo">
                    @if(!empty($blogDetail->featured_image))
                    <img src="{{URL::asset('uploads/blog_images/' . $blogDetail->featured_image)}}" class="img-responsive blogimg" >
                    @endif
                    <h2 class="blogtitle">{{ $blogDetail->blog_title }}</h2>
                    <p class="dv-dated">{{ __('blog.posted_on') }}: <span> {{ date('d M, Y h:i A', strtotime($blogDetail->created_at)) }}</span></p>
                    {!! $blogDetail->blog_description !!}
                    <div id="disqus_thread"></div>
                    <script>

                    /**
                    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
                    /*
                    var disqus_config = function () {
                    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                    };
                    */
                    (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = 'https://bailti.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    })();
                    </script>
                    <noscript>{{ __('blog.enable_script') }} <a href="https://disqus.com/?ref_noscript">{{ __('blog.comment_by') }}.</a></noscript>
                </div>
                <div class="col-md-4">
                    <div class="blogexplore dv-list">
                        <h3>{{ __('blog.explore_more') }}</h3>
                        @if(!empty($more_blogs))
                        @foreach($more_blogs as $more_blog)
                        <div class="media">
                            <div class="media-left">
                                <a href="{{ route('blogdetail', [date('Y', strtotime($more_blog->created_at)), date('m', strtotime($more_blog->created_at)), $more_blog->url_slug, $more_blog->id]) }}" title="{{ __('blog.view_more') }}">
                                    @if(!empty($more_blog->featured_image))
                                    <img class="media-object" src="{{URL::asset('uploads/blog_images/thumb/thumb_' . $more_blog->featured_image)}}" alt="...">
                                    @else
                                    <img class="media-object" src="{{URL::asset('images/room_no_pic.png')}}" alt="...">
                                    @endif
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{ route('blogdetail', [date('Y', strtotime($more_blog->created_at)), date('m', strtotime($more_blog->created_at)), $more_blog->url_slug, $more_blog->id]) }}" title="{{ __('blog.view_more') }}">
                                    <h4 class="media-heading">{{ $more_blog->blog_title }}</h4>
                                </a>
                                <p>{{ strip_tags($more_blog->blog_description) }}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    h1,h2,h3
    {
        color : rgb(40,146,245) ;
        margin-top : 30px;
        margin-bottom: 30px;
    }

    .blogtitle
    {
        color : initial !important
    }
</style>
@endsection

