@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="/css/custom_checkbox.css" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/messageroomstrash.js') }}"></script>
    <script src="{{ asset('js/scrollTo.js') }}"></script>
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
            <div class="col-md-5 col-sm-5">
                <div class="messagelistblock">
                    <form name="deletemessages" id="deletemessages" action="{{ route('delete.threads') }}" method="post" enctype="multipart/form-data" >
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
                                        <li><a href="{{ route('viewmessagerooms.inbox') }}"><i class="fa fa-inbox"></i> {{ __('messages.inbox') }}</a></li>
                                        <li><a href="{{ route('viewmessagerooms.inbox') }}?type=sent"><i class="fa fa-send-o"></i> {{ __('messages.envoye') }}</a></li>
                                        <li class="active"><a href="javascript:"><i class="fa fa-trash"></i> {{ __('messages.trash') }}</a></li>
                                    </ul>
                                </div>
                                @if(!empty($sideBarArray))
                                <div class="message-userslist">
                                    @foreach($sideBarArray as $key1 => $sideBar)
                                    <div class="searchbytitle">
                                        <h3>{{$sideBar['ad_info']->title}}</h3>
                                        <ul>
                                            @foreach($sideBar['message_info'] as $key2 => $messageInfo)
                                            <li @if(Auth::id() == $messageInfo[0]->receiver_id)user_id="{{$messageInfo[0]->sender_id}}" @else user_id="{{$messageInfo[0]->receiver_id}}" @endif thread_id="{{$messageInfo[0]->thread_id}}" @if(!empty($messageInfo[2]) && $messageInfo[2] == 'yes') class="active" @endif>
                                                <figure class="brdr-radi">
                                                    <img @if(!empty($messageInfo[1]) && !empty($messageInfo[1]->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $messageInfo[1]->user_profiles->profile_pic)}}" alt="{{$messageInfo[1]->user_profiles->profile_pic}}" title="{{$messageInfo[1]->first_name}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" title="{{$messageInfo[1]->first_name}}" @endif/>
                                                </figure>
                                                <div class="userinfo">
                                                    <div class="msg-checkbx"><input class="custom-checkbox" id="pf-checkbox-{{$messageInfo[0]->thread_id}}" name="delete_thread[]" value="{{$messageInfo[0]->thread_id}}" type="checkbox">
                                                        <label for="pf-checkbox-{{$messageInfo[0]->thread_id}}"></label>
                                                    </div>
                                                    <p class="messagener-name">{{$messageInfo[1]->first_name}}</p>
                                                    <p class="messanger-msg">
                                                        @if(Auth::id() == $messageInfo[0]->sender_id){{ __('You') }} @else{{$messageInfo[1]->first_name}} @endif: {{$messageInfo[0]->message}}</p>
                                                    <span class="count-glyph"></span>
                                                </div> <!-- End UserInfo -->
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div> <!-- End SearchByTitle -->
                                    @endforeach
                                </div> <!-- End MessageUsersList -->
                                @else
                                <div class="aucun_message aucun_message-mobile">
                                    {{__('messages.aucun_message')}}
                                </div>
                                @endif
                            </div> <!-- End AddressTab -->
                        </div> <!-- End TabContent -->
                    </form>
                </div> <!-- End MessageListBlock -->
            </div> <!-- End Col -->
            <div id="message_box">
                @if(!empty($sideBarArray))
                    @include('messages.message-room-data-trash')
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