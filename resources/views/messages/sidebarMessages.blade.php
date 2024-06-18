<?php $i=0;?>
@foreach($sideBarArray as $key1 => $sideBar)
<div class="searchbytitle" id="side-{{$sideBar['ad_info']->id}}">
    <h3>{{$sideBar['ad_info']->title}}</h3>
    <ul>
        <?php $i++;?>
        @foreach($sideBar['message_info'] as $key2 => $messageInfo)
        @if($i==1) <input type="hidden" id="maxMessageId" value="{{$messageInfo[0]->id}}"> @endif
        <li class="side-bar-message @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) uread-mesg @endif @if($active_thread==$messageInfo[0]->thread_id) active @endif" @if(Auth::id() == $messageInfo[0]->receiver_id)user_id="{{$messageInfo[0]->sender_id}}" ad_id="{{base64_encode($messageInfo[0]->sender_ad_id)}}" sender_ad_id="{{base64_encode($messageInfo[0]->ad_id)}}" @else user_id="{{$messageInfo[0]->receiver_id}}" 
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
                <p class="unread-div messagener-name @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) unread-message-sideBar @endif"">@if($messageInfo[0]->temp_user) $messageInfo[0]->temp_user @else {{$messageInfo[1]->first_name}} @endif</p>
                <p class="unread-div messanger-msg @if($messageInfo[0]->receiver_id==Auth::id() && is_null($messageInfo[0]->read_date)) unread-message-sideBar @endif">
                    @if(Auth::id() == $messageInfo[0]->sender_id)
                        {{ __('messages.you') }} @else{{$messageInfo[1]->first_name}} 
                    @endif: 
                    {{$messageInfo[0]->message}}
                </p>
                <span class="count-glyph text-center"></span>
            </div>
            @if($messageInfo[0]->sender_id == Auth::id() && $messageInfo[0]->read == 1 && !is_null($messageInfo[0]->read_date))<div class="view-div"><span class="span-check fa fa-check"></span> {{__("messages.vu")}} {{dateLocale($messageInfo[0]->read_date, true)}}</div>@endif
        </li>
        @endforeach
    </ul>
</div> 
@endforeach