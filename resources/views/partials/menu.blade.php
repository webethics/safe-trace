<div class="menu">
	<div class="main-menu">
		<div class="scroll">
		 @php    
		 $roleArray = Config::get('constant.role_id');
         $ractive='';  $aactive='';	 $uactive='';	$rcactive=''; $rpcactive='';$cust_active='';$accactive='';
		 $auactive=''; $sactive ='';$emactive ='';$site_sactive ='';
		 @endphp
		 
		 <!-- ACTIVE REQUESTS -->
		 @if(collect(request()->segments())->last()=='requests')
		 @php
	      $ractive ='active'
	     @endphp
		 @endif
		 <!-- ACTIVE USERS -->
		 @if(collect(request()->segments())->last()=='users')
		 @php
	      $uactive ='active'
	     @endphp
		 @endif
		<!-- ACTIVE Customers -->
		 @if(collect(request()->segments())->last()=='customers')
		 @php
	      $cust_active ='active'
	     @endphp
		 @endif


		 @if(collect(request()->segments())->last()=='account')
		 @php
	      $accactive ='active'
	     @endphp
		 @endif
		 <!-- ACTIVE ANALYSTS -->
		 @if(collect(request()->segments())->last()=='business-users')
		 @php
	      $aactive ='active'
	     @endphp
		 @endif
		  <!-- ACTIVE CREATE REQUEST -->
		 @if(collect(request()->segments())->last()=='create')
		 @php
	      $rcactive ='active'
	     @endphp
		 @endif
		 	<!-- ACTIVE REPORTS -->
		 @if(collect(request()->segments())->last()=='reports')
			 @php
			  $rpcactive ='active'
			 @endphp
		 @endif 
		 <!-- ACTIVE REPORTS -->
		 @if(collect(request()->segments())->last()=='settings')
			 @php
			  $sactive ='active'
			 @endphp
		 @endif 
		  @if(collect(request()->segments())->last()=='site-settings')
			 @php
			  $site_sactive ='active'
			 @endphp
		 @endif 
		 <!-- ACTIVE REPORTS -->
		 @if(collect(request()->segments())->last()=='audits')
			 @php
			  $auactive ='active'
			 @endphp
		 @endif 
		 <!-- ACTIVE REPORTS -->
		 @if(collect(request()->segments())->last()=='emails')
			 @php
			  $emactive ='active'
			 @endphp
		 @endif
		 
			<ul class="list-unstyled">
				@if(current_user_role_id()==$roleArray['DATA_ANALYST'])
				  <li class="{{$aactive}}">
					<a href="{{url('/business-users')}}">
						<i class="iconsminds-digital-drawing"></i>
						<span>Customers</span>
					</a>
				 </li>
				 <li class="{{$accactive}}">
					<a href="{{url('/account')}}">
						<i class="iconsminds-user"></i>
						<span>Account</span>
					</a>
				 </li>
				
				 @else
					 
				<li class="{{$uactive}}">
					<a href="{{url('/users')}}">
						<i class="iconsminds-digital-drawing"></i>
						<span>Business Listing</span>
					</a>
				</li>
				<!--li class="{{$cust_active}}">
					<a href="{{url('/customers')}}">
						<i class="iconsminds-digital-drawing"></i>
						<span>Customers </span>
					</a>
				</li-->
				 @endif
				 
				@if(current_user_role_id()==$roleArray['DATA_ANALYST'])
				  <li class="{{$site_sactive}}">
					<a href="{{url('/site-settings')}}">
						<i class="simple-icon-settings"></i> Config
					</a>
				 </li>
				@endif
				 
				@if(current_user_role_id()==$roleArray['DATA_ADMIN'])
				
				 <li class="{{$sactive}}">
					<a href="{{url('settings')}}">
						<i class="simple-icon-settings"></i> Config
					</a>
				</li>
				
				 <li class="{{$emactive}}">
					<a href="{{url('emails')}}">
						<i class="simple-icon-envelope"></i> Email
					</a>
				</li>
				 @endif
				 
			</ul>
		</div>
	</div>    
</div>