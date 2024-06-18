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
                                <h4>Number of toctoc auto sent today</h4>
                                <h2>{{ $toctocToday }}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                @php
                    $nowInParisMoins = Carbon\Carbon::now('Europe/Paris')->hour;
                    $nowInParisPlus = Carbon\Carbon::now('Europe/Paris')->addHour(+1)->hour;
                @endphp
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Time of the last sending of the Toctoc auto</h4>
                                <h2>{{ $nowInParisMoins }} : 00</h2>

                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Next time of sending the Toctoc auto</h4>
                                <h2>{{ $nowInParisPlus }} : 00</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Max number of toctoc auto per day</h4>
                                <h2>{{$maxToctocToday}}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Max toctoc auto for the receiver per day</h4>
                                <h2>{{ $maxToctocReceive }}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Max toctoc auto for the receiver per execution</h4>
                                <h2>{{ $maxToctocReceiveExec }}</h2>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card_info">
                        <div class="card-p">
                            <center>
                                <h4>Maximum toctoc auto per user per day</h4>
                                <h2>{{ $maxToctocUser }}</h2>
                            </center>
                        </div>
                    </div>
                </div>


            </div>


            <br>
            <h1>Toctoc auto for those looking for entire home</h1><br>
            <div class="box box-primary">
                <div class="box-body table-responsive no-padding db-table-outer">
                    <table id="table" class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">User send</th>
                            <th scope="col">User receive</th>
                            <th scope="col">Ads ID User send</th>
                            <th scope="col">Ads ID User receive</th>
                            <th scope="col">Ville send</th>
                            <th scope="col">Ville receive</th>
                            <th scope="col">Title send</th>
                            <th scope="col">Title receive</th>
                            <th scope="col">Ads created send</th>
                            <th scope="col">Ads created receive</th>
                            <th scope="col">Sending date toctoc auto</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allUserRentAPropertys as $allUserRentAProperty)
                            @foreach($allUserSeekAPropertys as $allUserSeekAProperty)
                                @php
                                    $coupdefoudre = App\coup_de_foudre::where('sender_id',$allUserRentAProperty->user_id)
                                                     ->where('receiver_id', $allUserSeekAProperty->user_id)
                                                     ->where('ad_id',$allUserRentAProperty->id)
                                                     ->where('sender_ad_id',$allUserRentAProperty->id)->first();
                                          if(isset($coupdefoudre)){
                                               $duplication = $coupdefoudre->count();
                                          }
                                          else{
                                              $duplication = 0;
                                          }
                                                                //Get user rent information
                                                                 $user_id_rent = $allUserRentAProperty->user_id;
                                                                 $user_id_seek = $allUserSeekAProperty->user_id;

                                                                 $ads_id_rent = $allUserRentAProperty->id;
                                                                 //Get distance between ad cities
                                                                 $distance = 6366 * acos(cos(deg2rad($allUserRentAProperty->latitude))
                                                                         * cos(deg2rad($allUserSeekAProperty->latitude))
                                                                         * cos(deg2rad($allUserSeekAProperty->longitude)
                                                                             - deg2rad($allUserRentAProperty->longitude))
                                                                         + sin(deg2rad($allUserRentAProperty->latitude))
                                                                         * sin(deg2rad($allUserSeekAProperty->latitude)));
                                                                 //Round 4 decimal places
                                                                 $distance_roud = round($distance, 4);
                                @endphp

                                @if($distance_roud <= 40 && $user_id_seek != $user_id_rent)
                                    @if($duplication == 0)
                                        <tr style="background-color: #eab676">
                                    @else
                                        <tr style="background-color: #76b5c5">
                                            @endif
                                            <td>{{$allUserRentAProperty->first_name}}</td>
                                            <td>{{$allUserSeekAProperty->first_name}}</td>
                                            <td>
                                                <a href="{{adUrl($allUserRentAProperty->id)}}">{{$allUserRentAProperty->id}}</a>
                                            </td>
                                            <td>
                                                <a href="{{adUrl($allUserSeekAProperty->id)}}">{{$allUserSeekAProperty->id}}</a>
                                            </td>
                                            <td>{{$allUserRentAProperty->address}}</td>
                                            <td>{{$allUserSeekAProperty->address}}</td>
                                            <td>{{$allUserRentAProperty->title}}</td>
                                            <td>{{$allUserSeekAProperty->title}}</td>
                                            <td>{{$allUserRentAProperty->created_at}}</td>
                                            <td>{{$allUserSeekAProperty->created_at}}</td>
                                            @if($duplication != 0)
                                                <td>{{$coupdefoudre->created_at}}</td>
                                            @else
                                                <td>Not send</td>
                                            @endif
                                            @if($duplication == 0)
                                                <td>In progress</td>
                                            @else
                                                <td>Send</td>
                                            @endif
                                        </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

@endsection
