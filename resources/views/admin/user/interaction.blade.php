@extends('layouts.adminappinner')
@push('scripts')
<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Interaction
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('status'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12 show-message">
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">

                            <tr>

                                <th>From</th>                                
                                <th>To</th>
                                <th>Type</th>
                                <th>Message</th>
                                <th>Ad</th>
                                <th>Date</th>
                            </tr>
                            @if(!empty($results))
                            @foreach($results as $key => $result)
                            <tr>
                                <td>
                                    <a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($result->sender_id))}}">
                                        {{$result->sender_prenom}} {{$result->sender_nom}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($result->receiver_id))}}">
                                        {{$result->receiver_prenom}} {{$result->receiver_nom}}
                                    </a>
                                </td>
                                <td>{{$result->type_interaction}}</td>
                                <td class="message-interaction"><a href="javascript:">{{$result->message}}</a></td>
                                <td><a href="{{adUrl($result->ad_id)}}">{{$result->annonce}}</a></td>
                                <td>{{adjust_gmt($result->date)}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> 
<div id="message-mobile-windows">
    
</div>  

<style>
    #message-mobile-windows
    {
        position: absolute;
        z-index: 88888888888;
        background-color: black;
        padding: 5px;
        max-width: 350px;
        /* white-space: nowrap; */
        /* overflow: hidden; */
        word-wrap: break-word;
        border-radius: 5px;
        color: white;
        display: none;
    }

    .message-interaction
    {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; 
    }
</style> 
<script>
    $(document).ready(function(){
        $(document).on('mouseenter', '.message-interaction', function(){
            var pos = $(this).offset();
            $('#message-mobile-windows').text($(this).text());
            $('#message-mobile-windows').show();
            $('#message-mobile-windows').css("top", pos.top - $('#message-mobile-windows').height());
            $('#message-mobile-windows').css("left", pos.left - 10);
        });
        $(document).on('mouseleave', '.message-interaction', function(){
            $('#message-mobile-windows').hide();
        });
    });

</script>
@endsection