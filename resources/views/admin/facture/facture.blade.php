<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <title>Facture</title>
        <style>

            .right{
                float: right;
            }

            .top{
                margin-top: 4rem;
            }

            .cadre{
                border: 3px solid #555;
            }
            .box-info{
                width: 30rem;
                height: auto;
            }
            .box-info1{
                width: 12rem;
                height: auto;
            }
            .box-info2{
                width: 16rem;
                height: auto;
            }

            .flex-end{
                justify-content: flex-end!important;
            }

            .row {
                /*display: -ms-flexbox;*/
                display: flex;
                /*-ms-flex-wrap: wrap;*/
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
            }

            .w100{
                width: 100%;
            }

            .top8{
                margin-top: 8rem;
            }

            .content{
                margin-left: 3rem;
                margin-right: 3rem;
            }

            .top1{
                margin-top: 1rem;
            }

            .facture{
                border: 2px solid #cfcbcb;
            }

            .m10{
                margin: 10px;
            }

            .left{
                text-align: left;
            }

            .size12{
                font-size: 12px;
            }

        </style>
    </head>
    <body>
        <div class="">
            <h1><center>{{ __('mail.fac_facture') }}</center> </h1>
            <div class="m10">
                <div class="box-info1 left">
                    <a href="{{ url('/') }}"><img src="https://res.cloudinary.com/dl7aa4kjj/image/upload/v1656922665/Bailti/img/blue-logo-1_pfuf4p.png" width="100" alt="logo"></a>
                    <div class="size12">
                        {!! __('mail.reseau_social') !!}
                    </div>
                </div>
                <div class="box-info2  right top1">
                    <strong>{{ $packageDetail['userName'] }}</strong><br>
                    <strong>{{ $packageDetail['adress'] }}</strong>

                </div>
                <div class="top8">
                    <div class="box-info ">
                        {{ __('mail.fac_date') }} : <strong>{{ $packageDetail['date'] }}</strong><br>
                        {{ __('mail.fac_facture') }} N<sup>o</sup> <strong>{{ $packageDetail['id_fac'] }}</strong><br>
                        <strong>TVA: 20%</strong>
                    </div>
                    <div class="facture">
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table_wt">
                            <thead>
                                <tr>
                                <th><center>{{ __('mail.fac_pack') }}</center></th>
                                <th><center>{{ __('mail.fac_duree') }}</center></th>
                                <th><center>{{ __('mail.fac_prix') }} (TTC)</center></th>
                                </tr>
                            </thead>
                            <tbody>

                                    <tr>
                                    <td><center>{{ $packageDetail['packageTitle'] }}</center></td>
                                    <td>
                                        <center>
                                            {{ $packageDetail['packageDuration'] }} {{ traduct_info_bdd($packageDetail['unite'])}} <br>
                                            {{ $packageDetail['packageStartDate'] }} à {{ $packageDetail['packageEndDate'] }}
                                        </center>
                                    </td>
                                    <td><center>{{ $packageDetail['packageAmount']*10**-2 }} {{ $currency }}  </center></td>
                                    </tr>

                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="top1">
                    <center>
                        <strong>{{ env('APP_NAME', 'Bailti') }}</strong>
                        <p>{{ __('mail.paris_naples') }}</p>
                    </center>
                </div>
            </div>
        </div>
    </body>
</html>
