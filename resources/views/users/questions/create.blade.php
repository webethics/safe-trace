@extends('layouts.admin')
@section('content')
@section('profilepageJsCss')
<script src="{{ asset('js/module/jquery.account.js')}}"></script>
<script src="{{ asset('js/module/user.js')}}"></script>
<script type="text/javascript">

$(document).ready(function() {
	
    var max_fields      = 8;
    var wrapper         = $(".copy");
    var add_button      = $(".add_form_field");
 
    var x = 1;
	
    $(add_button).click(function(e){
		
        e.preventDefault();
		 if(x < max_fields){
            x++;
            $(wrapper).append('<div class="control-group"><div class="form-group"><input type="text" class="form-control quesField" name ="question['+x+']" placeholder="*Question:"><div class="question.'+x+'_error errors">{{ $errors->first("question['+x+']")  }}</div></div><div class="form-group radioCheck"><span class="answer">Answer :-</span><label title="yes"><input type="radio" name="answer['+x+']" value="yes" checked>Yes<img /></label><label title="no"><input type="radio" name="answer['+x+']" value="no">No<img /></label></div><button class="btn btn-danger remove delete" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button></div>'); //add input box
			$('#total_member').val(x);
        }
  else
  {
		alert('You Reached the limits')
  }
    });
 
    $(wrapper).on("click",".delete", function(e){
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});

</script>
@stop
<div class="row">
	<div class="col-12">
		<h1>{{trans('global.add_question')}}</h1>
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="row">	
	<div class="col-md-12">		
		<div class="form-wrap">
			<div id="msg" class="alert hide"></div>
			<form method="POST" name="create_question" data-id="{{$user_id}}" id="create_question" class="frm_class">
				 {{ csrf_field() }}
				<div class="control-group after-add-more">
					<div class="form-group">
						<input type="text" class="form-control quesField" name ="question[1]" placeholder="*Questions:">
						<div class="question.1_error errors"></div>
					</div>
					<!-- <div class="form-group">
						<input type="text" class="form-control" name ="answer[1]" placeholder="*Answer:">
						<div class="answer.1_error errors"></div>
					</div> -->
					<div class="form-group radioCheck"><span class="answer">Answer :-</span>
                        <label title="yes">
                            <input type="radio" name="answer[1]" value="yes" checked>
                            Yes
                            <img />

                        </label>
                        <label title="no">
                            <input type="radio" name="answer[1]" value="no">
                            No
                            <img />

                        </label>
                    </div>
				</div>
				<div class="copy">
				</div>					  
				<div class="form-group addMore">
					<a class="add-more add_form_field" href="javascript:void(0)">+ Add More Question</a>
				</div>
				<div class="form-group">
					<button id="create_ques" type="button" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Submit</button>
				</div>
	
					<!-- <button id="create_cust" type="button" class="btn btn-primary">Submit</button> -->
			</form>
		</div>	
	</div>
</div>
@endsection