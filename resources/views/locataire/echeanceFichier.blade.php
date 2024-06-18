<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('finance.Avis_échéance')}}</title>
    <style>
        #boxes {
            /* display: flex !important; */
            width: 100% !important;
        }

        #column1 {
            /* float: left; */
            padding-left: 20px !important;
            width: 60%;
            height: 100px;
            /* border: solid 2px black; */
            display: inline-block;
        }

        #column2 {
            float: right;
            width: 40%;
            height: 100px;
            margin-top: 40px;
            /* border: solid 2px black; */
        }
    </style>
</head>

<body>
    <div class="container" style="border: 3px solid gray;color:black;background-color:#FFFFFF;padding:30px;">
                {{-- row 1 --}}
                <div class="row">
                    <div
                        style="width: 251px; position: relative; float:right; line-height: 11px;text-align:left;margin-top:30px;">
                        <font style="font-size:13px">
                            <strong>{{__('finance.Date')}}</strong>
                            {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}
                        </font>
                        <br><br>
                        <font style="font-size:13px">
                            <b>{{__('echeance.Période')}}</b>
                            {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} {{__('echeance.au')}}
                            <?php $date = new DateTime($location->debut);
                            $date->modify('last day of this month');
                            echo $date->format('d/m/Y'); ?>
                        </font>
                        <br>
                    </div>
                </div>
                {{-- end row 1 --}}
                {{-- <div style="page-break-after: always;"></div> --}}
                <div class="row" id="boxes" style="margin-top: 30px;">
                    <div style="text-transform:uppercase;" id="column2" class="col-md-6">
                        <strong>
                            <font style="font-size:12px; text-transform: uppercase;">
                                {{__('echeance.De')}}&nbsp;</font>
                        </strong>
                            {{$proprietaire->first_name}}
                            {{$proprietaire->last_name}} <br>
                            {{$proprietaire->address_register}}
                        <br>
                            {{ $numbre->mobile_no }}
                    </div>
                    <div style="margin-top: 50px;text-transform:uppercase;" id="column2" class="col-md-6">
                        <strong>
                            <font style="font-size:12px; text-transform: uppercase;">
                                {{__('echeance.A')}}&nbsp;</font>
                        </strong>
                        {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}
                        <br>
                        {{ $location->Locataire->TenantAddress }}
                        <br>{{ $location->Locataire->TenantZip }}
                        {{ $location->Locataire->TenantState }}
                        <br>
                        {{ $location->Locataire->tenant_country }}
                    </div>
                </div>
                <div class="row text-center" style="margin-top:10px; ">
                    <h1>
                        <font style="font-size:24px; text-transform: uppercase;">
                            <b>
                            {{__('finance.Avis_échéance')}}  {{getNomDumois(\Carbon\Carbon::parse($location->debut)->format('m') )}}
                        </font>
                    </h1>
                    <p style="margin-top: 15px;">
                        <font style="font-size:11px; line-height: 12px; color:#999999; text-transform: uppercase;">
                            {{__('echeance.document')}}
                        </font>
                    </p>
                </div>
                <hr>
                <div class="row" id="box" style="width: 100% !important;">
                    <div id="#col1"
                        style=" padding-left: 20px !important;width :50%;height : 100px;display : inline-block;float: left;">
                        <h5 class="text-center">{{__('echeance.info_locataire')}}</h5>
                        <hr>
                        <ul style="list-style-type:none;text-align:left;">
                            <li> <strong style="font-size:15px">{{__('echeance.Locataire')}}</strong>
                                <font style="font-size:12px">
                                    {{ $location->Locataire->civilite }}
                                    {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}
                                </font>
                            </li>
                            <li> <strong style="font-size:15px">{{__('echeance.Téléphone')}}</strong>
                                <font style="font-size:15px">
                                    {{ $location->Locataire->tenant_mobile_phone }}
                                </font>
                            </li>
                            <li> <strong style="font-size:15px">{{__('echeance.Email')}}</strong>
                                <font style="font-size:15px">
                                    {{ $location->Locataire->TenantEmail }}</font>
                            </li>
                        </ul>
                    </div>
                    <div id="#col2" style=" width : 50%; height: 100px;float:right;">
                        <h5 class="text-center">{{__('echeance.detail_terme')}}</h5>
                        <hr>
                        <ul style="list-style-type:none;text-align:left;">
                            <li> <strong style="font-size:15px">{{__('echeance.Loyer')}}</strong>
                                <font style="font-size:15px">
                                    {{ $location->loyer_HC }} €</font>
                            </li>
                            <li> <strong style="font-size:15px">{{__('echeance.Charges')}}</strong>
                                <font style="font-size:15px">
                                    {{ $location->charge }} €</font< /li>
                            <li> <strong style="font-size:15px">{{__('echeance.Loyer_charges_comprises')}}</strong>
                                <font style="font-size:15px">
                                    <strong>
                                        {{ $location->loyer_HC + $location->charge }} €
                                    </strong>
                                </font>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" style="margin-top:70px;padding-left:55px;">
                    <p style="font-size:15px;">
                        {{__('echeance.Locataire_a_payé')}} {{ $location->loyer_HC + $location->charge }} €
                        {{__('echeance.le')}} {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} <br>
                    </p>
                    <p style="padding;8px; border: 1px solid #000000;">
                        <span style=" padding: 5px;">
                            <b>
                                <strong>
                                    <font style="font-size:12px; text-transform: uppercase;padding:15px;">{{__('echeance.TOTAL_PAYÉ')}}
                                    </font>
                                    {{ $location->loyer_HC + $location->charge }} €
                                </strong>
                            </b>
                        </span>
                    </p>
                    <p style="font-size:13px;line-height:20px;">
                             {{__('echeance.location_adresse')}} <b>
                                {{$location->Logement->adresse}}
                            {{-- {{ $location->Locataire->TenantCity }} ,
                            {{ $location->Locataire->TenantState }} , <br>
                            {{ $location->Locataire->TenantAddress }} --}}
                            <br>
                                {{__('echeance.Pour_la_période_du')}}<b>
                                {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} {{__('echeance.au')}}
                                <?php $date = new DateTime($location->debut);
                                $date->modify('last day of this month');
                                echo $date->format('d/m/Y'); ?>
                    </p>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div style="width: 251px; position: relative; float:right; line-height: 11px;text-align:left;margin-top:20px;">
                        <span style="text-transform: uppercase;">{{$proprietaire->first_name}}</span>
                        {{$proprietaire->last_name}}
                        @if(!empty($signature))
                            <p>
                                <img
                                    style="height: {{$signature['desiredHeight']}};width: {{$signature['desiredWidth']}}; margin-top: 5px"
                                    src={{$signature['path']}}>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row text-center" style="margin-top:10px; ">
                    <p style="text-align:center; font-size:10px; line-height: 10px;">© Bailti</p>
                </div>
    </div>
    <script type="text/javascript" src="../assets/client/layout1/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"
        integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
