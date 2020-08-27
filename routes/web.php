<?php

Route::get('/', 'User\UsersController@landing_page');


Route::redirect('/login', '/login');

//Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);


Route::group(['prefix' => '','as' => 'user.' ,'namespace' => 'User','middleware' => ['auth']], function () {
	
	
	
    //OTP 
    Route::get('send-otp', 'OtpController@ShowOtpForm');
    Route::post('send_otp', 'OtpController@SendOtp');

    //ROLE 
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

 
    // REQUEST ROUTES
	Route::get('reports',array('as'=>'ajax.pagination','uses'=>'ReportsController@ajaxPagination'));
	Route::post('reports',array('as'=>'ajax.pagination','uses'=>'ReportsController@ajaxPagination'));
    
	
	
	/* Route::get('requests',array('as'=>'ajax.pagination','uses'=>'RequestsController@ajaxPagination'));
	Route::post('requests',array('as'=>'ajax.pagination','uses'=>'RequestsController@ajaxPagination'));
	Route::get('requests/create', 'RequestsController@create')->name('requests.create');
	Route::post('requests/store', 'RequestsController@store')->name('requests.store');
	Route::get('requests/show/{request_id}', 'RequestsController@show')->name('requests.show');
	Route::post('requests/update/{request_id}', 'RequestsController@update'); //Update Request
	Route::post('requests/edit/{request_id}', 'RequestsController@edit'); //Edit Request
	Route::get('report/download/{report_id}', 'RequestsController@donwloadReport'); //Download Report
	
	//Assign request 
	Route::post('request/assignModal', 'RequestsController@requestAssignModal'); //Edit Request
	Route::post('request/assign', 'RequestsController@requestAssignToAnalyst'); //Edit Request
	Route::post('request/clarificationModal', 'RequestsController@clarificationModal'); //Edit Request
	Route::post('request/clarification', 'RequestsController@clarificationRequest'); //Edit Request */
	
    //Route::delete('requests/destroy', 'RequestsController@massDestroy')->name('requests.massDestroy');
	
	
	// USRS ROUTES
	Route::get('users',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxPagination'));
	Route::post('users',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxPagination'));
	
	Route::get('customers',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxCustomerPagination'));
	Route::post('customers',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxCustomerPagination'));
	Route::post('customer/edit/{request_id}', 'UsersController@customer_edit'); //Edit User
	Route::get('customer/show/{request_id}', 'UsersController@customer_show')->name('users.show');
	
	Route::get('guests/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxGuestPagination'));
	Route::post('guests/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxGuestPagination'));
	
	Route::get('customers-listing/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxCustomerListingPagination'));
	Route::post('customers-listing/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@ajaxCustomerListingPagination'));
	Route::post('update-customer/{id}','CustomersController@customerUpdate');
	Route::post('user/enable-disable',array('uses'=>'UsersController@enableDisableUser'));
	Route::post('user/delete_user/{id}', 'UsersController@delete_user')->name('users.delete');
	Route::post('user/delete_customer/{id}', 'CustomersController@delete_customer')->name('customer.delete');
	
	Route::get('user/create', 'UsersController@create')->name('users.create');
	Route::get('user/print/{id}', 'UsersController@print_code')->name('users.print_code');
	Route::post('user/store/{user_id}', 'UsersController@store');
	Route::get('user/show/{request_id}', 'UsersController@show')->name('users.show');
	
	Route::post('user/edit/{request_id}', 'UsersController@edit'); //Edit User
	Route::post('update-profile/{user_id}', 'UsersController@profileUpdate');//UPDATE USER
	Route::post('user/roleDropdown', 'UsersController@roleDropdown');
	
	
	Route::get('account', 'UsersController@account');
	Route::post('openCancelSubscriptionModal', 'UsersController@openCancelSubscriptionModal');  //openCancelSubscriptionModal
	Route::post('cancel_subscription', 'UsersController@cancelSubscription');  //cancel_subscription
	Route::post('openResumeSubscriptionModal', 'UsersController@openResumeSubscriptionModal');  //openCancelSubscriptionModal
	Route::post('resume_subscription', 'UsersController@resumeSubscription');  //cancel_subscription
	
	Route::get('logout', 'UsersController@logout');
	
	Route::post('reset-password/{user_id}', 'UsersController@passwordUpdate');
	//Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
	
	
	// REPORT FROM ANALYST 
	Route::get('business-users',array('as'=>'ajax.pagination','uses'=>'BusinessUsersController@ajaxPagination'));
	Route::get('requests/report/{request_id}', 'RequestsReplyController@reportForm');
	Route::post('report/complete', 'RequestsReplyController@completeReport');
	Route::post('confirmModal', 'CommonController@confirmModal');
	Route::post('delete/reportFile', 'RequestsReplyController@DeleteReportFile');
    Route::post('analyst/report', 'RequestsReplyController@anlystReportSubmit');
    Route::post('status/change', 'RequestsReplyController@changeStatus');
    Route::post('request/showComment', 'RequestsReplyController@showComment');
    Route::post('request/updateComment', 'RequestsReplyController@ModifyComment');
    //Route::get('analysts/requestreply/{request_id}', 'RequestsReplyController@requestReply');
	Route::post('send-notification/{user_id}', 'UsersController@sendEmailNotification');
	
	// Global Setting 
	Route::get('settings',array('uses'=>'SettingsController@index'));
	
	Route::get('site-settings',array('uses'=>'SettingsController@site_settings'));
	Route::post('update/email/{request_id}',array('uses'=>'SettingsController@update_email_settings'));
	Route::post('update/site/{request_id}',array('uses'=>'SettingsController@update_site_settings'));
	/*logo upload*/
	Route::post('uploads/logo/{request_id}',array('uses'=>'SettingsController@uploadLogo'));
	Route::post('fetch/logo/{request_id}',array('uses'=>'SettingsController@getLogo'));
	Route::post('delete/logo/{request_id}',array('uses'=>'SettingsController@deleteLogo'));
	// Custom  Setting 
	Route::post('uploads/custom_logo/{request_id}/{request_type}',array('uses'=>'SettingsController@uploadCustomLogo'));
	Route::post('fetch/custom_logo/{request_id}/{request_type}',array('uses'=>'SettingsController@getCustomLogo'));
	Route::post('delete/custom_logo/{request_id}/{request_type}',array('uses'=>'SettingsController@deleteCustomLogo'));
	Route::post('update/site_settings/{request_id}',array('uses'=>'SettingsController@update_custom_site_settings'));
	//EMAIL TEMPLATE 
	Route::get('emails',array('uses'=>'EmailController@index'));
	Route::get('email/edit/{template_id}',array('uses'=>'EmailController@email_template_edit'));
	Route::post('email/update',array('uses'=>'EmailController@email_template_update'));
	
	
	//Notifiactions
	Route::get('notifications',array('uses'=>'NotificationsController@index'));
	Route::post('getUnreadNotifications',array('uses'=>'NotificationsController@getUnreadNotifications'));
	Route::post('getUnreadNotificationsCount',array('uses'=>'NotificationsController@getUnreadNotificationsCount'));
	
	
	// AUDIT LOGS
	Route::get('audits',array('as'=>'ajax.pagination','uses'=>'AuditsController@ajaxPagination'));
	Route::post('audits',array('as'=>'ajax.pagination','uses'=>'AuditsController@ajaxPagination'));
	Route::post('audit/showEventDetail',array('uses'=>'AuditsController@showEventDetail'));
	
	
	Route::get('report/viewdownload/{report_id}/{attached_id}', 'RequestsController@viewDonwloadReport'); //Download Report
	Route::post('business-users',array('as'=>'ajax.pagination','uses'=>'BusinessUsersController@ajaxPagination'));
	
	
	Route::post('uploads/custom_header/{request_id}',array('uses'=>'SettingsController@uploadCustomHeader'));
	Route::post('fetch/custom_header/{request_id}',array('uses'=>'SettingsController@getCustomHeader'));
	Route::post('delete/custom_header/{request_id}',array('uses'=>'SettingsController@deleteCustomHeader'));
	//Route::get('export_customers',array('uses'=>'BusinessUsersController@export_customers'));
	Route::post('export_customers',array('as'=>'ajax.pagination','uses'=>'BusinessUsersController@exportCustomers'));
	Route::post('export_users_customers/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@exportListingCustomers'));
	Route::post('export_users',array('as'=>'ajax.pagination','uses'=>'UsersController@exportUsers'));

	//Questions
	Route::get('questions',array('uses'=>'QuestionsController@index'));
	Route::get('/question-info/{user_id}', 'QuestionsController@create')->name('question.create');
	//Route::post('/question/create/', 'QuestionsController@question_save');
	Route::post('/question-info/{user_id}',array('uses'=>'QuestionsController@question_save'));
	Route::post('question/edit/{request_id}', 'QuestionsController@question_edit'); 
	Route::post('update-question/{id}','QuestionsController@question_update');
	Route::post('delete-question/{id}', 'QuestionsController@question_delete')->name('question.delete');
	
});


Route::get('register/{plan}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/checkemail', 'Auth\RegisterController@checkemail');
Route::post('/subscription', 'SubscriptionController@create')->name('subscription.create');
Route::get('cron_subscription_package_end', 'SubscriptionController@checkSubscriptionPackage');  //check-subscription-package-end

Route::get('verify/account/{token}', 'User\UsersController@verifyAccount'); //VERIFY ACCOUNT


// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::post('password/reset_new_user_password', 'Auth\ResetPasswordController@reset_new_user_password');

Route::group(['prefix' => '','as' => 'user.' ,'namespace' => 'User'], function () {
	
Route::get('/customer-info/{user_id}', 'CustomersController@customer_info')->name('customer_info');
Route::get('thankyou/{user_id}/{customer_name}/{covid}', 'CustomersController@thankyou')->name('thankyou');
Route::post('/customer-info/{user_id}', 'CustomersController@customer_create')->name('customer_create');
Route::post('/submit-contact/', 'CustomersController@submit_contact')->name('submit_contact');
});

//Route::post('stripe/webhook', '\Laravel\Cashier\WebhookController@handleWebhook');
Route::post('stripe/webhook', '\App\Http\Controllers\WebhookController@handleWebhook');