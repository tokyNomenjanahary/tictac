@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <style>
        .ligne
        {
            margin-top: 15px;
            margin-bottom: 4px;
            border: 1px solid;
        }

        .h3
        {
            font-size: 1.5rem;
            height: 3rem;
        }

        .card-p {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0px 3px 2px #aab2bd;
        }

        .card_info
        {
            text-decoration: none;
            color: #797979;
        }

        .card_info:hover >.card-p
        {
            background-color:#797979;
            color: #ffffff;

        }
    </style>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content-header">
        @if ($notif = Session::get('success'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        {{-- <h1><button class="btn btn-success btn-xs larg h3" title="Envoyer toctoc" data-toggle="modal" data-target="#envoi_toctoc"><i class="fa fa-paper-plane"></i> Envoyer tous les Toctoc</button></h1>
        <div class="modal fade" id="envoi_toctoc" tabindex="-1" role="dialog" aria-labelledby="envoi_toctocLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 1rem;">
                    <div class="modal-body">
                        <div class="form-panel">
                            <hr>
                            <center>
                                <h3>
                                    Vous êtes en train d'envoyér tous les toctoc. <br>
                                    Confirmez vous l'envoie ?
                                </h3>
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <a type="submit" class="btn btn-success" href="{{ route('admin.send_toctoc') }}" >Oui</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        </center>

                    </div>
                </div>
            </div>
        </div>
        <hr class="ligne"> --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card_info">
                    <div class="card-p">
                        <center>
                            <h4>Nombre de toctoc envoyé</h4>
                            <h2>{{ $nbr_toctoc_envoi_auto }}</h2>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card_info">
                    <div class="card-p">
                        <center>
                            <h4>Heur du dernière envoi de toctoc</h4>
                        <h2>{{ $date_envoi }} : 00</h2>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card_info">
                    <div class="card-p">
                        <center>
                            <h4>Heur suivant pour l'envoi du toctoc</h4>
                        <h2>{{ $date_suivant }} : 00</h2>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <h1>Toctoc pour ce qui cherche un logement à louer</h1><br>
        <div class="box box-primary">
            <div class="box-body table-responsive no-padding db-table-outer">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">
                            @if($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['first-name-0']) }}"><center>User send </center></a>
                            @elseif ($order_by == 'first-name-0')
                                <a href="{{ route('liste_toctoc',['first-name-1']) }}"><center>User send <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['first-name-0']) }}"><center>User send <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th scope="col">
                            @if($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['name-receive-0']) }}"><center>User receive</i></center></a>
                            @elseif ($order_by == 'name-receive-0')
                                <a href="{{ route('liste_toctoc',['name-receive-1']) }}"><center>User receive <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['name-receive-0']) }}"><center>User receive <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th>User ID send</th>
                        <th>User ID receive</th>
                        <th scope="col">Ville send</th>
                        <th scope="col">Ville receive</th>
                        <th scope="col">Title send</th>
                        <th scope="col">Title receive</th>
                        <th scope="col">
                            <center>
                                @if($order_by == 'date-envoi-0')
                                    <a href="{{ route('liste_toctoc',['post-ad-created-0']) }}"><center>Send : Ad post-ad-created</center></a>
                                @elseif ($order_by == 'post-ad-created-0')
                                    <a href="{{ route('liste_toctoc',['post-ad-created-1']) }}"><center>Send : Ad post-ad-created <i class="fa fa-sort-asc"></i></center></a>
                                @else
                                    <a href="{{ route('liste_toctoc',['post-ad-created-0']) }}"><center>Send : Ad post-ad-created <i class="fa fa-sort-desc"></i></center></a>
                                @endif
                            </center>
                        </th>
                        <th scope="col">
                            <center>
                                @if($order_by == 'date-envoi-0')
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-0']) }}"><center>Receive : Ad created on </center></a>
                                @elseif ($order_by == 'receive-ad-created-0')
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-1']) }}"><center>Receive : Ad created on <i class="fa fa-sort-asc"></i></center></a>
                                @else
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-0']) }}"><center>Receive : Ad created on <i class="fa fa-sort-desc"></i></center></a>
                                @endif
                            </center>
                        </th>
                        <th scope="col">
                            @if($order_by == 'first-name-1')
                                <a href="{{ route('liste_toctoc',['date-envoi-0']) }}"><center>Date d'envoi </center></a>
                            @elseif ($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['date-envoi-1']) }}"><center>Date d'envoi <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['date-envoi-0']) }}"><center>Date d'envoi <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($logement_entier as $l)
                            <tr style="background-color: {{ $l['style'] }}">
                                <td>{{ $l['first_name_send'] }}</td>
                                <td>{{ $l['first_name_receive'] }}</td>
                                <td><a href="{{ adUrl($l['ads_id']) }}">{{ $l['user_id_send'] }}</a></td>
                                <td><a href="{{ adUrl($l['ads_id_receive']) }}">{{ $l['user_id_receive'] }}</a></td>
                                <td>{{ $l['address_send'] }}</td>
                                <td>{{ $l['address_receive'] }}</td>
                                <td>{{ $l['title_send'] }}</td>
                                <td>{{ $l['title_receive'] }}</td>
                                <td><center>{{ $l['updated_at_post'] }}</center></td>
                                <td><center>{{ $l['updated_at_receive'] }}</center></td>
                                <td><center>{{ $l['date_envoi'] }}</center></td>
                                <td><center>{{ $l['status'] }}</center></td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <h1>Toctoc pour la colocation </h1><br>
        <div class="box box-primary">
            <div class="box-body table-responsive no-padding db-table-outer">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">
                            @if($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['first-name-0']) }}"><center>User send </center></a>
                            @elseif ($order_by == 'first-name-0')
                                <a href="{{ route('liste_toctoc',['first-name-1']) }}"><center>User send <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['first-name-0']) }}"><center>User send <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th scope="col">
                            @if($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['name-receive-0']) }}"><center>User receive</center></a>
                            @elseif ($order_by == 'name-receive-0')
                                <a href="{{ route('liste_toctoc',['name-receive-1']) }}"><center>User receive <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['name-receive-0']) }}"><center>User receive <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th>User ID send</th>
                        <th>User ID receive</th>
                        <th scope="col">Ville send</th>
                        <th scope="col">Ville receive</th>
                        <th scope="col">Title send</th>
                        <th scope="col">Title receive</th>
                        <th scope="col">
                            <center>
                                @if($order_by == 'date-envoi-0')
                                    <a href="{{ route('liste_toctoc',['post-ad-created-0']) }}"><center>Send : Ad post-ad-created </center></a>
                                @elseif ($order_by == 'post-ad-created-0')
                                    <a href="{{ route('liste_toctoc',['post-ad-created-1']) }}"><center>Send : Ad post-ad-created <i class="fa fa-sort-asc"></i></center></a>
                                @else
                                    <a href="{{ route('liste_toctoc',['post-ad-created-0']) }}"><center>Send : Ad post-ad-created <i class="fa fa-sort-desc"></i></center></a>
                                @endif
                            </center>
                        </th>
                        <th scope="col">
                            <center>
                                @if($order_by == 'date-envoi-0')
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-0']) }}"><center>Receive : Ad created on </center></a>
                                @elseif ($order_by == 'receive-ad-created-0')
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-1']) }}"><center>Receive : Ad created on <i class="fa fa-sort-asc"></i></center></a>
                                @else
                                    <a href="{{ route('liste_toctoc',['receive-ad-created-0']) }}"><center>Receive : Ad created on <i class="fa fa-sort-desc"></i></center></a>
                                @endif
                            </center>
                        </th>
                        <th scope="col">
                            @if($order_by == 'first-name-1')
                                <a href="{{ route('liste_toctoc',['date-envoi-0']) }}"><center>Date d'envoi <i class="fa fa-sort-desc"></i></center></a>
                            @elseif ($order_by == 'date-envoi-0')
                                <a href="{{ route('liste_toctoc',['date-envoi-1']) }}"><center>Date d'envoi <i class="fa fa-sort-asc"></i></center></a>
                            @else
                                <a href="{{ route('liste_toctoc',['date-envoi-0']) }}"><center>Date d'envoi <i class="fa fa-sort-desc"></i></center></a>
                            @endif
                        </th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($colocation as $c)
                            <tr style="background-color: {{ $c['style'] }}">
                                <td>{{ $c['first_name_send'] }}</td>
                                <td>{{ $c['first_name_receive'] }}</td>
                                <td><a href="{{ adUrl($c['ads_id']) }}">{{ $c['user_id_send'] }}</a></td>
                                <td><a href="{{ adUrl($c['ads_id_receive']) }}">{{ $c['user_id_receive'] }}</a></td>
                                <td>{{ $c['address_send'] }}</td>
                                <td>{{ $c['address_receive'] }}</td>
                                <td>{{ $c['title_send'] }}</td>
                                <td>{{ $c['title_receive'] }}</td>
                                <td><center>{{ $c['updated_at_post'] }}</center></td>
                                <td><center>{{ $c['updated_at_receive'] }}</center></td>
                                <td><center>{{ $c['date_envoi'] }}</center></td>
                                <td><center>{{ $c['status'] }}</center></td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>


@endsection
