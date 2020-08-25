@extends('layouts.admin')
@section('content')

 <div class="row">
                <div class="col-12">
                    <h1>Listing page</h1>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4">
						  @include('partials.searchRequestForm')		
					<div class="card">
						<div class="card-body">
						<div class="table-responsive" id="tag_container">
					      @include('users.requests.requestPagination')
						</div>
						</div>
					</div>

                </div>
            </div>
<!-- Edit Request modal Form -->
<div class="modal fade modal-right requestEditModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">
</div>

<!-- Assign Request Modal -->
<div class="modal fade modal-right requestAssign"  tabindex="-1" role="dialog"  aria-hidden="true">
 </div>
	
<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>  	
   
@section('addEditRequestjs')
<script src="{{ asset('js/module/request.js')}}"></script>	
@stop
@endsection