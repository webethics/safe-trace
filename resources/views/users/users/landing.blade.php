@extends('layouts.landing')
@section('content')
@section('extraJsCss')
<script src="{{ asset('js/module/jquery.landing.js')}}"></script>
 <script>
	(function($){
        $(window).on("load",function(){
            $("a[rel='m_PageScroll2id']").mPageScroll2id({
			  offset:"#mainNav"
			});
        });
    })(jQuery);
  </script>
  <script>
	 jQuery('.pricing_box').hover(
	   function(){ 
		 jQuery(".active").addClass('inactive').removeClass('active');
	   },
	   function(){ 
		 jQuery(".inactive").addClass('active').removeClass('inactive'); 
	   }
	 );
 </script>
@stop

  <nav class="navbar navbar-expand-lg navbar-light py-2 fixed-top" id="mainNav" data-scroll-header>
         <div class="container">
            <a class="navbar-brand " href="{{url('/')}}"><img class="img-fluid" src="{{asset('img/logo.png')}}"></a>
			   <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
			   <ul class="navbar-nav ml-auto my-2 my-lg-0">
                  <li class="nav-item"><a class="nav-link" href="#home-banner" rel="m_PageScroll2id" >Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="#how_it_works" rel="m_PageScroll2id">How It Works</a></li>
                  <li class="nav-item"><a class="nav-link" href="#pricing" rel="m_PageScroll2id">Pricing</a></li>
				  <li class="nav-item"><a class="nav-link" href="#about" rel="m_PageScroll2id">About Us</a></li>
                  <li class="nav-item"><a class="nav-link" href="#contact" rel="m_PageScroll2id">Contact Us</a></li>
               </ul>
			</div>
         </div>
      </nav>
	  
  <div class="wrapper-main">
  <div class="wrapper-main">
	 <section id="home-banner" class="masthead page-section">
            <div class="container h-100">
               <div class="row h-100 align-items-center justify-content-center text-center">
                  <div class="col-lg-12 align-self-end">
                     <h1 class="text-uppercase text-white">Contact Tracing at your fingertips</h1>
                  </div>
                  <div class="col-lg-12 align-self-baseline">
                     <p class="text-white">Safe-Trace is a fast, secure, cost-effective solution for businesses to collect patron information per city guidelines and utilize the data for future marketing initiatives.</p>
                  </div>
               </div>
            </div>
         </section>
		 
		<section class="how_it_works_sec page-section" id="how_it_works">
            <div class="container">
               <h2 class="text-center mt-0 title-heading">How It Works</h2>
               <p class="text-center sub-title-heading">Simple and easy customer experience</p>
               <hr class="divider my-4">
               <div class="row">
                  <div class="col-lg-3 col-md-6 d-flex text-center">
                     <div class="box-wrap mt-5">
                        <img class="img-fluid" src="{{asset('img/hiw_step_1.jpg')}}">
                        <h3 class="mb-2 font-weight-semi-bold">SCAN QR Code</h3>
                        <p class="mb-0">Scan the restaurant's QR code at the entrance. Use cell phone camera or QR Code reader app.</p>
                     </div>
                  </div>
                  <div class="col-lg-3 col-md-6 d-flex text-center">
                     <div class="box-wrap mt-5">
                        <img class="img-fluid" src="{{asset('img/hiw_step_2.jpg')}}">
                        <h3 class="mb-2 font-weight-semi-bold">Enter Contact info</h3>
                        <p class="mb-0">Simply enter the contact information requested via the page provided by the QR Code scan.</p>
                     </div>
                  </div>
                  <div class="col-lg-3 col-md-6 d-flex text-center">
                     <div class="box-wrap mt-5">
                        <img class="img-fluid" src="{{asset('img/hiw_step_3.jpg')}}">
                        <h3 class="mb-2 font-weight-semi-bold">Green for Go</h3>
                        <p class="mb-0">After entering the information, click "submit" and show green check to the host/hostess.</p>
                     </div>
                  </div>
                  <div class="col-lg-3 col-md-6 d-flex text-center">
                     <div class="box-wrap mt-5">
                        <img class="img-fluid" src="{{asset('img/hiw_step_4.jpg')}}">
                        <h3 class="mb-2 font-weight-semi-bold">Enjoy your meal</h3>
                        <p class="mb-0">Guests enjoy their dining experience</p>
                     </div>
                  </div>
               </div>
            </div>
         </section>
		 
	 
		<section class="pricing-sec page-section" id="pricing">
            <div class="container">
               <div class="row justify-content-center mb-5">
                  <div class="col-md-12 text-center">
                     <h2 class="text-center mt-0 title-heading">Pricing Packages</h2>
                     <p class="text-center sub-title-heading">Choose the package that best fits your business’s needs</p>
                     <hr class="divider my-4">
                  </div>
               </div>
               <div class="row pricing_box_row">
                  <div class="col-lg">
                     <div class="pricing_box box_1">
                       <h3 class="font-weight-bold">${{$plan_data[0]->cost}} <sub>/ month</sub>
						 <span class="time_period">{{$plan_data[0]->plan_interval}} month subscription </span>
						</h3>
                        <p><i class="fas fa-check"></i> Safe & secure data storage</p>
                        <p><i class="fas fa-check"></i> Reduce labor cost</p>
                        <p><i class="fas fa-check"></i> Instant email marketing database</p>
                        <p><i class="fas fa-check"></i> Complete branded experience</p>
                        <p><i class="fas fa-check"></i> Export scan reports through web-based portal</p>
                        <p><i class="fas fa-check"></i> Contact-free information retrieval</p>
                        <p><i class="fas fa-check"></i> Download-free web application</p>
                        <p><i class="fas fa-check"></i> Adhere to city regulations stress-free</p>
						<div class="buy_now_box"><a href="{{url('register')}}/{{$plan_data[0]->stripe_plan}}" name="package" class="btn btn-primary btn-lg btn-shadow uppercase_button buy_now" >Buy Now</a> </div>
						
                     </div>
                  </div>
                  <div class="col-lg active">
                     <div class="pricing_box box_2">
                        <h3 class="font-weight-bold">${{$plan_data[1]->cost}} <sub>/ month</sub> <span class="time_period">{{$plan_data[1]->plan_interval}} month subscription </span></h3>
                        <p><i class="fas fa-check"></i> Safe & secure data storage</p>
                        <p><i class="fas fa-check"></i> Reduce labor cost</p>
                        <p><i class="fas fa-check"></i> Instant email marketing database</p>
                        <p><i class="fas fa-check"></i> Complete branded experience</p>
                        <p><i class="fas fa-check"></i> Export scan reports through web-based portal</p>
                        <p><i class="fas fa-check"></i> Contact-free information retrieval</p>
                        <p><i class="fas fa-check"></i> Download-free web application</p>
                        <p><i class="fas fa-check"></i> Adhere to city regulations stress-free</p>
						<div class="buy_now_box"><a href="{{url('register')}}/{{$plan_data[1]->stripe_plan}}" class="btn btn-primary btn-lg btn-shadow uppercase_button buy_now"  name="package">Buy Now</a> </div>
                     </div>
                  </div>
                  <div class="col-lg">
                     <div class="pricing_box box_3">
                       <h3 class="font-weight-bold">${{$plan_data[2]->cost}} <sub>/ month</sub> <span class="time_period">{{$plan_data[2]->plan_interval}} month subscription </span></h3>
                        <p><i class="fas fa-check"></i> Safe & secure data storage</p>
                        <p><i class="fas fa-check"></i> Reduce labor cost</p>
                        <p><i class="fas fa-check"></i> Instant email marketing database</p>
                        <p><i class="fas fa-check"></i> Complete branded experience</p>
                        <p><i class="fas fa-check"></i> Export scan reports through web-based portal</p>
                        <p><i class="fas fa-check"></i> Contact-free information retrieval</p>
                        <p><i class="fas fa-check"></i> Download-free web application</p>
                        <p><i class="fas fa-check"></i> Adhere to city regulations stress-free</p>
						<div class="buy_now_box"><a href="{{url('register')}}/{{$plan_data[2]->stripe_plan}}" class="btn btn-primary btn-lg btn-shadow uppercase_button buy_now"  name="package">Buy Now</a> </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
		 <section id="about" class="about_sec page-section">
            <div class="container">
               <div class="row mb-5">
                  <div class="col-12">
                     <h2 class="text-center mt-0 title-heading">About Us</h2>
                     <hr class="divider my-4">
                  </div>
               </div>
               <div class="row align-items-center mb-0 mb-lg-5 pb-5">
                  <div class="col-lg-6 col-md-9 mx-auto mb-4 mb-lg-0">
                     <img src="{{asset('img/iPhone_Mockup.jpg')}}" class="img-fluid abt-img" alt="">
                     <div class="img-title">Custom Landing Page</div>
                  </div>
                  <div class="col-lg-6 content">
                     <p>
                        Safe-Trace is a fast, secure, cost-effective solution for businesses to collect patron information per city Covid-19 guidelines and be able to utilize the data for future marketing initiatives. Through a QR Code created specific to your business, guests are routed to a safe and secure, branded landing page requiring simple contact information to be entered. Safe-Trace provides not only an easy, stress-free way to abide by these new parameters, but creates an instant email marketing database for your
                        business at the same time.
                     </p>
                  </div>
               </div>
               <div class="row align-items-center flex-column-reverse flex-lg-row">
                  <div class="col-lg-6 pt-4 pt-lg-0 content">
                     <p>
                        With Safe-Trace,
                        retrieving information is contact-free via a download-free web app. The contact
                        information received can easily be exported through a web-based portal making reporting
                        convenient and always available. Safe-Trace makes retrieving information safe and
                        simple. Guests scan the business QR Code from their personal cell phone via camera
                        mode or a QR Code scanning app. After scanning, guests are brought to your business’s
                        branded landing page where they put in their information, as well as enter additional guest
                        information all on the same page. Once the information required is submitted, the guest
                        receives a green check to show the host/hostess.  Once confirmed, guests are then
                        escorted to their table per reservation.  All Safe-Trace data is stored securely in the cloud
                        and can be exported to a CSV file quick and easily through the business owner's secure
                        online portal. <br><br>
						Reduce labor costs, save time and increase your business outreach with
                        Safe-Trace. <br><br>
						<strong>CONTACT SAFE-TRACE TODAY!</strong>
                     </p>
                  </div>
                  <div class="col-lg-6 col-md-9 mx-auto mb-4 mb-lg-0">
                     <img src="{{asset('img/Computer_Mockup_SafeTrace_V2.png')}}" class="img-fluid abt-img" alt="">
                     <div class="img-title">Secure Online Portal</div>
                  </div>
               </div>
            </div>
         </section>
		 
	 <section class="conact-sec page-section" id="contact">
		<div class="container">
		   <div class="row justify-content-center">
			  <div class="col-md-12">
				 <h2 class="mt-0 title-heading">Contact Us</h2>
				 <p class="sub-title-heading">Email us below to get started today!</p>
			  </div>
		   </div>
		   <div class="row align-items-center">
			  <div class="col-md-6">
				<div id="msg" class="alert hide"></div>
				 <form action="" class="probootstrap-form" id="contactform">
					<div class="form-row">
					   <div class="col-md-6 form-group">
						  <input type="text" id="name" name="name" class="form-control" placeholder="Name">
						  <div class="name_error errors"></div>
					   </div>
					   <div class="col-md-6 form-group">
						  <input type="email" id="email" name="email" class="form-control" placeholder="Email">
						  <div class="email_error errors"></div>
					   </div>
					</div>
					<div class="form-group">
					   <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
					   <div class="subject_error errors"></div>
					</div>
					<div class="form-group">
					   <textarea class="form-control" id="message" name="message" cols="30" rows="4" placeholder="Message"></textarea>
					   <div class="message_error errors"></div>
					</div>
					<div class="form-group">
						<button type="button" id="submitForm" class="btn btn-primary font-weight-semi-bold">Submit</button>
					</div>
				 </form>
			  </div>
			  <div class="col-md-6 ">
				 <div class="conact-detail">
					<!---h3 class="font-weight-semi-bold">Reach out now!</h3--->
					<ul>
					   <li>
						  <i class="fas fa-phone-alt"></i>
						  <a href="tel:504-233-2166">504-233-2166</a>
					   </li>
					   <li>
						  <i class="fas fa-envelope"></i>
						  <a href="mailto:info@safe-trace.com">info@safe-trace.com</a>
					   </li>
					   <li>
						  <i class="fas fa-map-marker-alt"></i>
						  <a href="https://goo.gl/maps/A51bN2vY8bvgnq3Q8" target="_blank"> 1461 N Causeway Blvd, Ste. 13<br>
                              Mandeville, La 70471</a>
					   </li>
					</ul>
				 </div>
			  </div>
		   </div>
		</div>
	 </section>
  </div>
   <footer class="footer">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-6 d-flex align-items-center">
                  <div class="col"><img class="img-fluid f-logo" src="{{asset('img/safetrace-logo-footer.jpg')}}"></div><span class="by">by</span>
                  <div class="col"><a href="https://digi-therm.com/" target="_blank"><img class="img-fluid f-logo" src="{{asset('img/DigiTherm_logo-Footer-1.png')}}"></a></div>
               </div>
               <div class="col-md-6 mt-4 mt-md-0">
                  <span class="copytext">© 2020 SAFE-TRACE. All rights reserved.</span>
               </div>
            </div>
         </div>
      </footer>
	  
 
 
@endsection