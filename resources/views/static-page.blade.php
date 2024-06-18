@extends('layouts.app')

@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer">
                <div class="user-visit-main-hdr" style="float: left; width: 100%">
                    <h4>{{$page_detail->title}}</h4>
                </div>
                <div class="myad-cont-bx white-bg m-t-2" style="margin-top: 20px;float:left">
                    <div class="myad-bx">
                        {!! $page_detail->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection