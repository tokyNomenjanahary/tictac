<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" integrity="sha512-t4GWSVZO1eC8BM339Xd7Uphw5s17a86tIZIj8qRxhnKub6WoyhnrxeCIMeAqBPgdZGlCcG2PrZjMc+Wr78+5Xg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="">
        <div class="row">
            <img style="width: 90px;height: 100px;" src="{{ storage_path('app/public/allocationFamilliale.png') }}" alt="AllocationFamilliale">
            <img style="width: 140px;height: 160px;" src="{{ storage_path('app/public/sante.png') }}" alt="AllocationFamilliale">
            <img style="width: 150px;height: 120px;margin-left:550px;margin-top:-160px"src="{{ storage_path('app/public/cerfa.png') }}" alt="AllocationFamilliale">
            <hr style="background: black;margin-top:-40px;">
            {{-- <img style="width: 160px;height: 150px;" src="{{asset('assets/img/lotie/allocationFamilliale.png')}}" alt="">
            <img style="width: 200px;height: 160px;" src="{{asset('assets/img/lotie/sante.png')}}" alt="">
            <img style="width: 220px;height: 160px;margin-left:1000px;margin-top:-150px" src="{{asset('assets/img/lotie/cerfa.png')}}" alt=""> --}}
        </div>
    </div>
</body>
</html>
