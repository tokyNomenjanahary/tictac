@extends('espace_locataire.index')

@section('locataire-contenue')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @push('styles')
    <style>
        .padding-full-calendar{
            margin: 1.625rem;
        }
        input[disabled] , select[disabled], textarea[disabled]{
        /* Exemples de styles personnalisés (remplacez-les par les styles souhaités) */
        background-color: white !important;
        /* color: #999; */
        cursor: not-allowed;
        border: 1px solid #ccc;
        /* Ajoutez d'autres propriétés CSS selon vos besoins */
        }
        .navbar-expand-xl .navbar-nav .dropdown-menu {
            position: absolute;
            right: 0;
            z-index: 9999;
        }

    </style>
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/fullcalendar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/flatpickr.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/app-calendar.css')}}">

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/calendrier/style.css')}}">
    @endpush
        <div class="content-body">
            <!-- Full calendar start -->
            @if($locations == 'vide')
                <div class="alert m-t-15 m-b-0 m-l-10 m-r-10 p-2" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                    {{-- <span class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span> --}}
                    <span style="font-size:25px ; color:rgb(76,141,203)">Information</span>
                    </p style="margin-top:50px;font-size:12px !important;"> Vous n'êtes pas encore un locataire d'une location.</p>
                </div>
            @else
                <section>
                    <div class="app-calendar overflow-hidden border" style="z-index: 0 !important;">
                        <div class="row g-0">
                            <!-- Sidebar -->
                            <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                                <div class="sidebar-wrapper">
                                    <div class="card-body d-flex justify-content-center">
                                        <button class="btn btn-primary btn-toggle-sidebar w-100" data-bs-toggle="modal" data-bs-target="#add-new-sidebar">
                                            <span class="align-middle">{{__('location.ajoutRendezvous')}}</span>
                                        </button>
                                    </div>
                                    <div class="card-body pb-0">
                                        <h5 class="section-label mb-1">
                                            <span class="align-middle">Filter</span>
                                        </h5>
                                        <div class="form-check mb-1">
                                            <input type="checkbox" class="form-check-input select-all" id="select-all" checked />
                                            <label class="form-check-label" for="select-all">{{__('location.voirTout')}}</label>
                                        </div>
                                        <div class="calendar-events-filter">
                                            <div class="form-check form-check-danger mb-1">
                                                <input type="checkbox" class="form-check-input input-filter" id="business" data-value="personal" checked />
                                                <label class="form-check-label" for="business">{{__('location.remisCle')}}</label>
                                            </div>
                                            <div class="form-check form-check-primary mb-1">
                                                <input type="checkbox" class="form-check-input input-filter" id="personal" data-value="business" checked />
                                                <label class="form-check-label" for="personal">{{__('location.etatSortie')}}</label>
                                            </div>
                                            <div class="form-check form-check-warning mb-1">
                                                <input type="checkbox" class="form-check-input input-filter" id="family" data-value="family" checked />
                                                <label class="form-check-label" for="family">{{__('location.signature')}}</label>
                                            </div>
                                            {{-- <div class="form-check form-check-success mb-1">
                                                <input type="checkbox" class="form-check-input input-filter" id="holiday" data-value="holiday" checked />
                                                <label class="form-check-label" for="holiday">Holiday</label>
                                            </div> --}}
                                            <div class="form-check form-check-info">
                                                <input type="checkbox" class="form-check-input input-filter" id="etc" data-value="etc" checked />
                                                <label class="form-check-label" for="etc">ETC</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <img src="{{asset('assets/calendrier/calendar-illustration.png')}}" alt="Calendar illustration" class="img-fluid" />
                                </div>
                            </div>
                            <!-- /Sidebar -->

                            <!-- Calendar -->
                            <div class="col position-relative">
                                <div class="card shadow-none border-0 mb-0 rounded-0">
                                    <div class="card-body pb-0">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Calendar -->
                            <div class="body-content-overlay"></div>
                        </div>
                    </div>
                    <!-- Calendar Add/Update/Delete event modal-->
                    <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <div class="modal-header mb-1 justify-content-beetwen">
                                    <h5 class="modal-title">{{__('location.ajoutRendezvous')}}</h5>
                                    <button type="button" class="" style="width: 40px" data-bs-dismiss="modal" aria-label="Close">×</button>
                                </div>
                                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                    <form class="event-form needs-validation" data-ajax="false" novalidate>
                                        <div class="mb-1">
                                            <label for="title" class="form-label">{{__('location.titre')}}</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Event Title" required />
                                        </div>
                                        <div class="mb-1">
                                            <label for="select-label" class="form-label">Label</label>
                                            <select class="select2 select-label form-select w-100" id="select-label" name="select-label">
                                                <option data-label="primary" value="Business" selected>{{__('location.remisCle')}}</option>
                                                <option data-label="danger" value="Personal">{{__('location.etatSortie')}}</option>
                                                <option data-label="warning" value="Family">{{__('location.signature')}}</option>
                                                {{-- <option data-label="success" value="Holiday">Holiday</option> --}}
                                                <option data-label="info" value="ETC">ETC</option>
                                            </select>
                                        </div>
                                        <div class="mb-1 position-relative">
                                            <label for="start-date" class="form-label">{{__('location.debut')}}</label>
                                            <input type="text" class="form-control" id="start-date" name="start-date" placeholder="Start Date" />
                                        </div>
                                        <div class="mb-1 position-relative">
                                            <label for="end-date" class="form-label">{{__('location.fin')}}</label>
                                            <input type="text" class="form-control" id="end-date" name="end-date" placeholder="End Date" />
                                        </div>
                                        {{-- <div class="mb-1">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input allDay-switch" id="customSwitch3" />
                                                <label class="form-check-label" for="customSwitch3">All Day</label>
                                            </div>
                                        </div> --}}
                                        <div class="mb-1">
                                            <label for="event-url" class="form-label">{{__('location.lieuRendez')}}</label>
                                            <input type="text" class="form-control" id="event-url" />
                                        </div>
                                        {{-- <div class="mb-1 select2-primary">
                                            <label for="event-guests" class="form-label">Add Guests</label>
                                            <select class="select2 select-add-guests form-select w-100" id="event-guests" multiple>
                                                <option data-avatar="1-small.png" value="Jane Foster">Jane Foster</option>
                                                <option data-avatar="3-small.png" value="Donna Frank">Donna Frank</option>
                                                <option data-avatar="5-small.png" value="Gabrielle Robertson">Gabrielle Robertson</option>
                                                <option data-avatar="7-small.png" value="Lori Spears">Lori Spears</option>
                                                <option data-avatar="9-small.png" value="Sandy Vega">Sandy Vega</option>
                                                <option data-avatar="11-small.png" value="Cheryl May">Cheryl May</option>
                                            </select>
                                        </div> --}}
                                        @if($locations == 'vide')
                                            <p>location vide</p>
                                        @else
                                        <div class="mb-1">
                                            <label for="event-location" class="form-label">{{__('location.location')}}</label>
                                            {{-- <input type="text" class="form-control" id="event-location" placeholder="Enter Location" /> --}}
                                            <select name="event-location" id="event-location" class="form-control">
                                                <option value="">{{__('location.choixLocation')}}</option>
                                                @foreach ($locations as $location)
                                                    <option value="{{$location->id}}">{{$location->identifiant}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        @endif
                                        <div class="mb-1">
                                            <label class="form-label">Description</label>
                                            <textarea name="event-description-editor" id="event-description-editor" class="form-control"></textarea>
                                        </div>
                                        <div class="mb-1 d-flex">
                                            <button type="submit" class="btn btn-primary add-event-btn me-1">Add</button>
                                            <button type="button" class="btn btn-outline-secondary btn-cancel" data-bs-dismiss="modal">{{__('location.Annuler')}}</button>
                                            <button type="submit" class="btn btn-primary update-event-btn d-none me-1">{{__('location.modifier')}}</button>
                                            <button type="submit" class="btn btn-outline-danger btn-delete-event d-none">{{__('location.supprimer')}}</button>
                                            <button type="submit" class="btn btn-primary btn-accept-event d-none me-1">{{__('application.accept')}}</button>
                                            <button type="submit" class="btn btn-outline-danger btn-refus-event d-none ">{{__('application.decline')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Calendar Add/Update/Delete event modal-->
                </section>
            @endif
            <!-- Full calendar end -->

        </div>
        <div id="events-data" data-events="{{ $eventsJson }}"></div>
        <input type="hidden" id="langue" value="{{__('location.anglais')}}">

    @push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- BEGIN: Vendor JS-->
        <script src="{{asset('assets/calendrier/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('assets/calendrier/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/calendrier/moment.min.js')}}"></script>
    <script src="{{asset('assets/calendrier/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/calendrier/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/calendrier/flatpickr.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('assets/calendrier/app-menu.js')}}"></script>
    <script src="{{asset('assets/calendrier/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('assets/calendrier/app-calendar-events.js')}}"></script>
    <script src="{{asset('assets/calendrier/app-calendar.js')}}"></script>
    <!-- END: Page JS-->
        <script>
            $('.drop-btn').on('click', function (e) {
                e.stopPropagation()
                $(this).children().last().toggleClass('show')
            })
            $('#body-content').on('click', function () {
                $('.custom-drop-toggle').removeClass('show')
            })
        </script>

    @endpush

@endsection
