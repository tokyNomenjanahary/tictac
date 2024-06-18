<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
} else {
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>
@extends($entete)

@section($contenue)
    <style>
        th {
            color: blue !important;
            font-size: 10px !important;
        }

        td {
            font-size: 13px;
        }

        .dataTables_length {
            display: none !important;
        }

        .dataTables_empty {
            display: none !important;
        }

        .dataTables_info {
            display: none !important;
        }

        div.dataTables_wrapper div.dt-row {
            position: static !important;
        }
    </style>
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row tete mt-4">
                <div class="col-lg-3 col-sm-4 col-12 col-md-4 titre">
                    <h3 class="page-header page-header-top m-0">Message</h3>
                </div>
                <div class="col-12 col-lg-5 col-sm-7 col-md-5 mb-2">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="navs-top-actif-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                                aria-controls="home" aria-selected="true">
                                <i class="fa fa-check m-r-5"></i>Boîte de réception

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="navs-top-archive-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_archive" type="button" role="tab"
                                aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i>
                                Message envoyé
                                <span id="ArchivesCounts" class="badge bg-primary"></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <a href="#" style="color: white;"><i class="fa fa-plus-circle"></i>Nouveau message</a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:15px">
                <div class="col">
                    <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                        <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row"
                                style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="documents"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th>

                                                </th>
                                                <th style="width:65%">Message</th>
                                                <th class="d-none"></th>
                                                <th style="width: 20%">Date</th>
                                                <th>{{ __('finance.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($message_recu as $recu)
                                                @if (!empty($recu->Message))
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-center rounded-pill bg-primary"
                                                                style="width:50px;height:50px;">{{ strtoupper(substr($recu->getInfoReceiver(), 0, 2)) }}</span>

                                                        </td>
                                                        <td>
                                                            <p><span
                                                                    style="font-size: 14px;color:blue">{{ $recu->getInfoReceiver() }}</span>
                                                                {{ '(' . $recu->Subject . ')' }} @if (!$recu->read)
                                                                    <span style="color: red;font-size:19px">nouv</span>
                                                                @endif

                                                            <h6>{{ $recu->Message }}</h6>
                                                            </p>
                                                        </td>
                                                        <td class="d-none">{{ $recu->Subject }}</td>
                                                        <td class=" align-items-center text center">
                                                            <h6>{{ \Carbon\Carbon::parse($recu->date_sent)->format('d/m/Y') }}
                                                            </h6>
                                                        </td>
                                                        <td class=" align-items-center text center">
                                                            <div class="dropdown" style="position: static !important;">
                                                                <button type="button"
                                                                    class="btn p-0 dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu" style="z-index: 2000">
                                                                    <a href="/delete_message/{{ $recu->id }}">
                                                                        <i class="fa-solid fa-trash"
                                                                            style="color:red;"></i>{{ __('location.supprimer') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="coloc_archive" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row"
                                style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="archiveTab"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th>

                                                </th>
                                                <th style="width:65%">Message</th>
                                                <th class="d-none"></th>
                                                <th style="width: 20%">Date</th>
                                                <th>{{ __('finance.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($message_envoi as $envoi)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-center rounded-pill bg-primary"
                                                            style="width:50px;height:50px;">{{ strtoupper(substr($envoi->ticket->getInfoReceiver(), 0, 2)) }}</span>

                                                    </td>
                                                    <td>
                                                        <p><span style="font-size: 14px;color:blue">{{ $envoi->ticket->getInfoReceiver() }}
                                                            </span> {{ '(' . $envoi->ticket->Subject . ')' }}</p>
                                                        <h6>{{ $envoi->Message }}</h6>
                                                    </td>
                                                    <td class="d-none">{{ $envoi->ticket->Subject }}</td>
                                                    <td class=" align-items-center text center">
                                                        <h6>{{ \Carbon\Carbon::parse($envoi->created_at)->format('d/m/Y') }}
                                                        </h6>
                                                    </td>
                                                    <td class=" align-items-center text center">
                                                        <div class="dropdown" style="position: static !important;">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a href="/delete_message/{{ $envoi->id }}"
                                                                    class="dropdown-item ">
                                                                    <i class="fa-solid fa-trash"
                                                                        style="color:red;"></i>{{ __('location.supprimer') }}
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade">
    </div>
@endsection
