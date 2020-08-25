<div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header confirm">
		 <div class="header-cont1">
						 <h2>Renew Subscription </h2>
						  </div>
		
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
			<h4 class="modal-title" id="myModalLabel">Are you want to renew  ${{$plan_data->cost}} / month ({{$plan_data->plan_interval}} month subscription) ?. </h4>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary resume_subscription" data-subscription_id="{{$subscription_data->id}}" id=""> Yes</button>
			<div class="spinner-border text-primary loader_resume_subscription" style="display: none;"></div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
         </div>
      </div>
</div>