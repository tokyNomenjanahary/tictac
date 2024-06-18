@extends('layouts.app')
<!-- Push a script dynamically from a view -->
<!-- @push('styles')
    <link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
@endpush
-->
@section('content')
<div class="main-container" style="position: relative;">
	<div class="content">
		<h1>{{__('404.title')}}</h1>
		<p>
			{{__('404.text')}}
		</p>
			<a href='/' class="btn-acceuil-404">{{__('404.button_acceuil')}}</a>
	 </div>
</div>
<style>
.footer
{
	margin-top : initial !important;
}

.img-hidden
{
	width : 100%;
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
}

h1
{
	color : red;
	width : 250px;
	margin : auto;
	
}
.content
{
	top : 0px;
	width : 80%;
	min-width : 300px;
	margin : auto;
	padding-top : 150px;
}
.content p
{
	color : white;
	font-weight : bold;
	font-size : 20px;
	width : 50%;
	min-width : 250px;
	margin: auto;
}

.main-container
{
	color:black;
	background-color:white;
	background-image:url(/img/slider.jpg);
	background-repeat:no-repeat;
	background-size : 100% 600px;
	height : 600px;
	
}
</style>
@endsection
