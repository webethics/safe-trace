<table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col">{{ trans('global.question') }}</th>
		<th scope="col">{{ trans('global.answer') }}</th>								
		<th scope="col">{{ trans('global.actions') }}</th>								
		</tr>
	</thead>
	<tbody>
	 @if(is_object($questions) && !empty($questions) && $questions->count())
	 
	  @foreach($questions as $key => $value)
		<tr data-user-id="{{ $value->id }}"  class="ques_row_{{$value->id}}" >
			<td id="ques_{{$value->id}}" >{!! Str::limit($value->question, 50) !!}</td>
			<td id="ans_{{$value->id}}">{!! Str::limit($value->answer, 50) !!}</td>
			<td>
				<a title="Edit Question" href="javascript:void(0)" class="editQuestion" data-ques_id="{{ $value->id }}"><i class="simple-icon-note"></i></a>
				
				<a title="Delete"  data-id="{{$value->id}}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Question?"  data-left_button_name ="Yes" data-left_button_id ="delete_question" data-left_button_cls="btn-primary" class="open_confirmBox" href="javascript:void(0)" data-user_id="{{ $value->id }}"><i class="simple-icon-trash"></i></a>
			</td>	
		</tr>
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table> 
	<!------------ Pagination -------------->
		@if(is_object($questions) && !empty($questions) && $questions->count()) 
		 {!! $questions->render() !!}  
		 @endif	