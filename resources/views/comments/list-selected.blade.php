@foreach($cs as $c)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-rect">
            <img @if(!empty($c->sender_ads->ad_files) && count($c->sender_ads->ad_files) > 0) class="pic_available" src="{{'/uploads/images_annonces/' . $c->sender_ads->ad_files[0]->filename}}" alt="{{$c->sender_ads->ad_files[0]->user_filename}}" @else class="no_pic_available" src="/images/room_no_pic.png" alt="{{ __('no pic') }}" @endif/>
        </figure>
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span>
                            {{$c->sender_ads->title}} 
                           </span></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="rb-mid-detail">
            
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <label>{{__('Question')}}:</label> 
                <span id="content-comment-edit-text-{{$c->id}}">{{ $c->text }}</span>
                @if(count($c->responses) <= 0)
                ( <a href="javascript:" class="comment-edit" data-id="{{$c->id}}">{{ __('comments.edit') }}</a> )
                @endif
            </div>
            <div>
                <ul>
                    @foreach($c->responses as $r)
                        <li>
                            <label>{{__('Response')}}: </label> 
                            <span id="content-response-edit-text-{{ $r->id }}">{{$r->text}}</span>
                            @if($type == 'received')
                            ( <a href="javascript:" class="response-edit" data-id="{{$r->id}}">{{ __('comments.edit') }}</a> )
                            ( <a href="javascript:" class="response-remove" data-id="{{$r->id}}">{{ __('comments.remove') }}</a> )
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    @if(!empty($c->sender_ads))
                    <a href="{{adUrl($c->sender_ads->id, $c->sender_ads->sender_id)}}">{{ __('comments.ad_details') }}</a>
                    @endif
                </div>
                @if($type == 'received')
                <div class="porject-btn-1">
                    <a href="javascript:" class="respond" data-id="{{$c->id}}">{{ __('comments.respond') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>                            
@endforeach

<div id="response-edit-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('comments.contact') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/my-comment/response/edit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" id="response-edit-modal-action">
                    <input type="hidden" name="id" id="response-edit-modal-id">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{ __('comments.response') }}" class="form-control" id="response-edit-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{ __('comments.submit') }}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="response-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('comments.contact') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/my-comment/response/add" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="comment_id" id="response-modal-comment-id">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{ __('comments.response') }}" class="form-control" id="response-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{ __('comments.submit') }}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="comment-edit-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('comments.contact') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/comment/edit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="ad_id" id="comment-modal-ad-id">
                    <input type="hidden" name="comment-id" id="comment-modal-action">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{ __('comments.comment') }}" class="form-control" id="comment-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{ __('comments.submit') }}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
