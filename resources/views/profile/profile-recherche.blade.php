@if (isAfterInscription())
    @push('styles')
        {{-- <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet"> --}}
        {{-- <link rel="stylesheet" href="/css/login.css"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649074295/css/custom_seek_wwpyoq.css"
            rel="stylesheet">
        <link rel="stylesheet" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649073142/css/login_pi6vkx.css">
    @endpush
    <script>
        var messages = {
            "phone_error": "{{ __('validator.phone_error') }}",
            "error_form": "{{ __('profile.error_form') }}",
            "error_contact": "{{ __('backend_messages.error_contact') }}"
        }
        var appSettings = {};
        var messagess = {
            "browse": "{{ __('profile.browse') }}",
            "cancel": "{{ __('profile.cancel') }}",
            "remove": "{{ __('profile.remove') }}",
            "upload": "{{ __('profile.upload') }}"
        }
    </script>

    <!-- Push a script dynamically from a view -->
    @push('scripts')
        {{-- <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649077863/js/jquery-ui.min_einwp7.js"></script>


        <script type="text/javascript">
            $(document).ready(function() {
                /*code here*/
                $('.sumo-select').SumoSelect();

                if ($("#formRecherche").length >= 0) {
                    $("#formRecherche").validate({

                        rules: {
                            smoker: {
                                required: true,
                            },
                            alcool: {
                                required: true,
                            },
                            gay: {
                                required: true,
                            }
                        },
                        messages: {
                            smoker: {
                                required: "{{ __('validator.required') }}",
                            },
                            alcool: {
                                required: "{{ __('validator.required') }}",
                            },
                            gay: {
                                required: "{{ __('validator.required') }}",
                            }

                        }
                    })
                }
            })
        </script>
    @endpush

    <div class="modal fade project-popup-1 project-login-form-outer" data-backdrop="static" id="profil-popup"
        role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content project-form">
                <div class="modal-body">
                    <div class="loader-icon" style="/* display:none; */"><img src="/images/rolling-loader.apng"></div>
                    <div class="tab-content">
                        @if ($message = Session::get('status'))
                            <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"
                                    title="{{ __('close') }}">×</a>
                                {{ $message }}
                            </div>
                        @endif

                        <form id="formRecherche" method="POST" action="{{ route('saveProfilRecherche') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="contact-continue" name="contact_continue" value="0">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <h2 class="h1-profil h1-title">{{ __('profile.derniere_etape') }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <h2 class="h1-profil">{{ __('profile.demarquez_vous') }}</h2>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    @if ($message = Session::get('error'))
                                        <div class="alert alert-danger fade in alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                            {{ $message }}
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        @if (!isRegisterFb())
                                            <label class="control-label">{{ __('profile.upload_photo') }}</label>
                                            <div class="upload-file-outer">
                                                <div class="file-loading">
                                                    <input id="file_profile_photos" name="file_profile_photos"
                                                        type="file" class="file">
                                                </div>
                                                <script>
                                                    $("#file_profile_photos").fileinput({
                                                        language: $("#changeLang").val(),
                                                        theme: 'fa',
                                                        showUpload: false,
                                                        uploadAsync: false,
                                                        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                                                        maxFileSize: 5120,
                                                        overwriteInitial: true,
                                                        maxFilesNum: 1,
                                                        slugCallback: function(filename) {
                                                            return filename.replace('(', '_').replace(']', '_');
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <div class="upload-photo-listing">
                                                <p>{{ __('profile.upload_photo_message') }}</p>
                                            </div>
                                        @endif
                                        <div class="div-description-recherche">
                                            <textarea id="description" name="description" class="form-control"
                                                placeholder="{{ __('profile.decrivez_annonce') }}"
                                                rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') == 1)
                                    <div class="upload_guarantees col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"
                                                for="guarantee_type_1">{{ __('profile.type_garantie') }}
                                                <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select id="guarantee_type_1" sumo-required="true"
                                                    placeholder="{{ __('filters.no_selected') }}"
                                                    name="guarantee_type[]" class="sumo-select" multiple="">
                                                    @foreach ($guarAsked as $data)
                                                        <option value="{{ $data->id }}">
                                                            {{ traduct_info_bdd($data->guarantee) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label id="guarantee_type_1-custom_error" class="custom-error"
                                                for="social_interests" style="">{{ __('validator.required') }}</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if (!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') != 1)
                                @if (!isRegisterFb())
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">{{ __('profile.interest') }}
                                                    <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="sumo-select" sumo-required="true"
                                                        placeholder="{{ __('filters.no_selected') }}"
                                                        name="social_interests[]" id="social_interests" multiple="">
                                                        @foreach ($socialInterests as $data)
                                                            <option value="{{ $data->id }}">
                                                                {{ traduct_info_bdd($data->interest_name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label id="social_interests-custom_error" class="custom-error"
                                                    for="social_interests"
                                                    style="">{{ __('validator.required') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="sport-select col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">{{ i18n('user_sport') }}
                                                    <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="sport-sumo-select sumo-select" sumo-required="false"
                                                        placeholder="{{ __('filters.no_selected') }}" name="sports[]"
                                                        id="sports" multiple="">
                                                        @foreach ($sports as $data)
                                                            <option value="{{ $data->id }}">{{ $data->label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label id="sports-custom_error" class="custom-error"
                                                    for="social_interests"
                                                    style="">{{ __('validator.required') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.smoker') }} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" title="{{ __('filters.no_selected') }}"
                                                    name="smoker" id="smoker">
                                                    <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '0') selected @endif value="0">
                                                        {{ __('profile.yes') }}</option>
                                                    <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '1') selected @endif value="1">
                                                        {{ __('profile.no') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.alcool') }} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" title="{{ __('filters.no_selected') }}"
                                                    name="alcool" id="alcool">
                                                    <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '0') selected @endif value="0">
                                                        {{ __('profile.yes') }}</option>
                                                    <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '1') selected @endif value="1">
                                                        {{ __('profile.no') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('profile.gay') }} <sup>*</sup></label>
                                    <div class="custom-selectbx">
                                        <select class="selectpicker" title="{{ __('filters.no_selected') }}" name="gay" id="gay">
                                            <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '0') selected @endif value="0">{{ __('profile.yes') }}</option>
                                            <option @if (!empty($user) && count($user->user_profiles) > 0 && $user->user_profiles->smoker == '1') selected @endif value="1">{{ __('profile.no') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.gay') }}</label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" title="{{ __('filters.no_selected') }}"
                                                    name="gay" id="gay">
                                                    <option value="0">{{ __('profile.yes') }}</option>
                                                    <option value="1">{{ __('profile.no') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn_ok" href="javascript:void(0);"
                                id="saveRecherche">{{ __('profile.go') }}</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        history.replaceState ?
            history.replaceState(null, null, window.location.href.split("#")[0]) :
            window.location.hash = "";
    </script>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $('#profil-popup').modal("show");

                $('#social_interests').on('change', function() {
                    var data = $(this).val();
                    if (data.indexOf("4") != -1) {
                        $(".sport-select").show();
                        $('.sport-sumo-select').attr('sumo-required', "true");
                    } else {
                        $(".sport-select").hide();
                        $('.sport-sumo-select').attr('sumo-required', "false");
                    }
                });
                $('.sumo-select').on('change', function() {
                    if ($(this).attr('sumo-required') == "true") {
                        var id = $(this).attr("id");
                        if ($(this).val().length == 0) {
                            $('#' + id + '-custom_error').show();
                        } else {
                            $('#' + id + '-custom_error').hide();
                        }
                    }
                });
                $('.sumo-select').SumoSelect();
                $(".row").on('click', function(event) {});
                $("#formRecherche").change(function() {
                    $("#saveRecherche").removeAttr("disabled");

                });
                $('#saveRecherche').on('click', function(e) {
                    $("#saveRecherche").attr("disabled", "disabled");

                    e.preventDefault();
                    sumoReq = true;
                    var sumos = ["sports", "social_interests"];
                    $('.sumo-select').each(function() {
                        if ($(this).attr('sumo-required') == "true") {
                            var id = $(this).attr("id");
                            if (sumos.indexOf(id) != -1) {
                                if ($(this).val().length == 0) {
                                    sumoReq = false;
                                    $('#' + id + '-custom_error').show();
                                } else {
                                    $('#' + id + '-custom_error').hide();
                                }
                            }
                        }
                    });
                    if (!$("#formRecherche").valid() || !sumoReq) {
                        $("#saveRecherche").removeAttr("disabled");
                        return;
                    }
                    $(".loader-icon").show();

                    $.ajax({
                        type: "POST",
                        url: '/check_coordonne_profil',
                        data: $('#formRecherche').serialize(),
                        dataType: 'json',
                        success: function(data) {
                            $(".loader-icon").hide();
                            if (data.error == "contact_error") {
                                $('#information-modal').modal('show');
                                $('#modal-information-text').text(data.message);
                                $("#description").closest(".form-group").addClass('has-error');
                                return;
                            } else {
                                $('#formRecherche').submit();
                            }
                        },
                        error: function(data) {

                        }
                    });

                });
            });
        </script>
        <style type="text/css">
            h2.h1-profil {
                color: black !important;
            }

            .sport-select {
                display: none;
            }

            .upload-photo-listing p {
                color: white;
            }

            .error {
                color: red !important;
            }

            .custom-error {
                color: red !important;
                display: none;
            }

            .h1-title {
                font-size: 34px !important;
            }

        </style>
    @endpush
@endif
