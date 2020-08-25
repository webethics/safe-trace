<div class="user d-inline-block">
		<button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="false">
			@php
			$user = user_data()
			@endphp 
			<span class="name">{{ $user->owner_name}}</span>
			<span class="name-circle">
			{{ substr($user->owner_name,0,1) }}
			<!--img alt="Profile Picture" src="{{ url('img/profile-pic-l.jpg')}}" -->
			</span>
		</button>

		<div class="dropdown-menu dropdown-menu-right mt-3">
			<a class="dropdown-item" href="{{url('account')}}">Account</a>
			<a class="dropdown-item" href="{{url('logout')}}">Sign out</a>
			
		</div>
 </div>