
@extends('proprietaire.index')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
<style>
    .fc-event-main{
        text-align: center !important;
    }
    .fc-view-harness-active{
        height: auto !important;
    }
    .dataTables_length{
        display: none !important;
    }
    .dataTables_info{
        display: none !important;
    }
    .form-select:disabled {
        color: whitesmoke;
        background-color: white !important;
    }
    .fc-timeGridWeek-button{
        background-color: blue !important;
    }
    .fc-button{
        background-color: #0a6ebd !important;
    }
    th{
        padding: 5px !important;
    }
    .fc-daygrid-body-unbalanced{
        display: none !important;
    }
</style>
@push("styles")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@section('contenue')
    <div class="content-wrapper"
         style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row tete mt-4">
                <div class="col-lg-6 col-sm-4 col-12 col-md-4 titre t-sm">
                    <h3 class="page-header page-header-top">{{__('agenda.Agenda_proprietaire')}}</h3>
                </div>
                <div class="col-lg-6 t-sm" style="text-align: right;">
                    <button type="button" class="btn btn-primary">
                        <a href="{{route('proprietaire.nouveauAgenda')}}" style="color: white;">{{__('agenda.nouveau')}}</a>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="card mb-4 p-4 mt-5">
                    <div id="calendar" class="mt-3"></div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-hover" id="Myrdv" style="margin-bottom:0px;border:2px solid #F3F5F6;">
                            <thead>
                            <tr>
                                <th>{{__('agenda.Objet')}}</th>
                                <th>{{__("ticket.Adresse")}}</th>
                                <th>{{__('agenda.status')}}</th>
                                <th>{{__('agenda.Debut')}}</th>
                                <th>{{__('agenda.Fin')}}</th>
                                <th>{{__('finance.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rdvs as $agenda)
                                <tr>
                                    <td>{{$agenda->objet}}</td>
                                    <td>{{$agenda->adresse_rdv}}</td>
                                    <td>
                                        @if($agenda->cree_par==1)
                                            <form method="POST" action="">
                                                @csrf
                                                @method('UPDATE')
                                                <select data-id="{{ $agenda->id }}" class="form-select filter-select-status" >
                                                    {{--                                                    @if($agenda->start_time) < {{\Carbon\Carbon::now()->format('d/m/Y')}} disabled @endif--}}
                                                    <option value="{{ $agenda->status }}" selected hidden>{{ $agenda->status }}</option>
                                                    @foreach (['Confirmé'=> 1, 'Refusé' => 2] as $option => $value)
                                                        @if ($option != $agenda->status)
                                                            <option value="{{ $value }}">{{ $option }} </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </form>
                                        @else
                                            {{ $agenda->status }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($agenda->start_time)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($agenda->finish_time)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if($agenda->cree_par==0)
                                            {{--                                            <button class="btn btn-info"><a href="{{route('agenda.edit',$agenda->id)}}"><i class="fa fa-edit"></i></a></button>--}}
                                            <button class="btn btn-info"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modification{{$agenda->id}}"><i class="fa fa-edit"></i></a></button>
                                        @endif
                                        <div class="modal fade" id="modification{{$agenda->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTitleId">{{__('agenda.Modification')}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{__('agenda.validationModif')}}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                        <a href="{{route('agenda.edit',$agenda->id)}}" type="button" class="btn btn-info ">{{__('agenda.Confirmer')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal" tabindex="-1" role="dialog" id="eventModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalTitle"></h5>
                            </div>
                            <div class="modal-body" id="eventModalBody">
                                <span id="eventModalStart" style="border: 1px solid gray;padding: 5px;"></span><span id="eventModalEnd" style="border: 1px solid gray;padding: 5px;"></span>
                                <div class="mt-5">
                                    <h5 class="">{{__('agenda.Lieu_rdv')}}</h5>
                                    <p>{{__("ticket.Adresse")}} : <span id="eventModalAdresse"></span></p>
                                    <h5 class="">{{__('echeance.Locataire')}}</h5>
                                    <p><span id="eventModalLocataire"></span></p>
                                    <h5 class="">{{__("ticket.Déscription")}}</h5>
                                    <p> <span id="eventModalDescription"></span></p>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal">{{__('agenda.fermer')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'timeGridWeek',
                        // initialView: 'dayGridMonth',
                        // initialView: 'dayGridWeek',
                        slotMinTime: '8:00:00',
                        slotMaxTime: '23:00:00',
                        events: @json($events),
                        headerToolbar: {
                            left: 'prev,next',
                            center: 'title',
                            right: 'timeGridWeek,timeGridDay' // user can switch between the two
                        },
                        eventClick: function (info) {
                            // Remplissez le contenu du modal avec les détails du rendez-vous
                            document.getElementById('eventModalTitle').innerText = info.event.title;
                            document.getElementById('eventModalStart').innerText = info.event.start.toDateString();
                            document.getElementById('eventModalEnd').innerText = info.event.end.toDateString();
                            document.getElementById('eventModalDescription').innerText = info.event.extendedProps.description;
                            document.getElementById('eventModalAdresse').innerText = info.event.extendedProps.adresse;
                            document.getElementById('eventModalLocataire').innerText = info.event.extendedProps.locataire;

                            // Ouvrez le modal
                            $('#eventModal').modal('show');
                        },
                    });
                    var appLocale = "{{ $locale }}";

                    if (appLocale === 'fr') {
                        calendar.setOption('locale', 'fr');
                    } else {
                        calendar.setOption('locale', 'en');
                    }
                    calendar.render();
                });
                $(document).ready(function() {
                    var agenda = $('#Myrdv').DataTable({
                        "pageLength": 10,
                        "language": {
                            "paginate": {
                                "previous": "&lt;", // Remplacer "previous" par "<"
                                "next": "&gt;" // Remplacer "next" par ">"
                            },
                            "lengthMenu": "Filtre _MENU_ ",
                            "zeroRecords": "Pas de recherche corespondant",
                            "info": "Affichage _PAGE_ sur _PAGES_",
                            "infoEmpty": "Pas de recherche corespondant",
                            "infoFiltered": "(filtered from _MAX_ total records)"
                        },
                        "createdRow": function(row, data) {
                            // Accéder à la valeur de la colonne 3
                            var status = $('#Myrdv').DataTable().cell(row, 2).data();
                            if (status == 'confirmé') {
                                $(row).css('background-color', '#fcdada');
                            }else if (status == 'en attente'){
                                $(row).css('background-color', '#FFF3BD');
                            } else if(status == 'annulé'){
                                $(row).css('opacity', '0.3');
                            }
                        },
                        "scrollCollapse" : true,
                        ordering:true
                    });
                    $('.filter-select-status').change(function (e) {
                        e.preventDefault();
                        var newstatus = $(this).val();
                        var id = $(this).attr('data-id')
                        var url = "{{ route('agenda.status') }}";
                        var token = "{{ csrf_token() }}";

                        $("#myLoader").removeClass("d-none")
                        $.ajax({
                            url: url,
                            'type' : "POST",
                            data: {
                                'status': newstatus,
                                'id' : id,
                                '_token': token
                            },
                            success: function (data) {
                                toastr.success("status mis a jour");
                                $("#myLoader").addClass("d-none")                },
                            error: function (data) {
                                toastr.success("Erreur lors de l\'enregistrement du changement de valeur");
                            }
                        });
                    })
                })
            </script>

