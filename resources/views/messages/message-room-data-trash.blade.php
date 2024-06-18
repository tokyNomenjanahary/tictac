<div class="col-md-7 col-sm-7">
    <div class="message-chat-panel">
        <div class="user-profile-bar"> <p><strong>{{$otherUserInfo->first_name}}@if(!empty($otherUserInfo->last_name)){{' '.$otherUserInfo->last_name}}@endif</strong> - <span>@if(!empty($otherUserInfo->user_profiles) && !empty($otherUserInfo->user_profiles->birth_date)){{date_diff(date_create($otherUserInfo->user_profiles->birth_date), date_create('today'))->y}}@endif/@if(!empty($otherUserInfo->user_profiles))@if($otherUserInfo->user_profiles->sex == 0){{ __('messages.male') }}@else{{ __('messages.female') }}@endif @endif </span> <span class="pull-right">@if(!empty($otherUserInfo->user_profiles) && (!empty($otherUserInfo->user_profiles->professional_category) || (isset($otherUserInfo->user_profiles->professional_category) && $otherUserInfo->user_profiles->professional_category == 0)))@if($otherUserInfo->user_profiles->professional_category == 0){{ __('messages.student') }}@elseif($otherUserInfo->user_profiles->professional_category == 1){{ __('messages.freelancer') }}@else{{ __('messages.salaried') }}@endif @endif</span></p></div>

        <div class="message-chat-block" id="{{$active_thread_id}}">
            @foreach($activeThreadMessages as $activeThreadMessage)
            @if(Auth::id() == $activeThreadMessage->receiver_id)
            <div class="chat-block-box sender-block">
                <div class="sender-thumb">
                    <figure class="brdr-radi">
                        <img @if(!empty($otherUserInfo->user_profiles) && !empty($otherUserInfo->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $otherUserInfo->user_profiles->profile_pic)}}" alt="{{$otherUserInfo->user_profiles->profile_pic}}" title="{{$otherUserInfo->first_name}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" title="{{$otherUserInfo->first_name}}" @endif/>
                    </figure>
                    <p class="sender_name">{{$otherUserInfo->first_name}}</p>
                </div> <!-- End SenderThumb -->
                <div class="chat-message">
                    <p>{{$activeThreadMessage->message}}</p>
                    <div class="chat-date_time">    <p><span class="chat_date">{{date('m/d/Y', strtotime($activeThreadMessage->created_at))}}</span> <span class="chat_time">{{date("h:i A", strtotime(convertDateByTimezone($activeThreadMessage->created_at)))}}</span></p></div> <!-- End ChatDateTime -->
                </div> <!-- End SenderMessage -->
            </div> <!-- End SenderBlock -->
            @else
            <div class="chat-block-box receiver-block">
                <div class="chat-message">
                    <p>{{$activeThreadMessage->message}}</p>
                    <div class="chat-date_time">    <p><span class="chat_date">{{date('m/d/Y', strtotime($activeThreadMessage->created_at))}}</span> <span class="chat_time">{{date("h:i A", strtotime(convertDateByTimezone($activeThreadMessage->created_at)))}}</span></p></div> <!-- End ChatDateTime -->
                </div> <!-- End SenderMessage -->
            </div> <!-- End ReceiverBlock -->
            @endif
            @endforeach
        </div> <!-- End MessageChatBlock -->
    </div> <!-- End MessageChatPanel -->
</div>