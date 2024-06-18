@extends('layouts.adminappinner')
@push('styles')
    <link href="/css/metro.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="{{ asset('js/admin/manageads.js') }}"></script>
    <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
    <script src="/js/metro_autocomplete.js"></script>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Community Manager<small>New ad</small>
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
            @if (isset($messageComunity))
                <div class="alert alert-success fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ $messageComunity }}
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-12 show-message">
                            <!-- general form elements -->
                            <form id="createAdForm" method="POST" action="{{ route('admin.post-create-ad') }}"
                                  enctype="multipart/form-data" role="form">
                                {{ csrf_field() }}
                                <input type="hidden" id="signalAd" name="signalAd" value="0">
                                <input type="hidden" name="type_signal" id="type_signal">
                                <div class="box box-primary ">
                                    <div class="box-header with-border">
                                        <h3 class="box-title text-danger bg-danger">{{__("Remplissez en premier les champs : Adresse * et Lien vers le profil facebook *.")}}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <label class="control-label" for="property_type">Support *</label>
                                                <input type="hidden" name="edit_ad_id"
                                                       @if(isset($ad)) value="{{$ad->id}}" @endif />
                                                <div class="custom-selectbx"
                                                     style="display: block; max-width: 250px !important;">
                                                    <select id="utm_medium" name="utm_medium" class="selectpicker">
                                                        <option value="groups"
                                                                @if(isset($ad) && $ad->utm_medium == "groups") selected @endif>
                                                            Groupe
                                                        </option>
                                                        <option value="profil"
                                                                @if(isset($ad) && $ad->utm_medium == "profil") selected @endif>
                                                            Profil
                                                        </option>
                                                        <option value="page"
                                                                @if(isset($ad) && $ad->utm_medium == "page") selected @endif>
                                                            Page
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="control-label" for="title">Support Id *</label>
                                                <input type="text" class="form-control" disabled="" id="utm_term"
                                                       placeholder="Entrer l'id du groupe ou de la page ou du profil"
                                                       name="utm_term" @if(isset($ad)) value="{{$ad->utm_term}}"
                                                       @endif autofocus>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="title">Post Id *</label>
                                                <input type="text" class="form-control" disabled="" id="utm_content"
                                                       placeholder="Entrer l'id de la publication" name="utm_content"
                                                       @if(isset($ad)) value="{{$ad->utm_content}}" @endif autofocus>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <label class="control-label" for="property_type">Type de l'annonce
                                                    *</label>
                                                <input type="hidden" name="edit_ad_id"
                                                       @if(isset($ad)) value="{{$ad->id}}" @endif />
                                                <div class="custom-selectbx"
                                                     style="display: block; max-width: 250px !important;">
                                                    <select id="scenario_id" name="scenario_id" class="selectpicker">
                                                        @foreach($scenarioTypes as $key => $value)
                                                            @if(isset($ad) && $ad->scenario_id == $key)
                                                                <option value="{{$key}}" selected>{{$value}}</option>
                                                            @else
                                                                <option value="{{$key}}">{{$value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc"
                                                 @if(isset($ad) && $ad->scenario_id != 1 && $ad->scenario_id != 3 ) style="display:none;" @endif>
                                                <div class="form-group">
                                                    <label class="control-label"
                                                           for="sous_loc_type">{{ __('property.type_location') }}
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="sous_loc_type"
                                                                class="selectpicker">
                                                            @foreach($sous_type_loc as $data)
                                                                @if(!empty($ad) && $ad->sous_type_loc == $data->id)
                                                                    <option selected
                                                                            value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                                @else
                                                                    <option
                                                                        value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label"
                                                       for="property_type">{{ __('property.property_type') }} *</label>
                                                <div class="custom-selectbx"
                                                     style="display: block; max-width: 250px !important;">
                                                    <select id="property_type" name="property_type"
                                                            class="selectpicker">
                                                        @foreach($propertyTypes as $data)
                                                            @if(isset($ad) && $ad->property_type_id == $data->id)
                                                                <option selected
                                                                        value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                                            @else
                                                                <option
                                                                    value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label"
                                                       for="link_fb_ad">{{ __("Lien vers l'annonce sur facebook") }}
                                                    *</label>
                                                <input type="text" disabled="" class="form-control"
                                                       placeholder="Lien vers le profil facebook du propriétaire"
                                                       id="link_fb_ad" name="link_fb_ad"
                                                       @if(isset($ad)) value="{{$ad->fb_ad_link}}" @endif autofocus>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label class="control-label" for="title">{{ __('property.title') }}
                                                    *</label>
                                                <input type="text" class="form-control" id="title"
                                                       placeholder="{{ __('property.title_placeholder') }}" name="title"
                                                       @if(isset($ad)) value="{{$ad->title}}" @endif autofocus>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label" for="title">Adresse *</label>
                                                <div class="autocomplete-container" id="autocomplete-container"></div>
                                                <input type="text" class="form-control" id="address"
                                                       placeholder="Entrer Adresse" name="address"
                                                       @if(isset($ad)) value="{{$ad->address}}" @endif autofocus>
                                                <input type="hidden" id="latitude" name="latitude"
                                                       @if(isset($ad)) value="{{$ad->latitude}}" @endif>
                                                <input type="hidden" id="longitude" name="longitude"
                                                       @if(isset($ad)) value="{{$ad->longitude}}" @endif>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label"
                                                       for="rent_per_month">{{ __('property.rent') }} *</label>
                                                <input type="number" min="1" class="form-control"
                                                       id="rent_per_month_standard" placeholder="&euro;"
                                                       name="rent_per_month"
                                                       @if(isset($ad)) value="{{$ad->min_rent}}" @endif>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label"
                                                       for="rent_per_month">{{ __('property.furnished') }}</label>
                                                <input type="checkbox" style="display:block" id="furnished"
                                                       name="furnished"
                                                       @if(isset($ad)) @if($ad->furnished==1) chekcked @endif @endif>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="rent_per_month">{{ __('property.metro_lines') }}</label>
                                            <div class="div-metro">
                                                <input type="text" class="form-control metro_lines" id="metro_line"
                                                       placeholder="{{ __('property.metro_lines_placeholder') }}"
                                                       name="metro_line">
                                                <button type="button" id="btn-add-metro" class="btn btn-add-metro">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="metro-data" class="metro-data">
                                            @if(!empty($ad_details->nearby_facilities) && count($ad_details->nearby_facilities) > 0)
                                                @foreach($ad_details->nearby_facilities as $key => $nearby)
                                                    @if($nearby->nearbyfacility_type == "subway_station")
                                                        <div class="metro-elem">{{$nearby->name}}<a href="javascript:"
                                                                                                    class="close-metro">x</a><input
                                                                type="hidden" name="metro_lines[]"
                                                                value="{{$nearby->name}}"/></div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">{{ __('property.date_availability') }}*&nbsp;<a
                                                            href="javascript://;" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ __('Date of availablity of the property') }}"><i
                                                                class="fa fa-question-circle fa-lg"
                                                                aria-hidden="true"></i></a></label>
                                                    <div class="datepicker-outer">
                                                        <div id="datepicker-1" class="custom-datepicker">
                                                            <input class="date_field form-control" type="text"
                                                                   id="date_of_availablity"
                                                                   @if(!isset($ad)) value="{{date('d/m/Y')}}"
                                                                   @else value="{{date('d/m/Y', strtotime($ad->available_date))}}"
                                                                   @endif name="date_of_availablity" readonly
                                                                   placeholder="dd/mm/yyyy">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                           for="prop_square_meters">{{ __('property.square_meters') }} </label>
                                                    <input id="prop_square_meters" type="text" class="form-control"
                                                           placeholder="{{ __('property.square_meters_placeholder') }}"
                                                           name="prop_square_meters"
                                                           @if(isset($ad)) value="{{$ad->min_surface_area}}" @endif>
                                                </div>
                                            </div>
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc">
                                                <div class="form-group">
                                                    <label class="control-label" for="sous_loc_type">Si vous accceptez
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="accept_as"
                                                                class="selectpicker">
                                                            <option @if(isset($ad)) @if($ad->accept_as==1) selected
                                                                    @endif @endif  value="1">Propriétaire
                                                            </option>
                                                            <option value="2">Agent</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="prop_square_meters">Email</label>
                                                    <input id="prop_square_meters" type="email" class="form-control"
                                                           placeholder="ex: exemple@gmail.com" name="email">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="prop_square_meters">Tél</label>
                                                    <input id="prop_square_meters" type="text" class="form-control"
                                                           placeholder="ex: +33123456789" name="mobile_no">
                                                </div>
                                            </div>
                                            {{-- <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="prop_square_meters">N° whatsapp</label>
                                                    <input id="prop_square_meters" type="text" class="form-control" placeholder="" name="prop_square_meters">
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc">
                                                <div class="form-group">
                                                    <label class="control-label" for="sous_loc_type">Genre préferé
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="preferred_gender"
                                                                class="selectpicker">
                                                            <option value="2">Ca ne pose pas de problème</option>
                                                            <option value="0"
                                                                    @if(isset($ad)) @if($ad->preferred_gender==0) selected @endif @endif >
                                                                Homme
                                                            </option>
                                                            <option value="1"
                                                                    @if(isset($ad)) @if($ad->preferred_gender==1) selected @endif @endif>
                                                                Femme
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc">
                                                <div class="form-group">
                                                    <label class="control-label" for="sous_loc_type">Occupation préferée
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="preferred_occupation"
                                                                class="selectpicker">
                                                            <option selected value="2">Ca ne pose pas de problème
                                                            </option>
                                                            <option value="0"
                                                                    @if(isset($ad)) @if($ad->preferred_occupation==0) selected @endif @endif>
                                                                Etudiant
                                                            </option>
                                                            <option value="1"
                                                                    @if(isset($ad)) @if($ad->preferred_occupation==1) selected @endif @endif>
                                                                Salarié
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc">
                                                <div class="form-group">
                                                    <label class="control-label" for="sous_loc_type">Chambre à coucher
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="no_of_bedrooms"
                                                                class="selectpicker">
                                                            @for ($i = 1; $i <= 10; $i++)
                                                                <option @if(isset($ad)) @if($ad->bedrooms== $i) selected
                                                                        @endif @endif  value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-10 col-sm-3 col-md-3 sous_type_loc">
                                                <div class="form-group">
                                                    <label class="control-label" for="sous_loc_type">Salle de bain
                                                        *</label>
                                                    <div class="custom-selectbx">
                                                        <select id="sous_loc_type" name="no_of_bathrooms"
                                                                class="selectpicker">
                                                            @for ($i = 1; $i <= 10; $i++)
                                                                <option
                                                                    @if(isset($ad)) @if($ad->bathrooms== $i) selected
                                                                    @endif @endif  value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label class="control-label" for="prenoms">{{ __('Prénoms') }} *</label>
                                                <input type="text" class="form-control"
                                                       placeholder="Prénoms du propriétaire de l'annonce" name="prenoms"
                                                       @if(isset($ad)) value="{{$ad->first_name}}" @endif autofocus>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label" for="nom">{{ __('Nom') }} *</label>
                                                <input type="text" class="form-control"
                                                       placeholder="Nom du propriétaire de l'annonce" name="nom"
                                                       @if(isset($ad)) value="{{$ad->last_name}}" @endif autofocus>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label"
                                                       for="link_fb">{{ __('Lien vers le profil facebook') }} *</label>
                                                <input type="text" class="form-control"
                                                       placeholder="Lien vers le profil facebook du propriétaire"
                                                       id="link_fb" name="link_fb"
                                                       @if(isset($ad)) value="{{$ad->fb_profile_link}}"
                                                       @endif autofocus>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label" for="nom">{{ __('Sexe') }} *</label>
                                                <select id="sex" name="sex" class="selectpicker">
                                                    <option value="0" @if(isset($ad) && $ad->sex == 0) selected @endif>
                                                        M
                                                    </option>
                                                    <option value="1" @if(isset($ad) && $ad->sex == 1) selected @endif>
                                                        F
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-12">
                                                <label class="control-label" for="description">Photo de profil</label>
                                                <input id="pdp" name="pdp" type="file" class="file">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="description">{{ __('property.description') }} *</label>
                                            <textarea id="description" name="description" class="form-control"
                                                      placeholder="{{ __('property.description_placeholder') }}"
                                                      rows="6">@if(isset($ad))
                                                    {{$ad->description}}
                                                @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">{{ __('property.upload_photos') }}</label>
                                            <div class="upload-photo-outer">
                                                <div class="file-loading">
                                                    <input id="file-photos" type="file" multiple class="file"
                                                           data-overwrite-initial="false" data-min-file-count="1"
                                                           name="file_photos[]" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="upload-photo-listing">
                                                <p>**{{ __('property.upload_photos_message') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-footer">
                                                <button id="btnSaveAd" @if(isset($ad)) data-id="{{$ad->id}}"
                                                        @endif type="submit"
                                                        class="btn btn-info">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWarningInfo" aria-hidden="true"
         id="modalWarningInfo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Verify ad</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-save-btn-yes">Save anyway</button>
                    <button type="button" class="btn btn-default" id="modal-save-signal-btn">Save and Signal</button>
                    <button type="button" class="btn btn-default" id="modal-cancel-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWarningInfo" aria-hidden="true"
         id="modalAdExist">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ad exist </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody">please verify, this ad already exists</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-ok-btn-exist">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWarningInfo" aria-hidden="true"
         id="profilLinkError">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lien profil facebook manquant </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody">Vérifier le lien vers le profil facebook. Au
                        cas où le terme "profile.php" est présent dans le lien, l'id(?id=1231321546546) ne doit pas être
                        éffacé. </br>
                        Donc, le lien doit être comme l'exemple suivant : </br>
                        https://www.facebook.com/profile.php?id=1517726641</br>
                        Veuillez aussi bien tester avant de valider que le lien que vous saisissez redirige vraiement
                        vers le profil du propriétaire de l'annonce
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-ok-btn-profil">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWarningInfo" aria-hidden="true"
         id="modalInterdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lien facebook interdit </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody">
                        Le lien que vous avez saisit contient l'un des termes interdits suivants : </br>
                        (sales, comment, web)</br>
                        Veuillez corriger s'il vous plaît (web => www)
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close-modal" data-id="modalInterdit"
                            id="modal-ok-btn-modalInterdit">Ok
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWarningInfo" aria-hidden="true"
         id="modalMobile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lien facebook Mobile détecté</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody">Vous devez utiliser un pc pour la création
                        d'annonce
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-ok-btn-mobile">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalListAds" aria-hidden="true"
         id="modalListAds">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Please verify </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="ModalWarningBody">please verify from the list below that your ad
                        is not one of them
                    </div>
                    <div class="box box-primary">

                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover" id="tableDoute">
                                <tr>
                                    <th>Ad title</th>
                                    <th>Owner</th>
                                    <th>Description</th>
                                    <th>Adresse</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-cancel-btn-exist">cancel</button>
                    <button type="button" class="btn btn-default" id="save-anyway-btn-profil-exist">Save Anyway</button>
                </div>
            </div>
        </div>
    </div>
    @include('admin.ads.contact_annonceur')

    <style type="text/css">
        .modal-lg {
            width: 80%;
            margin: auto;
        }
    </style>

    @stack('scripts')
    <script>
        var messagess = {
            "browse": "{{__('profile.browse')}}",
            "cancel": "{{__('profile.cancel')}}",
            "remove": "{{__('profile.remove')}}",
            "upload": "{{__('profile.upload')}}"
        }
    </script>
    <script src="/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script>
    <script type="text/javascript">

        var key_to_use = null
        var page_name = 'admin_add_ads'

        function getKey(page) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.get(`/geoapify_key/${page}`)
                .then((response) => {
                    key_to_use = response
                })
                .catch((error) => {
                    //ajout de clé par defaut pour que l'api reste fonctionne
                    key_to_use = 'cc765b1a7ea34e3e88b8434632fdd785'
                    //send error ici
                })

        }

        /*
            The addressAutocomplete takes as parameters:
          - a container element (div)
          - callback to notify about address selection
          - geocoder options:
               - placeholder - placeholder text for an input element
             - type - location type
        */
        function addressAutocomplete(containerElement, callback, options) {
            getKey(page_name)
            // create input element
            var inputElement = document.getElementById("address");
//   inputElement.setAttribute("type", "text");
//   inputElement.setAttribute("placeholder", options.placeholder);
            containerElement.appendChild(inputElement);

            // add input field clear button
            var clearButton = document.createElement("div");
            clearButton.classList.add("clear-button");
            addIcon(clearButton);
            clearButton.addEventListener("click", (e) => {
                e.stopPropagation();
                inputElement.value = '';
                callback(null);
                clearButton.classList.remove("visible");
                closeDropDownList();
            });
            containerElement.appendChild(clearButton);

            /* Current autocomplete items data (GeoJSON.Feature) */
            var currentItems;

            /* Active request promise reject function. To be able to cancel the promise when a new request comes */
            var currentPromiseReject;

            /* Focused item in the autocomplete list. This variable is used to navigate with buttons */
            var focusedItemIndex;

            /* Execute a function when someone writes in the text field: */
            inputElement.addEventListener("input", function (e) {
                var currentValue = this.value;

                /* Close any already open dropdown list */
                closeDropDownList();

                // Cancel previous request promise
                if (currentPromiseReject) {
                    currentPromiseReject({
                        canceled: true
                    });
                }

                if (!currentValue) {
                    clearButton.classList.remove("visible");
                    return false;
                }

                // Show clearButton when there is a text
                clearButton.classList.add("visible");

                /* Create a new promise and send geocoding request */
                var promise = new Promise((resolve, reject) => {
                    currentPromiseReject = reject;

                    var apiKey = key_to_use;


                    var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&limit=1&apiKey=${apiKey}`;

                    if (options.type) {
                        url += `&type=${options.type}`;
                    }

                    fetch(url)
                        .then(response => {
                            // check if the call was successful
                            $.get(`/update_gestion_geoapify/${page_name}`,`url=${window.location.href}`)
                            if (response.ok) {
                                response.json().then(data => resolve(data));
                            } else {
                                // window.location.href = "/geopify_error_community_ads";
                                response.json().then(data => reject(data));
                            }
                        });
                });

                promise.then((data) => {
                    currentItems = data.features;

                    /*create a DIV element that will contain the items (values):*/
                    var autocompleteItemsElement = document.createElement("div");
                    autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                    containerElement.appendChild(autocompleteItemsElement);

                    /* For each item in the results */
                    data.features.forEach((feature, index) => {
                        /* Create a DIV element for each element: */
                        var itemElement = document.createElement("DIV");
                        /* Set formatted address as item value */
                        itemElement.innerHTML = feature.properties.formatted;

                        /* Set the value for the autocomplete text field and notify: */
                        itemElement.addEventListener("click", function (e) {
                            inputElement.value = currentItems[index].properties.formatted;

                            callback(currentItems[index]);

                            /* Close the list of autocompleted values: */
                            closeDropDownList();
                        });

                        autocompleteItemsElement.appendChild(itemElement);
                    });
                }, (err) => {
                    if (!err.canceled) {
                        console.log(err);
                    }
                });
            });

            /* Add support for keyboard navigation */
            inputElement.addEventListener("keydown", function (e) {
                var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    var itemElements = autocompleteItemsElement.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        e.preventDefault();
                        /*If the arrow DOWN key is pressed, increase the focusedItemIndex variable:*/
                        focusedItemIndex = focusedItemIndex !== itemElements.length - 1 ? focusedItemIndex + 1 : 0;
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 38) {
                        e.preventDefault();

                        /*If the arrow UP key is pressed, decrease the focusedItemIndex variable:*/
                        focusedItemIndex = focusedItemIndex !== 0 ? focusedItemIndex - 1 : focusedItemIndex = (itemElements.length - 1);
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 13) {
                        /* If the ENTER key is pressed and value as selected, close the list*/
                        e.preventDefault();
                        if (focusedItemIndex > -1) {
                            closeDropDownList();
                        }
                    }
                } else {
                    if (e.keyCode == 40) {
                        /* Open dropdown list again */
                        var event = document.createEvent('Event');
                        event.initEvent('input', true, true);
                        inputElement.dispatchEvent(event);
                    }
                }
            });

            function setActive(items, index) {
                if (!items || !items.length) return false;

                for (var i = 0; i < items.length; i++) {
                    items[i].classList.remove("autocomplete-active");
                }

                /* Add class "autocomplete-active" to the active element*/
                items[index].classList.add("autocomplete-active");

                // Change input value and notify
                inputElement.value = currentItems[index].properties.formatted;
                callback(currentItems[index]);
            }

            function closeDropDownList() {
                var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    containerElement.removeChild(autocompleteItemsElement);
                }

                focusedItemIndex = -1;
            }

            function addIcon(buttonElement) {
                var svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
                svgElement.setAttribute('viewBox', "0 0 24 24");
                svgElement.setAttribute('height', "24");

                var iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
                iconElement.setAttribute("d", "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z");
                iconElement.setAttribute('fill', 'currentColor');
                svgElement.appendChild(iconElement);
                buttonElement.appendChild(svgElement);
            }

            /* Close the autocomplete dropdown when the document is clicked.
              Skip, when a user clicks on the input field */
            document.addEventListener("click", function (e) {
                if (e.target !== inputElement) {
                    closeDropDownList();
                } else if (!containerElement.querySelector(".autocomplete-items")) {
                    // open dropdown list again
                    var event = document.createEvent('Event');
                    event.initEvent('input', true, true);
                    inputElement.dispatchEvent(event);
                }
            });

        }

        addressAutocomplete(document.getElementById("autocomplete-container"), (data) => {


            initInputAutoComplete("address", data);

//   console.log("Selected option: ");
//   console.log(data.properties.lat);
        }, {
            placeholder: "Enter an address here"
        });


        function initInputAutoComplete(id, data) {
            // console.log(data);
            $('#latitude').val("");
            $('#longitude').val("");

            $('#latitude').val(data.properties.lat);
            $('#longitude').val(data.properties.lon);
            // const val = document.getElementById('latitude').value;
//   console.log(val);
            //$address.textContent = e.suggestion.value


        }


        $("#file-photos").fileinput({
            theme: 'fa',
            showUpload: false,
            uploadAsync: false,
            showRemove: false,
            showCancel: false,
            showDrag: false,
            browseOnZoneClick: true,
            dropZoneClickTitle: "Or click here to select photos...",
            dropZoneTitle: "<div class='browse_file_text'>Drag and Drop Photos Here</div>",
            uploadUrl: 'comunity-uploadfiles', // you must set a valid URL here else you will get an error
            uploadExtraData: {'media_type': 0},
            ajaxSettings: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            ajaxDeleteSettings: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 5000,
            maxFileCount: 8,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
        }).on("filebatchselected", function (event, files) {
            $("#file-photos").fileinput("upload");
        });
        $(document).ready(function () {

            /* event listener */
          //  document.getElementsByName("link_fb")[0].addEventListener('change', input_change);

            /* function */
            // function doThing(){
            //     alert('Horray! Someone wrote "' + this.value + '"!');
            // }


            document.getElementById("link_fb").oninput = function () {
                input_change()
            };
            // setInterval(function(){
            //     //code goes here that will be run every 5 seconds.
            //     input_change()
            // }, 5000);

            // document.getElementById("address").oninput = function() {input_change()};


            function input_change() {
                fbAdCheckByProfil(function (exist) {
                    if (exist) {
                        $('#modalListAds').modal("show");
                    }
                });
            }

            $(".date_field").datepicker({
                format: "dd/mm/yyyy",
                minDate: "-0d"
            });

            $('#modal-cancel-btn-exist').on('click', function () {
                $('#modalListAds').modal('hide');
            });
            $('#save-anyway-btn-profil-exist').on('click', function () {
                $('#modalListAds').modal('hide');
                $('#createAdForm').submit();
            });
            jQuery.extend(jQuery.validator.messages, {
                required: "{{__('validator.required')}}",
                email: "{{__('validator.email')}}",
                number: "Ce champ doit être un nombre"
            });
            jQuery("#createAdForm").validate({
                rules: {
                    "title": {
                        "required": true,
                    },
                    "address": {
                        "required": true
                    },
                    "nom": {
                        "required": true
                    },
                    "description": {
                        "required": true
                    }/*,
                 "link_fb_ad": {
                    "required": true
                 }*/,
                    "link_fb": {
                        "required": true
                    }
                },
                messages: {
                    "title": "Champ obligatoire",
                    "address": "Champ obligatoire",
                    "nom": "Champ obligatoire",
                    "link_fb": "Champ obligatoire",
                    /*"link_fb_ad" : "Champ obligatoire",*/
                    "description": "Champ obligatoire"
                }
            });

            $('#modal-ok-btn-mobile').on('click', function () {
                $('#modalMobile').modal('hide');
            });
            $("#btnSaveAd").on("click", function (e) {
                var ad_id = $(this).attr('data-id');
                $('#modal-send-message-annonceurs').attr("data-id", ad_id);
                $('#modal-send-message-annonceurs').attr("data-type", "update");
                e.preventDefault();
                if (checkNotAllowedInfo($("#link_fb").val())) {
                    $("#modalInterdit").modal("show");
                    return;
                }
                /*if(checkNotAllowedInfo($("#link_fb_ad").val())) {
                    $("#modalInterdit").modal("show");
                    return;
                }*/

                if (isProfileLinkManquant($("#link_fb").val())) {
                    $("#profilLinkError").modal("show");
                    return;
                }
                if ($("#createAdForm").valid()) {

                    if (isContainMobile($("#link_fb").val()) || isContainMobile($("#link_fb_ad").val())) {
                        $("#modalMobile").modal("show");
                        return;
                    }

                    if (isWarning($("#title").val())) {
                        $('#type_signal').val('title');
                        $('#ModalWarningBody').text("Please verify the title of this ad, it contains some not allowed infos (phone number, email, website, facebook link..)");
                        $('#modalWarningInfo').modal('show');
                        return;
                    }
                    if (isWarning($("#description").val())) {
                        $('#type_signal').val('description');
                        $('#modalWarningInfo').modal('show');
                        $('#ModalWarningBody').text("Please verify the description of this ad, it contains some not allowed infos (phone number, email, website, facebook link..)");
                        return;
                    }

                    fbAdCheckByProfil(function (exist) {
                        if (exist) {
                            $('#modalListAds').modal("show");
                        } else {
                            if (ad_id != null) {
                                $('#send-message-modal').modal('show');
                            } else {
                                $("#createAdForm").submit();
                            }
                        }
                    });

                }
            });

            $('.close-modal').on('click', function () {
                var dataId = $(this).attr("data-id");
                $("#" + dataId).modal('hide');
            });

            $('#address').on('blur', function () {
                fbAdCheckByProfil(function (exist) {
                    if (exist) {
                        $('#modalListAds').modal("show");
                    }
                });
            });

            $('#link_fb').on('blur', function () {
                fbAdCheckByProfil(function (exist) {
                    if (exist) {
                        $('#modalListAds').modal("show");
                    }
                });
            });

            $('#scenario_id').on('change', function () {

                if ($(this).val() == 1 || $(this).val() == 3) {
                    $('.sous_type_loc').show();
                } else {
                    $('.sous_type_loc').hide();
                }
            });

            $('#link_fb').on('change', function () {

                if (checkNotAllowedInfo($("#link_fb").val())) {
                    $("#modalInterdit").modal("show");
                    return;
                }

                if (isProfileLinkManquant($("#link_fb").val())) {
                    $("#profilLinkError").modal("show");
                    return;
                }

                if (isContainMobile($("#link_fb").val())) {
                    $("#modalMobile").modal("show");
                    return;
                }
                fbAdCheckByProfil(function (exist) {
                    if (exist) {
                        $('#modalListAds').modal("show");
                    }
                });
            });

            $("#modal-ok-btn-profil").on('click', function () {
                $("#profilLinkError").modal("hide");
            });


            $("#modal-save-btn-yes").on("click", function (e) {
                $("#createAdForm").submit();
            });

            $("#modal-save-signal-btn").on("click", function (e) {
                $("#signalAd").val('1');
                $("#createAdForm").submit();
            });

            $("#modal-cancel-btn").on("click", function (e) {
                $('#modalWarningInfo').modal('hide');
            });

            $("#modal-ok-btn-exist").on("click", function (e) {
                $('#modalAdExist').modal('hide');
            });


            $('#link_fb_ad').on("change", function () {
                if (checkNotAllowedInfo($("#link_fb_ad").val())) {
                    $("#modalInterdit").modal("show");
                    return;
                }

                if (isContainMobile($("#link_fb_ad").val())) {
                    $("#modalMobile").modal("show");
                    return;
                }
                fbAdCheck();
            });
        });

        function isWarning(str) {
            if (ifContainPhoneNumber(str) || ifContainEmail(str) || ifContainWebsite(str)) {
                return true;
            }
            return false;
        }

        function isContainMobile(str) {
            if (str.toUpperCase().indexOf("m.facebook".toUpperCase()) != -1) {
                return true;
            }
            if (str.toUpperCase().indexOf("mobile.facebook".toUpperCase()) != -1) {
                return true;
            }
            return false;
        }

        function ifContainPhoneNumber(str) {
            //enlever tous les points
            str = str.replaceAll(".", "");
            //enlever tous les espace
            str = str.replaceAll(" ", "");

            var res = str.match(/(?:(?:\+?([1-9]|[0-9][0-9]|[0-9][0-9][0-9])\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([0-9][1-9]|[0-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/g);
            if (res != null) {
                if (res.length > 0) {
                    return true;
                }
            }
            return false;

        }

        function ifContainEmail(str) {
            var res = str.match(/(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/g);
            if (res != null) {
                if (res.length > 0) {
                    return true;
                }
            }
            return false;

        }

        function getWebsiteFromString(str) {
            var res = str.match(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi);
            return res;
        }

        function ifContainWebsite(str) {
            var res = getWebsiteFromString(str);
            if (res != null) {
                if (res.length > 0) {
                    return true;
                }
            }
            return false;
        }

        String.prototype.replaceAll = function (search, replacement) {
            var target = this;
            return target.split(search).join(replacement);
        };

        function fbAdCheck() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: 'check_ad_fb_link',
                type: 'post',
                dataType: 'json',
                data: {"ad_fb_link": $('#link_fb_ad').val()}
            }).done(function (result) {
                if (result.error == "yes") {
                    $('#modalAdExist').modal('show');
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {

            });
        }

        function checkNotAllowedInfo(str) {
            var infos = ["sales", "comment", "web."];
            for (var i = 0; i < infos.length; i++) {
                if (str.toUpperCase().indexOf(infos[i].toUpperCase()) != -1) {
                    return true;
                }
            }
            return false;
        }

        function fbAdCheckByProfil(callback) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: 'check_ad_profil_info',
                type: 'post',
                dataType: 'json',
                data: $('#createAdForm').serialize()
            }).done(function (result) {
                if (result.length > 0) {
                    $('#tableDoute').html("<tr><th>Ad title</th><th>Owner</th><th>Description</th><th>Adresse</th></tr>");
                    result.forEach(function (element) {
                        $('#tableDoute').append("<tr><td>" + element.title + "</td><td>" + element.first_name + " " + element.last_name + "</td><td>" + element.description + "</td><td>" + element.address + "</td></tr>")
                    });
                    callback(true);
                } else {
                    callback(false);
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {

            });
        }

        function isProfileLinkManquant(str) {
            str = str.toUpperCase();
            if (str.indexOf("profile.php".toUpperCase()) != -1 && str.indexOf("profile.php?id".toUpperCase()) == -1) {
                return true;
            }
            return false;
        }

        // function initInputAutoComplete(id)
        // {
        //     var placesAutocomplete = places({
        //         appId: 'pl8GAWX9U3QF',
        //         apiKey: 'e80df4d22a0beeeef730bb02a6602b51',
        //         container: document.querySelector('#' + id)
        //       });
        //       placesAutocomplete.on('change', function(e) {
        //         $('#latitude').val(e.suggestion.latlng.lat);
        //         $('#longitude').val(e.suggestion.latlng.lng);
        //         //$address.textContent = e.suggestion.value
        //       });

        //       placesAutocomplete.on('clear', function() {
        //         $('#latitude').val("");
        //         $('#longitude').val("");
        //       });
        // }


    </script>
    <style>


        .autocomplete-container {
            /*the container must be positioned relative:*/
            position: relative;

            margin-bottom: 20px;
        }

        .autocomplete-container input {
            width: calc(100% - 43px);
            outline: none;

            border: 1px solid rgba(0, 0, 0, 0.2);
            padding: 10px;
            padding-right: 31px;
            font-size: 16px;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0px 2px 10px 2px rgba(0, 0, 0, 0.1);
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: calc(100% + 2px);
            left: 0;
            right: 0;

            background-color: #fff;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: rgba(0, 0, 0, 0.1);
        }

        .autocomplete-items .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: rgba(0, 0, 0, 0.1);
        }

        .clear-button {
            color: rgba(0, 0, 0, 0.4);
            cursor: pointer;

            position: absolute;
            right: 52px;
            top: 0;

            height: 100%;
            display: none;
            align-items: center;
        }

        .clear-button.visible {
            display: flex;
        }


        .clear-button:hover {
            color: rgba(0, 0, 0, 0.6);
        }

        .error {
            color: red;
        }
    </style>

@endsection
