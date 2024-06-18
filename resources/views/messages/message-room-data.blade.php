<div class="col-md-7 col-sm-7 div-message-right">
    <div class="message-chat-panel">
        <div class="user-profile-bar"> 
        <div class="row">
            <div class="bouton-retour-reception">
            <a href="javascript:" ><img src="/images/icone-retour.png" width="30" height="30" alt="retour boit de reception" /></a>
            </div>
        </div>
        <p><strong>@if($otherUserInfo->temp_user) {{$otherUserInfo->temp_user}} @else {{$otherUserInfo->first_name}}@endif</strong> <span>@if(!empty($otherUserInfo->user_profiles) && !empty($otherUserInfo->user_profiles->birth_date))
        -
        {{date_diff(date_create($otherUserInfo->user_profiles->birth_date), date_create('today'))->y}}@endif
        @if(!empty($otherUserInfo->user_profiles))
        /
        @if($otherUserInfo->user_profiles->sex == 0){{ __('messages.male') }}@else{{ __('messages.female') }}@endif @endif </span> <span class="pull-right">@if(!empty($otherUserInfo->user_profiles) && (!empty($otherUserInfo->user_profiles->professional_category) || (isset($otherUserInfo->user_profiles->professional_category) && $otherUserInfo->user_profiles->professional_category == 0)))@if($otherUserInfo->user_profiles->professional_category == 0){{ __('messages.student') }}@elseif($otherUserInfo->user_profiles->professional_category == 1){{ __('messages.freelancer') }}@else{{ __('messages.salaried') }}@endif @endif</span></p></div>

        <div class="message-chat-block" id="{{$active_thread_id}}">
            @foreach($activeThreadMessages as $key => $activeThreadMessage)
            @if(Auth::id() == $activeThreadMessage->receiver_id)
            <div class="chat-block-box sender-block">
                <div class="sender-thumb">
                    <figure class="brdr-radi">
                        <img @if(!empty($otherUserInfo->user_profiles) && !empty($otherUserInfo->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $otherUserInfo->user_profiles->profile_pic)}}" alt="{{$otherUserInfo->user_profiles->profile_pic}}" title="{{$otherUserInfo->first_name}}" @else src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" title="{{$otherUserInfo->first_name}}" @endif/>
                    </figure>
                    <p class="sender_name">{{$otherUserInfo->first_name}}</p>
                </div> <!-- End SenderThumb -->
                <div class="chat-message">
                    <p>{{$activeThreadMessage->message}}</p>
                    <div class="chat-date_time">    <p><span class="chat_date">{{date('m/d/Y', strtotime(getUserDateByTimezone($activeThreadMessage->created_at)))}}</span> <span class="chat_time">{{date("h:i A", strtotime(getUserDateByTimezone($activeThreadMessage->created_at)))}}</span></p></div> <!-- End ChatDateTime -->
                </div> <!-- End SenderMessage -->
            </div> <!-- End SenderBlock -->
            @else
            <div class="chat-block-box receiver-block">
                <div class="chat-message">
                    <p>{{$activeThreadMessage->message}}</p>
                    <div class="chat-date_time">    <p><span class="chat_date">{{date('m/d/Y', strtotime(getUserDateByTimezone($activeThreadMessage->created_at)))}}</span> <span class="chat_time">{{date("h:i A", strtotime(getUserDateByTimezone($activeThreadMessage->created_at)))}}</span></p></div> <!-- End ChatDateTime -->
                </div> <!-- End SenderMessage -->
            </div> <!-- End ReceiverBlock -->  
             @if(($key == count($activeThreadMessages) - 1) && !is_null($activeThreadMessage->read_date) && ($activeThreadMessage->read == 1) && ($activeThreadMessage->sender_id == Auth::id()))
            <div id="view-div"><span class="span-check fa fa-check"></span> {{__("messages.vu")}} {{dateLocale(getUserDateByTimezone($activeThreadMessage->read_date), true)}}</div>
            @endif

            @endif
            @if(($key == count($activeThreadMessages) - 1))
            <input type="hidden" id="last_received_message" value="{{$activeThreadMessage->id}}"/>
            @endif 
           
            @endforeach
        </div> <!-- End MessageChatBlock -->
		
    </div> <!-- End MessageChatPanel --> 


    <input type="hidden" name="subscription_link" id="subscription_link" value="{{route('subscription_plan')}}">
    <div class="comment-section">
        <form id="sendMessageForm" class="comment-form" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <input type="hidden" name="ad_id" value="{{$adId}}">
            <input type="hidden" name="sender_ad_id" value="{{$senderAdId}}">
            @if(!empty($activeThreadMessages))
             <input type="hidden" name="receiver_id" id="receiver_id" data-id="{{getReceiverId($activeThreadMessages)}}" value="{{base64_encode(getReceiverId($activeThreadMessages))}}">
            @endif
            
            <div class="form-group">
                <textarea id="message" name="message" class="form-control" required placeholder="{{ __('messages.message_placeholder') }}"></textarea>
            </div>
            <div class="form-group">
                <input type="button" name="submit-Comment" class="return_handle_button share-comment" value="{{ __('messages.send') }}" id="submit-send-message" />
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.bouton-retour-reception').on("click", function() {
            $('.div-message-right').hide();
            $('.messagelistblock').show();
        });
    });
</script>