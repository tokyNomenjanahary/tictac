<form id="requestToVisit" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <input type="hidden" name="ad_id" value="{{$id}}">
    <input type="hidden" name="sender_ad_id" value="{{$sender_ad_id}}">
    
    @if(!empty($visiting_details) && count($visiting_details) > 0)
    <h6>{{ __('addetails.available_time_slots') }}</h6>
    <div class="m-t-2">
    @foreach($visiting_details as $key => $visiting_detail)
    <div class="custom-radio1">
        <input id="visit-detail-op{{$key}}" @if(!is_null(getParameter('visit_id')) && $visiting_detail->id == getParameter('visit_id')) checked @endif name="visit_detail_id" autocomplete="off" value="{{$visiting_detail->id}}" type="radio">
        <label for="visit-detail-op{{$key}}">
            <div class="p-a-15">
                <div class="ad-bd-vist-bx">
                    <div class="ad-bd-vist-hdd">
                        <div class="ad-bd-vist-hdd-icon">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                        <h6>{{date('j M. Y', strtotime($visiting_detail->visiting_date))}}</h6>
                        <span class="ad-bd-vist-tm"><strong>{{ __('addetails.time') }}:</strong> @if(!empty($visiting_detail->end_time)){{ __('addetails.between') }} {{date("g:i a", strtotime($visiting_detail->start_time))}} {{ __('addetails.to') }} {{date("g:i a", strtotime($visiting_detail->end_time))}}@else{{ __('addetails.from') }} {{date("g:i a", strtotime($visiting_detail->start_time))}} @endif</span>
                    </div>
                    @if(!empty($visiting_detail->notes))
                    <p>{{$visiting_detail->notes}}</p>
                    @endif
                </div>
            </div>
        </label>
    </div>
    @endforeach
    </div>
    <div class="or-divider"><span>{{ __('login.or') }}</span></div>
    @endif
    <div class="custom-check2 form-group">
        <input @if(!empty($visiting_details) && count($visiting_details) == 0) checked @endif id="check_slot" type="checkbox" name="check_slot" class="custom-checkbox form-control" value="1">
        <label class="control-label" for="check_slot">{{ __('addetails.check_this_box') }}</label>
    </div>
    <div class="visit-detail-bx-outer" style="margin-top: 0px;">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label class="control-label" for="date_of_visit">{{ __('addetails.date_visit') }}</label>
                    <div class="datepicker-outer">
                        <div class="custom-datepicker">
                            <input class="form-control" id="date_of_visit" name="date_of_visit" readonly="" placeholder="dd/mm/yyyy" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label class="control-label" for="start_time">{{ __('addetails.heure_debut') }}</label>
                    <input id="start_time" name="start_time" class="form-control ui-timepicker-input" readonly="" autocomplete="off" type="text">
                </div>  
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label class="control-label" for="end_time">{{ __('addetails.heure_fin') }}</label>
                    <input id="end_time" name="end_time" class="form-control ui-timepicker-input" readonly="" autocomplete="off" type="text">
                </div>  
            </div>

        </div>
        <div class="form-group">
            <label class="control-label" for="note">{{ __('addetails.notes') }}</label>
            <textarea id="note" name="note" class="form-control" placeholder="{{__('addetails.notes') }}" rows="4"></textarea>
        </div>
        <div class="ad-detail-ftr"><p>{{ __('addetails.max_car') }}</p></div>
    </div>

    <div class="pr-poup-ftr">
        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('addetails.cancel') }}</a></div>
        <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-req-to-visit">{{__('addetails.submit')}}</a></div>
    </div>
</form>