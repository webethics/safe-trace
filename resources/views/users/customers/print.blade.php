<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

 
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<body>	
<div class="print-page p-4">
<div class="container">
<div class="row">
<div class="col-12">
<div class="content-wrapper text-center">
<p style="font-family: Nunito,sans-serif;"><b>Business Name :</b> {{$user->business_name}}</p> 

<img class="qr-code-img" src="{{asset('uploads/qr_code/')}}/{{$image_path}}">
</div>
</div>
</div>
</div>
</div> 


	<script src="{{ asset('js/vendor/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
</body>

</html>