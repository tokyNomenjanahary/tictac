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
                        <h1 class="title-principale">{!! __('locataire.sous_title_locataire') !!}</h1>
                        <div class="intro">
                        {!!  __('locataire.first_message_locataire') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <div><h2 class="title-paragraphe title-paragraphe-1">{!! __("locataire.futur_colocataire") !!}</h2></div>
                <div class="img-box-1 box-image">
                    <img src="/img/futur-colocataire.png"/>

                </div>
                <div class="contenu-paragraphe">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!!  __('locataire.futur_1') !!}
                            </li>
                            <li class="puce">
                                {!!  __('locataire.futur_2') !!}
                            </li>
                            <li class="puce">
                                {!!  __('locataire.futur_3') !!}
                            </li>
                        </ul>
                    </div>
                </div>

             </div>
             <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
                <div> <h2 class="title-paragraphe title-2">{!! __('locataire.meme_ecole') !!}</h2></div>
                <div class="contenu-paragraphe contenu-paragraphe-2">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!!  __('locataire.meme_ecole_1') !!}
                            </li>
                            <li class="puce">
                            {!! __("locataire.meme_ecole_2") !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image abs">
                    <img src="/img/ecole-locataire.png" height="370"/>
                </div>

             </div>
              <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description">
                 <h2 class="title-paragraphe title-paragraphe-1">{!! __("locataire.denichez") !!}</h2>
                <div class="img-box-3 box-image abs_10">
                    <img class="img-right" src="/img/mode-de-vie.png" height="370"/>

                </div>
                <div class="contenu-paragraphe  p-right">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("locataire.denichez_1") !!}
                            </li>
                            <li class="puce">
                               {!! __("locataire.denichez_2") !!}

                            </li>
                            <li class="puce">
                                {!! __("locataire.denichez_3_span") !!}
                            </li>
                        </ul>
                    </div>
                </div>

             </div>
             <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
                 <div> <h2 class="title-paragraphe">{!! __('locataire.profitez') !!}</h2></div>
                <div class="contenu-paragraphe">

                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("locataire.profitez_1") !!}

                            </li>
                            <li class="puce">
                            {!! __("locataire.profitez_2") !!}
                            </li>
                            <li class="puce">
                                {!! __("locataire.profitez_3") !!}

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image abs abs-10">
                    <img class="special" src="/img/chatter-bailleur-candidat.png" height="370"/>

                </div>

             </div>
              <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
                 <h2 class="title-paragraphe title-paragraphe-1">{!! __("locataire.candidature") !!}</h2>
                <div class="img-box-3 box-image abs abs-30">
                    <img src="/img/envoi-candidature.png"/>

                </div>
                <div class="contenu-paragraphe par-right">
                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("locataire.candidature_1") !!}
                            </li>
                            <li class="puce">
                               {!! __("locataire.candidature_2") !!}
                            </li>
                        </ul>
                    </div>
                </div>

             </div>
            <div class="user-visit-main-hdr ligne" style="float: left; width: 100%;"></div>
             <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer div-principale-description relative">
               <div> <h2 class="title-paragraphe">{!! __('locataire.composer') !!}</h2></div>
                <div class="contenu-paragraphe">

                    <div class="myad-cont-bx white-bg ">
                        <ul>
                            <li class="puce first-puce">
                            {!! __("locataire.composer_1") !!}
                            </li>
                             <li class="puce">
                            {!! __("locataire.composer_2") !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="img-box-2 box-image abs abs-20">
                    <img src="/img/composer-locataire.png"/>

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
        margin-top : 40px;
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

        /*.title-2
        {
            margin-bottom : 100px;
        }*/
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

         .img-box-3 img.special
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
        .img-right
        {
            margin-left : 40%;
        }
    }

    @media screen and (min-width : 1295px)
    {
        .p-right
        {
            float: right;
            margin-right: 20px;
        }

        .abs_10
        {
            position: absolute;
            bottom: 10px;
        }

        .abs-10
        {
            bottom: -10px;
        }

        .abs-30
        {
            bottom: -30px;
        }
        .abs-30 img
        {
            height: 270px;
        }

        .abs-20
        {
            bottom: -20px;
        }
    }

    .abs
    {
        position: absolute;
    }

    .relative
    {
        position: relative;
    }

    .par-right
    {
        float: right;
        margin-right: 20px;
    }

    .first-puce
    {
        margin-top : 10px !important;
    }
</style>
@endsection

