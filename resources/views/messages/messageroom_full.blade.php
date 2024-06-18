@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="/css/custom_checkbox.css" rel="stylesheet">
    <link href="/css/message.css" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/messagerooms.js') }}"></script>
    <script src="{{ asset('js/scrollTo.js') }}"></script>
    <script src="/js/return_handler.js"></script>
@endpush

@section('content')
<style type="text/css">
    body{
        background-color: #fff;
    }
</style>

<section class="inner-page-wrap">
<div class="container">

    <div class="messagepage">
        <div class="row">
            @if ($message = Session::get('status'))
            <div class="col-xs-12">
                <div class="alert alert-success fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>
            </div>

            @endif

            @if ($message = Session::get('error'))
            <div class="col-xs-12">
                <div class="alert alert-danger fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>
            </div>

            @endif
            <div class="col-xs-12">
                <div class="alert alert-danger fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{__('messages.escroquerie_alert')}}
                </div>
            </div>
            <div class="col-md-5 col-sm-5">
                <div class="messagelistblock">
                    <form name="deletemessages" id="deletemessages" action="{{ route('archive.threads') }}" method="post" enctype="multipart/form-data" >
                        {{ csrf_field() }}

                        <div class="text-right msg-del-btn-outer">
                            <button type="submit" class="msg-del-btn">{{ __('messages.delete') }} (<span id="delete_count"></span>)</button>
                        </div>
                        <div class="tab-content tabbox">
                            <div class="tab-pane fade in active" role="tabpanel">
                                <h3> <i class="fa fa-inbox"></i> <span>{{ __('messages.message_room') }}</span>
                                <label class="container-checkbox check-unread">
                                  <span class="text-unread">{{__('messages.non_lu')}}</span>
                                  <input type="checkbox" id="check-unread"  value="" class="check-upsel" @if(!is_null(getParameter("type")) && getParameter("type") == 'unread') checked @endif>
                                  <span class="checkmark checkmark-unread"></span>
                                </label>
                                </h3>
    <!--                            <form name="searchmessage" class="searchform" action="" method="get" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <div class="searchicon"><i class="fa fa-search"></i></div>
                                        <input type="search" name="searchwithname" autocomplete="on" placeholder="Search" />
                                    </div>
                                </form>-->
                                <div class="inbx-tresh-bx">
                                    <ul>
                                        <li @if(is_null(getParameter("type")) || getParameter("type")== 'unread') class="active" @endif><a href="{{ route('viewmessagerooms.inbox') }}"><i class="fa fa-inbox"></i> {{ __('messages.inbox') }}</a></li>
                                        <li id="li-sent" @if(!is_null(getParameter("type")) && getParameter("type") == 'sent') class="active" @endif><a href="{{ route('viewmessagerooms.inbox') }}?type=sent">
                                                <i class="fa fa-send-o"></i> {{ __('messages.envoye') }}</a></li>
                                        <li><a href="{{ route('viewmessagerooms.trash') }}"><i class="fa fa-trash"></i> {{ __('messages.trash') }}</a></li>
                                    </ul>
                                </div>
                                @if(!is_null(getParameter('type')) && getParameter('type')=='sent')
                                    <input type="hidden" id="room-type" value="sent">
                                @elseif(!is_null(getParameter('type')) && getParameter('type')=='unread')
                                <input type="hidden" id="room-type" value="unread">
                                @endif
                                <div id="message-sidebar-list" class="message-userslist">
                                @if(!empty($sideBarArray))
                                    <?php $i = 0;?>
                                    @foreach($sideBarArray as $key1 => $sideBar)
                                    <div class="searchbytitle" id="side-{{$sideBar['ad_info']->id}}">
                                        <h3><a class="default-link" href="{{adUrl($sideBar['ad_info']->id)}}">{{$sideBar['ad_info']->title}}</a></h3>
                                        <ul>
                                            @foreach($sideBar['message_info'] as $key2 => $messageInfo)
                                            <?php $i++;?>
                                            @if($i==1) <input type="hidden" id="maxMessageId" value="{{$messageInfo[0]->id}}"> @endif
                                            <li class="side-bar-message @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) uread-mesg @endif  @if($i==1) active @endif"

                                            @if(Auth::id() == $messageInfo[0]->receiver_id)user_id="{{$messageInfo[0]->sender_id}}" ad_id="{{base64_encode($messageInfo[0]->sender_ad_id)}}" sender_ad_id="{{base64_encode($messageInfo[0]->ad_id)}}" @else user_id="{{$messageInfo[0]->receiver_id}}"
                                            ad_id="{{base64_encode($messageInfo[0]->ad_id)}}" sender_ad_id="{{base64_encode($messageInfo[0]->sender_ad_id)}}" @endif thread_id="{{$messageInfo[0]->thread_id}}" @if($messageInfo[2] == 'yes') class="active" @endif
                                            read="{{$messageInfo[0]->read}}"
                                            message_id="{{$messageInfo[0]->id}}"
                                            >
                                                <figure class="brdr-radi">
                                                    <img @if(!empty($messageInfo[1]) && !empty($messageInfo[1]->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $messageInfo[1]->user_profiles->profile_pic)}}" alt="{{$messageInfo[1]->user_profiles->profile_pic}}" title="{{$messageInfo[1]->first_name}}" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" title="{{$messageInfo[1]->first_name}}" @endif/>
                                                </figure>
                                                <div class="userinfo">
                                                    <div class="msg-checkbx"><input class="custom-checkbox" id="pf-checkbox-{{$messageInfo[0]->thread_id}}" name="delete_thread[]" value="{{$messageInfo[0]->thread_id}}" type="checkbox">
                                                        <label for="pf-checkbox-{{$messageInfo[0]->thread_id}}"></label>
                                                    </div>
                                                    <p class="unread-div messagener-name @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) unread-message-sideBar @endif"">@if($messageInfo[0]->temp_user) {{$messageInfo[0]->temp_user}}@else {{$messageInfo[1]->first_name}} @endif</p>
                                                    <p class="unread-div messanger-msg @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) unread-message-sideBar @endif">
                                                        @if(Auth::id() == $messageInfo[0]->sender_id)
                                                            {{ __('messages.you') }}
                                                        @else
                                                            @if($messageInfo[0]->temp_user) {{$messageInfo[0]->temp_user}}@else {{$messageInfo[1]->first_name}} @endif
                                                        @endif:
                                                        {{$messageInfo[0]->message}}
                                                    </p>
                                                    <span class="count-glyph text-center"></span>
                                                    {{-- style rouge a corriger  --}}
                                                </div>
                                                 <!-- End UserInfo -->
												@if($messageInfo[0]->sender_id == Auth::id() && $messageInfo[0]->read == 1 && !is_null($messageInfo[0]->read_date))<div class="view-div"><span class="span-check fa fa-check"></span> {{__("messages.vu")}} {{dateLocale(getUserDateByTimezone($messageInfo[0]->read_date), true)}}</div>@endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div> <!-- End SearchByTitle -->
                                    @endforeach
                                @else
                                <div class="aucun_message aucun_message-mobile">
                                    {{__('messages.aucun_message')}}
                                </div>
                                @endif
                                </div> <!-- End MessageUsersList -->
                            </div> <!-- End AddressTab -->
                        </div> <!-- End TabContent -->
                    </form>
                </div> <!-- End MessageListBlock -->
            </div> <!-- End Col -->

            <div id="message_box">
                @if(count($sideBarArray) > 0 && count($activeThreadMessages) > 0)
                @include('messages.message-room-data')
                @else
                <div class="col-md-7 col-sm-7 div-message-right">
                    <div class="message-chat-panel">
                        <div class="user-profile-bar">
                            <div class="aucun_message">
                                        {{__('messages.aucun_message')}}
                                    </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>
</section>
@endsection

@push('scripts')
<script>
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split("#")[0])
            : window.location.hash = "";

</script>
<script>
    var messages_text = {"vu" : '{{__("messages.vu")}}', "you" : '{{__("messages.you")}}'};
</script>


<script>
    $(document).ready(function(){
    getMessagesNotifControle($('#pageNotifMessage').val());

    function getMessagesNotifControle(page)
{
     $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(page == null) page = 0;
        $.ajax({
            type: "POST",
            url: '/message/get_all_message_notif_controle',
            data : {"page" : page},
            success: function (data) {
                if(!$.isEmptyObject(data)) {
                     $('.loader-icon').hide();
                    if(data.html != "") {
                        $('#messageTab').html(data.html);
                    }
                }
            }
        });
}
});
</script>

@endpush
