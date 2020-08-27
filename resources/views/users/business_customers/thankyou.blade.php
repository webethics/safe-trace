@extends('layouts.customer')
@section('content')
@section('extraJsCss')
<style>
.thank-you-template{
	background:{{$site_data && $site_data->background_color ? $site_data->background_color : '#af172e'}}
}
.thank-you-template{
	color:{{$site_data && $site_data->font_color?$site_data->font_color:'#ffffff'}}
}

.thank-you-template .custom-control-input:checked ~ .custom-control-label::before{
	border-color:{{$site_data && $site_data->font_color?$site_data->font_color:'#ffffff'}}	
}
</style>
@stop
@if($site_data && $site_data->header_image)
	<div class="header-img"><img class="img-fluid" src="{{asset('uploads/custom_logo/')}}/{{$site_data->header_image}}"></div> 
@else
	<div class="header-img"><img class="img-fluid" src="{{asset('img/header-img.jpg')}}"></div> 
@endif

<div class="container">
		
		<div class="row">
			<div class="col-md-12">
				<div class="info">
					@if($covid=='N')
				    <img class="img-fluid mx-auto mb-3 d-block checkmark" src="{{asset('img/checkmark-outside.png')}}">
					<i class="fa fa-check"></i>
					Thank you {{$customer_name}} <br> for visiting on <br>  {{ date('m/d/Y h:i A') }} <br>
					<p style="margin-top: 1rem;">Your answers indicate you are at low risk for COVID-19 at this time. However, you should protect yourself and others from the spread of COVID-19.</p>
					
					<!-- <i class="fa fa-check"></i>
					Thank you {{$customer_name}} <br> for visiting on <br>  {{ date('m/d/Y h:i A') }} -->
					@else
					<img class="img-fluid mx-auto mb-3 d-block checkmark" src="{{asset('img/check-mark-close.png')}}">
				    Thank you {{$customer_name}} <br> for visiting on <br>  {{ date('m/d/Y h:i A') }}  <br>
				    <p style="margin-top: 1rem;">Your answers indicate that you should call your health care provider and describe your symptoms and/or your contact with someone who’s been diagnosed. Your doctor will determine next steps.<br>Most COVID-19 cases can be managed at home. Severe symptoms may require hospital treatment.</p>
					@endif
				</div>
			</div>
		</div>
		

		<div class="row hr-row">
		<div class="col-md-12">
		<hr></hr>
		</div>
		</div>
			

</div>		
@if($site_data && $site_data->footer_image)
	<div class="footer-img"><img class="img-fluid" src="{{asset('uploads/custom_logo/')}}/{{$site_data->footer_image}}"></div> 
@else
	<div class="footer-img"><img class="img-fluid" src="{{asset('img/footer-img.jpg')}}"></div> 
@endif
			

@endsection