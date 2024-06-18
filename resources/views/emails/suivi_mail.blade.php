@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <style>
        .ligne {
            margin-top: 15px;
            margin-bottom: 4px;
            border: 1px solid;
        }

        .h3 {
            font-size: 1.5rem;
            height: 3rem;
        }

        .card-p {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0px 3px 2px #aab2bd;
        }

        .card_info {
            text-decoration: none;
            color: #797979;
        }

        .card_info:hover > .card-p {
            background-color: #797979;
            color: #ffffff;

        }
    </style>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content-header">
            <div class="row">
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Nombre de mails envoyé aujourd'hui</h4>
                                <h2>{{$NbreMailDays}}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Nombre de mails envoyé cette semaine</h4>
                                <h2>{{$NbreMailWeeks}}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Nombre de mails envoyé ce mois</h4>
                                <h2>{{$NbreMailMonths}}</h2>
                            </center>
                        </div>
                    </div>
                </div>
            </div>


            <br>
            <h1>Liste des mails envoyés</h1><br>
            <div class="box box-primary">
                <div class="box-body table-responsive no-padding db-table-outer">
                    <table id="table" class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Date d'envoi</th>
                            <th scope="col">Type</th>
                            <th scope="col">Destinataire</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($emails as $email)
                            <tr>
                            <td>{{$email->created_at}}</td>
                            <td>{{$email->type}}</td>
                            <td>{{$email->destinataire}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td>Aucun mail envoyé</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

@endsection
