@foreach($apps as $app)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-rect">
            <img @if(!empty($request->sender_ads->ad_files) && count($request->sender_ads->ad_files) > 0) class="pic_available" src="{{URL::asset('uploads/ads_images/' . $request->sender_ads->ad_files[0]->filename )}}" alt="{{$request->sender_ads->ad_files[0]->user_filename}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
        </figure>
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span id="user-name-{{$app->application->id}}">@if(!empty($app->user)){{$app->user->first_name}} {{$app->user->last_name}}@endif</span></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{__('application.sent_on')}} -  {{dateLocale($app->application->created_at)}}</div>
            </div>
        </div>
        <div class="rb-mid-detail">     
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <div class="roomate-grid-price-only">
                    <h4>
                        @if(!empty($app->sender_ads->min_rent) && $app->sender_ads->min_rent != 0)
                        {{$app->sender_ads->min_rent}} €
                        <sub> {{__('application.per_month')}}</sub>
                        @else
                        {{__('searchlisting.a_negocier')}}
                        @endif
                    </h4>
                </div>
                <div class="looking-now active">@if(date_create($app->sender_ads->available_date) > date_create('today')) {{__('application.available_from')}} {{date('j M', strtotime($app->sender_ads->available_date))}} @else {{__('application.available')}} @endif</div>
            </div>
            @if(!empty($app->application->motivation))
            <p>{{__('application.motivation')}} - {{$app->application->motivation}}</p>
            @endif
            @if(empty($app->documents) || count($app->documents) == 0)
                <div class="">
                    {{__('application.no_document')}} 
                </div>
            @else
                <div class="documents">
                    <fieldset>
                        <legend>{{__('application.document')}}</legend>
                    @foreach($app->documents as $documents)
                        @if(isset($documents->document_id) && !empty($documents->document_id))
                        <div>
                            <a href="{{url('/uploads/tempfile/' . $documents->name)}}" target="_blank">
                                {{$documents->document_id->document_name}} <span class="fa fa-search"></span>
                            </a>
                        </div>
                        @endif
                    @endforeach
                    </fieldset>
                </div>
            @endif
            @if($app->application->status == 0)
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    <a href="javascript:" class="accept-application" data-id="{{$app->application->id}}">{{__('application.accept')}}</a>
                </div>
                <div class="porject-btn-1">
                    <a href="javascript:" class="decline-application" data-id="{{$app->application->id}}">{{__('application.decline')}}</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>                            
@endforeach
<div id="application-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name"> {{__('application.contact')}}</h4>
            </div>
            <div class="modal-body">
                <form class="super-form" action="/applications/submit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" id="application-modal-action">
                    <input type="hidden" name="id" id="application-modal-id">
                    <div class="form-group">
                        <textarea name="message" placeholder="{{__('application.message')}}" class="form-control"></textarea>
                    </div>
                    <div class="form-group" style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{__('application.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>