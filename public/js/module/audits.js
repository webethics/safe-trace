/*==============================================
	OPEN CLARIFICATION MODAL (POPUP) 
============================================*/
$(document).on('click', '.auditModalOpen' , function() {
	
	var audit_id = $(this).data('audit_id');
	
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/audit/showEventDetail',
        data: {_token:csrf_token,audit_id:audit_id},
        success: function(data) {
			if(data.success){
				$('.showAuditModal').html(data.data);
				$('.showAuditModal').modal('show');
				$('.errors').html('');
			}else{
				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
    });
})

/*==============================================
	SEARCH FILTER FORM 
============================================*/
$(document).on('submit','#AuditsearchForm', function(e) {
	
    e.preventDefault(); 
	$('.search_spinloder').css('display','inline-block');
    $.ajax({
        type: "POST",
		//dataType: 'json',
        url: base_url+'/audits',
        data: $(this).serialize(),
        success: function(data) {
			 $('.search_spinloder').css('display','none');
             //start date and end date error 
			 if(data=='date_error'){
				notification('Error','Start date not greater than end date.','top-right','error',4000);	
			}else{
             // Set search result
			 $("#tag_container").empty().html(data); 
			}	
        },
		error :function( data ) {}
    });
});


	