<html lang="en"><head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('404.title') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 36px;
                padding: 20px;
            }

            .btn-acceuil-404
			{
				display : block;
				padding : 15px;
				width : 250px;
				background-color : rgb(40,146,254);
				color : white;
				margin-top : 60px !important;
				text-align : center;
				margin : auto;
				font-weight: bold;
    			text-decoration: none;
			}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title">
                @if(isset($reportMessage))
                {{$reportMessage}} 
                @else
                {{ __('404.something_wrong') }}
                @endif
                </div>
                <a href='/report_error' class="btn-acceuil-404">{{__('404.button_send_error')}}</a>
            </div>
        </div>
    

</body></html>