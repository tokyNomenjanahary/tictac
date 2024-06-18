<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- NOTE: external links are for testing only -->
        <link href="{{ storage_path('css/mail/email-inline.css') }}" rel="stylesheet">
        <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>

          .table_wt{
            background: #34495E;
            color: #fff;
            border-radius: .4em;
            overflow: hidden;
            tr {
              border-color: lighten(#34495E, 10%);
                }

            th, td {
              margin: .5em 1em;
              @media (min-width: $breakpoint-alpha) {
                padding: 1em !important;
              }
            }

            th, td:before {
              color: #fff;
            }
            td{
              color: #fff;
            }

          }

        </style>
      </head>
    <body>
      <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
        <div class="mui-panel">
          <div class="container">
            <center>
              <div style="text-align:center;" class="mui-container">
                  <div class="mui-divider-bottom">
                      <a href="{{ url('/') }}"><img src="{{URL::asset('images/blue-logo-1.png')}}" alt="{{ config('app.name', 'TicTacHouse') }}"></a>
                      <div class="mui-divider-bottom"></div>

                      <p style="padding:20px; text-align:left;">
                        {{ __('mail.hi') }} <br><br>
                          {{ __('mail.n_indicator') }} : {{ date('d-m-Y') }} </p>
                      <h6>{{ __('mail.ad_post_community') }}</h6>
                      <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
                        <div class="box-body table-responsive no-padding db-table-outer">
                          <table class="table table_wt">
                            <thead>
                              <tr>
                                <th><center>Today</center></th>
                                <th><center>1 W </center></th>
                                <th><center>1 M  </center></th>
                                <th><center>3 M</center></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><center>{{ $comunityCount }}</center></td>
                                <td><center>{{ $moyenne_semaine }}</center></td>
                                <td><center>{{ $moyenne_un_mois }}</center></td>
                                <td><center>{{ $moyenne_trois_mois }}</center></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <p style="padding:10px; text-align:left;">
                        <h6>{{ __('mail.coup_foudre') }}</h6>
                      </p>
                      <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table_wt">
                          <thead>
                            <tr>
                              <th><center>Today</center></th>
                              <th><center>1 W </center></th>
                              <th><center>1 M </center></th>
                              <th><center>3 M</center></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><center>{{ $nb_coup_de_foudre }}</center></td>
                              <td><center>{{ $moyenne_coup_de_foudre_semaine }}</center></td>
                              <td><center>{{ $moyenne_coup_de_foudre_mois }}</center></td>
                              <td><center>{{ $moyenne_coup_de_foudre_trois_mois }}</center></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <p style="padding:20px; text-align:left;">
                        <h6>{{ __('mail.comment_community') }}</h6>
                      </p>
                      <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
                        <div class="box-body table-responsive no-padding db-table-outer">
                          <table class="table table_wt">
                            <thead>
                              <tr>
                                <th><center>Today</center></th>
                                <th><center>1 W </center></th>
                                <th><center>1 M </center></th>
                                <th><center>3 M</center></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th><center>{{ $nbr_comments_by_community_now }}</center></th>
                                <td><center>{{ $moyenne_cbc_semaine }}</center></td>
                                <td><center>{{ $moyenne_cbc_mois }}</center></td>
                                <td><center>{{ $moyenne_cbc_3mois }}</center></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <p style="padding:10px; text-align:left;">
                        <h6>{{ __('mail.last_community_comment') }}</h6>
                      </p>
                      <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table_wt">
                          <thead>
                            <tr>
                              <th><center>Comunity</center></th>
                              <th><center>Date</center></th>
                               <th><center>Nombre de commentaire</center></th> 
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($lists as $l)
                            <tr>
                              <td><center>{{ $l['first_name'] }}</center></td>
                              <td><center>{{ $l['date_enregistrement'] }}</center></td>
                              <?php $a=$l['id_user']; ?>
                              <td><center><?php echo nbr_comments_by_community ($a) ?></center></td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                      <br><br>
                      <br />
                      {{ __('mail.team' ) }}
                      <br/>
                  </div>

              </div>
          </center>
        </div>
      </div>

    </body>
</html>