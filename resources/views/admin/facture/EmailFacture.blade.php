<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width" />
  <!-- NOTE: external links are for testing only -->
  <link href="{{ storage_path('css/mail/email-inline.css') }}" rel="stylesheet">
  <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet">
  <link href="{{ storage_path('/css/styleAnymate.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ storage_path('/css/cssHome/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ storage_path('/css/styleHome.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ storage_path('/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ storage_path('/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css">
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

.right{
    float: right;
}

.top{
    margin-top: 4rem;
}

.cadre{
    border: 2px solid;
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
              <a href="{{ url('/') }}"><img src="{{ URL::asset('images/blue-logo-1.png') }}" alt="{{ config('app.name', 'TicTacHouse') }}"></a>
              <div class="mui-divider-bottom"></div>
              
                <h3>{{ __('mail.fac_intro') }}</h3>
                
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