@extends('layouts.admin')
@section('content')
 <div class="row">
                <div class="col-12">
                    <h1>Customers of {{$business_name->business_name}}</h1>
                    <div class="separator mb-5"></div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 mb-4">
				
				    
					@include('partials.searchBusinessUsersForm')				
					<div class="card">
						<div class="card-body">
						<div class="table-responsive"  id="tag_container">
							 @include('users.customers.customersPagination')
						</div>
						</div>
					</div>

                </div>
            </div>
<div class="modal fade modal-right customerEditModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">
			 </div>
			  <div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('userJs')
<script src="{{ asset('js/module/user.js')}}"></script>	
@stop
@endsection