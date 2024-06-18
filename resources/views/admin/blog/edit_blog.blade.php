@extends('layouts.adminappinner')
<script>
    var appSettings = {};
    @if(!empty($blog) && !empty($blog->featured_image) && file_exists(storage_path('uploads/blog_images/' . $blog->featured_image)))
        filesize = {{filesize(storage_path('uploads/blog_images/' . $blog->featured_image))}}
        appSettings['featured_image'] = ["{{$blog->featured_image}}", filesize];
    @endif
</script>
@include('admin.blog.add_blog_form')