
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
                        <h6><span id="user-name-{{$app->application->id}}">@if(!empty($app->user)){{$app->user->first_name}} {{$app->user->last_name}}@endif {{__('application.apply_for')}} 
                            @if(!empty($app->sender_ads))<a href="{{ url($app->sender_ads->url_slug) }}/{{ base64_encode($app->sender_ads->id) }}/{{ base64_encode($app->application->sender_id) }}">{{$app->sender_ads->title}}</a>@endif</span></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right"> 
					{{__('application.sent_on')}}  -  {{dateLocale($app->application->created_at)}}
					@if($app->application->user_id == Auth::id() && $app->application->viewed == 1 && !is_null($app->application->date_view))<div style="display : inline-block;"><span style="margin-left : 5px;" class="fa fa-check"></span> {{__('application.vu')}} {{dateLocale($app->application->date_view, true)}}</div>@endif
				</div>	
			</div>
        </div>
        <div class="rb-mid-detail">
            
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                @if(!empty($app->sender_ads))
                <div class="roomate-grid-price-only">
                <h4>
                @if(!empty($app->sender_ads->min_rent) && $app->sender_ads->min_rent != 0)
                &euro;{{$app->sender_ads->min_rent}} 
                <sub> {{__('application.per_month')}} </sub>
                @else
                {{__("searchlisting.a_negocier")}}
                @endif
                </h4>
                </div> 
                @endif
                @if(!empty($app->sender_ads))<div class="looking-now active">@if(date_create($app->sender_ads->available_date) > date_create('today')) {{__('application.available_from')}}  {{date('j M', strtotime($app->sender_ads->available_date))}} @else {{__('application.available')}}  @endif</div>@endif
                
				<div>
                    @if($app->application->status == 0)
                    <div class="waiting">
                        {{__('application.waiting')}} 
                    </div>
                    @elseif($app->application->status == 1)
                    <div class="accepted">
                        {{__('application.accepted')}} 
                    </div>
                    @else
                    <div class="declined">
                        {{__('application.declined')}} 
                    </div>
                    @endif
                </div>
            </div>
            @if(!empty($app->application->motivation))
            <p> {{__('application.motivation')}}  - {{$app->application->motivation}}</p>
            @endif
            @if(empty($app->documents) || count($app->documents) == 0)
                <div class="">
                     {{__('application.no_document')}} 
                </div>
            @else
                
                <div class="documents">
                    <fieldset>
                        <legend> {{__('application.document')}} </legend>
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
            @if($app->application->status == 0 && $type == 'received')
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    <a href="javascript:" class="accept-application" data-id="{{$app->application->id}}"> {{__('application.accept')}} </a>
                </div>
                <div class="porject-btn-1">
                    <a href="javascript:" class="decline-application" data-id="{{$app->application->id}}"> {{__('application.decline')}} </a>
                </div>
            </div>
            @endif
            @if($app->application->status == 0 && $type != 'received' && $app->application->user_id == Auth::user()->id)
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    <a href="{{ url('mes-candidatures/modifier-candidature') }}/{{ 'application-' . $app->application->id }}"> {{__('application.edit')}} </a>
                </div>
            </div>
            @endif
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    @if(!empty($app->sender_ads))
                    <a href="{{ adUrl($app->sender_ads->id)}}"> {{__('application.ad_details')}} </a>
                    @endif
                </div>
                {{-- @if($app->application->status == 1 || !empty($app->documents) || count($app->documents) != 0) --}}
                @if($app->application->status == 1 && !empty($app->documents))
                    @foreach($app->documents as $documents)
                    @if(isset($documents->document_id) && !empty($documents->document_id))
                        <div class="porject-btn-1">
                            {{-- <a href="{{route('document.transfert',['id'=> $documents->document_id])}}">{{__('application.tranfert_document')}}</a> --}}
                            <a href="javascript:" data-id="{{$documents->document_id}}" class="tranfert_doc" >{{__('documents.tranferer')}}</a>
                        </div>
                    @endif
                    @endforeach
                @endif
				@if($type="received" && isset($app->common_friend) && count($app->common_friend)>0)
                    <a data-id="{{$app->user->id}}" href="javascript:void(0);" class="fb_common right" style="margin-top:10px;"><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class=""> {{ __("application.common_friend") }} ({{count($app->common_friend)}})</span></a>
				@endif
				@if($type="received" && isset($app->common_friend) && count($app->common_friend)==0)
                    <a data-id="{{$app->user->id}}" href="javascript:void(0);" class="right" style="margin-top:10px;"><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class="">{{ __("application.no_common_friend_sender") }}</span></a>
				@endif
            </div>
        </div>
    </div>
</div>                            
@endforeach
<div id="application-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name"> {{__('application.contact')}} </h4>
            </div>
            <div class="modal-body">
                <form class="super-form" action="/applications/submit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" id="application-modal-action">
                    <input type="hidden" name="id" id="application-modal-id">
                    <h5 class="modal-title text-center">{{__('Si votre annonce a été générer depuis la création de bien dans
                        l\'espace propriétaire, votre locataire sera automatiquement ajouter et la location sera créer
                        automatiquement à partir de la date de début et fin que vous entrerez dans les champs
                        ci-dessous')}}
                    </h5>
                    <div class="form-group">
                        <label>{{__('Date de début de location')}}
                        <input type="date" required name="dateDebut" placeholder="{{__('Date de début')}}" class="form-control">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>{{__('Date de fin de location')}}
                            <input type="date" required name="dateFin" placeholder="{{__('Date de fin')}}" class="form-control">
                        </label>
                    </div>
                    <div class="form-group">
                        <textarea required name="message" placeholder="{{__('application.message')}}" class="form-control"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2">
                            <input type="submit" value="{{__('application.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="lalaina" class="modal fade" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name ">{{__('documents.Voulez_vous_lier_à un_locataire')}}</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('document.transfert')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="lalaina-modal-id">
                    <div class="form-group">
                         <select name="bien" id="bien" class="form-control">
                             <option value="">{{__(('documents.Selectionner_la_locataire'))}}</option>  
                                @foreach ($locataires as $locataire )
                                    <option value="{{$locataire->id}}">{{$locataire->TenantLastName}}</option>
                                @endforeach
                         </select>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2">
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">{{__('application.submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="common-friend-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><a href="#"><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class="">{{ __("application.common_friend_sender") }}</span></a></h4>
            </div>
            <div id="fb_friend_body">
				
			</div>
        </div>
    </div>
</div>
<script>
$(".fb_common").on("click", function(){
	$(".loader-icon").show();
	$.ajax({
		type: "POST",
		url: '/app_common_friend',
		data: {"user_id" : $(this).attr("data-id")},
		dataType: 'html',
		success: function (data) {
			$("#fb_friend_body").html(data);
			$("#common-friend-modal").modal("show");
			 $(".loader-icon").hide();
		}
	});
});
</script>