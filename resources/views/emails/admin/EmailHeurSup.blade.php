<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width" />
  <!-- NOTE: external links are for testing only -->
  <link href="{{ storage_path('css/mail/email-inline.css') }}" rel="stylesheet">
  <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
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
              @if ($data_heur_sup )
              <p style="padding:20px; text-align:left;">
                {{ __('mail.hi') }} <br><br>
                {{ __('mail.community_Hsup') }} : {{ date('l-d-m-Y') }} </p>
                <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
                  <div class="box-body table-responsive no-padding db-table-outer">
                    <table class="table table_wt">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>H Sup</th>
                          <th>H Emp</th>
                          <th>H Coms</th>
                          <!--th>Liens</th-->
                        </tr>
                      </thead>
                      <tbody>
                        
                          @foreach ($data_heur_sup as $d)
                            <tr>
                              <td><center>{{ $d['user'] }}</center></td>
                              <td><center>{{ $d['heur_sup']}}</center></td>
                              <td><center>{{ $d['heur_fin'] }}</center></td>
                              <td><center>{{ $d['heur_coms'] }}</center></td>
                              <!--td><center><a type="button" class="btn btn-round btn-primary btn-sm" href="{{ $d['lien'] }}">Lien</a></center></td-->
                            </tr>
                          @endforeach
                        
                      </tbody>
                    </table>
                  </div>
                </div>
                @endif
                <br><br>
                <p>{{ __('mail.worked_today') }} : {{ date('l-d-m-Y') }}</p>
                <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
                  <div class="box-body table-responsive no-padding db-table-outer">
                      <table class="table table_wt">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>H Travailler</th>
                                  <!--th>Liens</th-->
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($data_heur_travallier as $m)
                              <tr>
                                  <td><center>{{ $m['user'] }}</center></td>
                                  <td><center>{{ $m['heur_travailler'] }} h</center></td>
                              </tr>
                              @endforeach
                              
                          </tbody>
                      </table>
                  </div>
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