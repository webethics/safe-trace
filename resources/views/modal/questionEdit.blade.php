<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header py-1">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
	<form action="{{ url('update-question/') }}/{{ $ques->id }}" method="POST" id="updateQuestion" >
	 @csrf
		
		<div class="form-group form-row-parent">
			<label class="col-form-label">{{ trans('global.question') }}<em>*</em></label>
			<div class="d-flex control-group">
				<textarea name="question" rows="4" cols="50" placeholder="{{ trans('global.question') }}">{{$ques->question}}</textarea>
			 <!-- <input type="text" name="question" value="{{$ques->question}}" class="form-control" placeholder="{{ trans('global.question') }}"> -->									
			</div>	
			<div class="question_error errors"></div>	
		</div>
		
		
		<div class="form-group form-row-parent">
			<label class="col-form-label">{{ trans('global.answer') }}</label>
			<div class="d-flex control-group radioCheck">
			 <!-- <input type="text" name="answer" value="{{$ques->answer}}" class="form-control" placeholder="{{ trans('global.answer') }}"> -->
			 <label title="yes">
                <input type="radio" name="answer" value="yes" @if($ques->answer=='yes') checked @endif>
                Yes
                <img />

            </label>
            <label title="no">
                <input type="radio" name="answer" value="no" @if($ques->answer=='no') checked @endif>
                No
                <img />

            </label>									
			</div>	
			<div class="answer_error errors"></div>	
		</div>
						
		<div class="form-row mt-4">
		<div class="col-md-12 offset-lg-3 offset-xl-2">
		<input id ="ques_id" class="form-check-input" type="hidden" value="{{$ques->id}}">
		<button type="submit" class="btn btn-primary default btn-lg mb-2 mb-sm-0 mr-2 col-12 col-sm-auto">{{ trans('global.submit') }}</button>
		<div class="spinner-border text-primary request_loader" style="display:none"></div>
		</div>
		</div>
		</form>

				</div>
			</div>
		</div>
		