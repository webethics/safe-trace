@extends('layouts.admin')
@section('content')

  <div class="row">
                <div class="col-12">
                    <h1>Audit Report</h1>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4">
					<div class="row"></div>
					@include('partials.searchAuditForm')							
					<div class="card">
						<div class="card-body">
						<div class="table-responsive" id="tag_container">
							 @include('users.audits.auditPagination')	
						</div>
						</div>
					</div>

                </div>
            </div>
			
<div class="modal fade modal-right showAuditModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalRight" aria-hidden="true">
			 </div>
@section('auditJs')
<script src="{{ asset('js/module/audits.js')}}"></script>	
@stop
@endsection
