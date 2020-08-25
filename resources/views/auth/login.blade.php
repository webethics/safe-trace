@extends('layouts.app')
@section('content')
			<div class="row h-100">
                <div class="col-12 col-md-8 col-lg-6 mx-auto my-auto">
                    <div class="card auth-card">
                        <div class="form-side">
							<span class="logo_image d-block mb-3"><a href="{{url('/')}}"><img src="{{asset('img/logo.png')}}"></a></span>
                            <h6 class="mb-4">{{ trans('global.login') }}</h6>
							@if(Session::has('message'))
							<div class="alert alert-success">
							{{ Session::get('message') }}
							@php
							Session::forget('message');
							@endphp
							</div>
							@endif


							@include('flash-message')	
                            <form method="POST" action="{{ route('login') }}" class="frm_class">
							 {{ csrf_field() }}
                                <label class="has-float-label 	@if(!\Session::has('errors')) form-group mb-4 @else labelcls @endif">
                                  <input name="email" type="text" class="form-control">
                                    <span>{{ trans('global.E-mail') }}</span>
                                </label>
								<div class="error_margin"><span class="error" >  {{ $errors->first('email')  }} </span></div>

                                <label class="has-float-label @if(!\Session::has('errors')) form-group mb-4 @else labelcls @endif">
                                     <input name="password" type="password" class="form-control">
                                    <span>{{ trans('global.login_password') }}</span>
                                </label>
								<div class="error_margin"><span class="error" >  {{ $errors->first('password')  }} </span></div>
								
								{{-- <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('register') }}">{{ trans('global.register') }}</a>
                                    
								</div>	--}}
                                 <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('password.request') }}">{{ trans('global.forgot_password') }}</a>
                                    
									<input type="submit" class="btn btn-primary btn-lg btn-shadow uppercase_button" value="{{ trans('global.login') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

@endsection