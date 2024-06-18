@extends('layouts.app',[
    'page_title' => $page->title
])
<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <?php echo html_entity_decode($page->description);?>
    </div>
</section>
<style>
    .title-paragraphe
    {
        /*margin-top : 40px;
        margin-bottom : 40px;*/
        font-size : 1.4em;
        color : rgb(40,146,245);
    }
    .puce::before
    {
        content:url('/img/puce-image.png');
        margin-right: 10px;
    }

    .puce
    {
        margin-top : 10px;
    }
    .recru
    {
        text-decoration: underline;
    }
    .div-principale-description
    {
        margin-bottom: 20px;
    }
    .span-team
    {
        font-weight: bold;
        color : rgb(40,146,245);
    }
    .content-para
    {
        padding: 20px;
    }

    .row-next-content
    {
        margin-top: 20px;
    }
    .div-content-team
    {
        margin-top: 10px;
    }
    .ligne
    {
        margin-top : 30px;
        background: linear-gradient(to top right, rgba(200,200,200,0) 0%, rgba(200,200,200,0) calc(50% - 3px), rgba(200,200,200,1) 50%, rgba(200,200,200,1) calc(50% + 1px), rgba(200,200,200,0) 0%);
        height: 50px;
    }
    .title-paragraphe
    {
        /*margin-top : 40px;
        margin-bottom : 40px;*/
        font-size : 1.4em;
        color : rgb(40,146,245);
    }

    .title-principale
    {
        color : rgb(40,146,245);
         font-size : 1.6em;
         margin-bottom : 20px;
    }


    @media screen and (max-width : 719px)
    {

        .title-2
        {
            margin-bottom : 100px;
        }
    }
    @media screen and (min-width : 720px)
    {
         .title-paragraphe
        {
            width : 46.5%;
        }
        .title-paragraphe-1
        {
            margin-left : 53.2%;
        }
    }

</style>
@endsection

