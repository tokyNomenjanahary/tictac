@extends('layouts.app')
<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer">
                <div class="myad-cont-bx white-bg m-t-2 col-md-4" style="margin-top: 40px;width : 100%;">
                    <div class="myad-bx">
                        <h1 class="title-principale">{!! __('bailleur.sous_title') !!}</h1>
                        <div class="intro">
                        {!! __('bailleur.first_message') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <div><h2 class="title-paragraphe title-paragraphe-1">{!! __("bailleur.title_gerer_candidature") !!}</h2></div>
                <div class="img-box-1 box-image abs">
                    <img src="/img/image-candidature.png"/>

                </div>
                <div class="contenu-paragraphe par-right">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("bailleur.gerer_1") !!}
                            </li>
                            <li class="puce">
                               {!! __("bailleur.gerer_2") !!}
                            </li>
                            <li class="puce">
                               {!! __("bailleur.gerer_3") !!}

                            </li>
                        </ul>
                    </div>
                </div>

             </div>
             <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
                <div> <h2 class="title-paragraphe title-2">{!! __('bailleur.title_chatter') !!}</h2></div>
                <div class="contenu-paragraphe contenu-paragraphe-2">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("bailleur.chatter_1") !!}
                            </li>
                            <li class="puce">
                            {!! __("bailleur.chatter_2") !!}

                            </li>
                            <li class="puce">
                            {!! __("bailleur.chatter_3") !!}

                            </li>
                             <li class="puce">
                            {!! __("bailleur.chatter_4") !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image abs abs-30">
                    <img src="/img/chatter-bailleur-candidat.png" height="370"/>
                </div>

             </div>
              <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <h2 class="title-paragraphe title-paragraphe-1">{!! __("bailleur.title_regrouper") !!}</h2>
                <div class="img-box-3 box-image abs">
                    <img src="/img/regrouper-colocataire.png"/>

                </div>
                <div class="contenu-paragraphe par-right">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("bailleur.regrouper_1") !!}
                            </li>
                            <li class="puce">
                               {!! __("bailleur.regrouper_2") !!}
                            </li>
                        </ul>
                    </div>
                </div>

             </div>
             <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <div> <h2 class="title-paragraphe">{!! __('bailleur.title_louer') !!}</h2></div>
                <div class="contenu-paragraphe">

                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            <span class="bold-li">{!! __("bailleur.span_louer_1") !!}</span>
                            </li>
                            <li class="puce">
                            {!! __("bailleur.louer_2") !!}

                            </li>
                            <li class="puce">
                                {!! __("bailleur.louer_3") !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image">
                    <img src="/img/louer-confiance.png" height="300"/>

                </div>

             </div>
              <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <h2 class="title-paragraphe title-paragraphe-1">{!! __("bailleur.title_visit") !!}</h2>
                <div class="img-box-3 box-image abs">
                    <img src="/img/visit-detail-automatic.png"/>

                </div>
                <div class="contenu-paragraphe par-right">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {{__("bailleur.visit_1")}}
                            </li>
                            <li class="puce">
                               {!! __("bailleur.visit_2") !!}

                            </li>
                        </ul>
                    </div>
                </div>

             </div>
            <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
               <div> <h2 class="title-paragraphe">{!! __('bailleur.title_securiser') !!}</h2></div>
                <div class="contenu-paragraphe">

                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("bailleur.securiser_1") !!}
                            </li>
                             <li class="puce">
                            {!! __("bailleur.securiser_2") !!}
                            </li>
                             <li class="puce">
                            {!! __("bailleur.securiser_3") !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image abs abs-30">
                    <img src="/img/security.png"/>

                </div>

             </div>


        </div>
    </div>
</section>
<style>

     .ligne
    {
        margin-top : 30px;
    }
    .title-paragraphe
    {
        margin-top : 30px;
        margin-bottom : 10px;
        font-size : 1.4em;
        color : rgb(40,146,245);
    }

    .title-principale
    {
        color : rgb(40,146,245);
         font-size : 1.6em;
         margin-bottom : 20px;
    }

   .bold-li
   {
     font-weight: bold;
   }
    .puce::before
    {
        content:url('/img/puce-image.png');
        margin-right: 20px;
    }

    .puce
    {
        margin-top : 30px;
    }



    @media screen and (max-width : 719px)
    {
        .title-paragraphe
        {
            text-align: center;
        }
        .box-image
        {
            position: relative !important;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .contenu-paragraphe
        {
            width : 100%;
            display: block;

        }
        .contenu-paragraphe .white-bg
        {
             padding-top: 20px;
             padding-bottom: 20px;
        }

        .par-right
        {
            float: initial !important;
        }
    }
    @media screen and (min-width : 720px)
    {
         .title-paragraphe
        {
            width : 45%;
        }
        .title-paragraphe-1
        {
            margin-left : 53.2%;
        }
        .img-box-1
        {
            width : 45%;
            display: inline-block;
            margin-right : 6.5%;
        }

        .img-box-1 img
        {
            width : 80%;
            height : 230px;
        }
        .img-box-3
        {
            width : 45%;
            display: inline-block;
            margin-right : 6.5%;
        }

         .img-box-3 img
        {
            width : 80%;
            height : 230px;
        }

        .box-image img
        {
            vertical-align: baseline;
        }

        .img-box-2
        {
            width : 45%;
            display: inline-block;
            margin-left : 6.5%;
        }
        .contenu-paragraphe
        {
            width : 45%;
            display: inline-block;

        }

         .contenu-paragraphe .white-bg
        {
             padding-top: 20px;
             padding-bottom: 20px;
        }
    }

    .relative
    {
        position: relative;
    }

    .abs
    {
        position: absolute;
    }

    .cdd
    {
        top : 60px;
    }

    .first-puce
    {
        margin-top : 10px !important;
    }

    .abs-30
    {
        bottom: -30px;
    }

    .par-right
    {
        float: right;
        margin-right: 20px;
    }
</style>
@endsection


