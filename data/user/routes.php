<?php
Route::middleware(['auth:web', 'user.obsolete'])->group(function () {
	Route::get('logout', 'Auth\Authentication@logout')->name('logout');

	Route::name('dashboard.')->prefix('dashboard')->group(function () {
		Route::get('/', 'DashboardController@showDashboard')->name('index');

		Route::get('operator', 'DashboardController@networkOperatorChart')->name('operator');
		Route::get('deliver', 'DashboardController@deliveryRate')->name('deliver');
		Route::get('product_usage', 'DashboardController@sourceRate')->name('product_usage');
		Route::get('sms_usage', 'DashboardController@monthlyChart')->name('sms_usage');
		Route::get('last_week', 'DashboardController@lastWeekChart')->name('last_week');
	});

	Route::middleware('user.accept_terms')->group(function () {
		Route::view('compose', 'compose')->name('compose');

		Route::get('logs', 'SmsLogController@index')->name('logs');
		Route::get('filter-logs', 'SmsLogController@smsLogsFilterByDate')->name('filter-logs');
		Route::get('log/detail', 'SmsLogController@getLogById')->name('log.detail');
		Route::get('log-details', 'SmsLogController@logDetailView')->name('log-details');
		Route::get('export-logs', 'ExportFileController@exportSmsLogDetails')->name('export-logs');

		Route::name('list.')->prefix('list')->group(function () {
			Route::get('groups', 'GroupController@getGroups')->name('groups');
			Route::get('contacts/{id}', 'GroupController@getGroupWithContacts')->name('contacts');
			Route::get('async-group', 'GroupController@asyncGroups')->name('async-group');
		});

		Route::name('group.')->prefix('group')->group(function () {
			Route::post('create', 'GroupController@create')->name('create');
			Route::post('update', 'GroupController@update')->name('update');
			Route::get('delete/{id}', 'GroupController@delete')->name('delete');
			Route::get('detail', 'GroupController@groupDetailView')->name('detail');
			Route::post('delete-contacts', 'GroupController@deleteContacts');
		});

		Route::name('address-book.')->prefix('address-book')->group(function () {
			Route::view('/', 'contacts.group-index')->name('index');
		});

		Route::name('contact.')->prefix('contact')->group(function () {
			Route::get('/', 'ContactController@showContactList')->name('index');

			Route::get('view', 'ContactController@viewContact')->name('view');
			Route::view('create', 'contacts.create-contact')->name('create');
			Route::post('create', 'ContactController@create')->name('create');
			Route::post('edit', 'ContactController@editContact')->name('edit');

			Route::get('detail/{id}', 'ContactController@getContactDetail')->name('detail');
			Route::get('delete/{id}', 'ContactController@deleteContact')->name('delete');

			Route::get('list', 'ContactController@getAllContacts')->name('list');

			Route::get('filter', 'ContactController@filterContactByPhoneAndName')->name('filter');

			Route::view('import', 'contacts-import')->name('import');
			Route::post('import', 'ExportFileController@importContactsFile')->name('import');
			Route::get('export', 'ExportFileController@exportContactByGroup')->name('export');
		});

		Route::name('rest_api.')->prefix('rest_api')->group(function () {
			Route::view('keys', 'api_credential')->name('api_keys');
			Route::post('create', 'ApiTokenController@create')->name('create');
			Route::get('tokens', 'ApiTokenController@getTokens')->name('tokens');
			Route::get('delete/{id}', 'ApiTokenController@deleteToken')->name('get');
		});

		Route::get('autocomplete', 'ContactController@async_contacts')->name('autocomplete');
		Route::get('country', 'ContactController@getCountries')->name('country');
		Route::get('sender', 'ContactController@getSenders')->name('sender');

		Route::get('invoices', 'InvoiceController@getInvoiceList')->name('invoices');

		Route::name('v1.')->middleware('auth:web')->prefix('v1')->group(function () {
			Route::post('message', 'SendMessageController@sendSms')->name('message');
		});

		Route::view('api/documentation', 'documentation.index')->name('api.documentation');

		Route::post('test', 'SendMessageController@sendSms')->name('test');

		Route::get('download/{invoice_id}', 'InvoiceController@downloadInvoice')->name('download');
		Route::get('download-payg/{invoice_id}', 'InvoiceController@downloadPaygInvoice')->name('download.payg');

		Route::get('buy', 'PackageController@showPricing')->name('buy');

		Route::get('user-checkout', 'OrderController@orderConfirmationView')->name('user-checkout');
		Route::post('confirm-order', 'OrderController@confirmOrder')->name('confirm-order');
		Route::view('order-success', 'success-order')->name('order-success');

		Route::get('resend-mail', 'Auth\ResendEmail@resendEmail')->name('resend-mail');
		Route::get('check-verifyemail', 'Auth\ResendEmail@checkAlreadyVerifyEmail')->name('check-verifyemail');


		Route::post('schedule-message', 'SendMessageController@createMessageSchedule')->name('schedule-message');
		Route::get('schedule', 'ScheduleController@index')->name('schedule.index');
		Route::get('schedule/cancel', 'ScheduleController@cancelSchedule')->name('schedule.cancel');
		Route::view('user/profile', 'profile')->name('user.profile');
		Route::get('user/get-profile', 'UserController@getUserProfile')->name('user.get-profile');
		Route::post('user/profile/edit', 'UserController@updateProfile')->name('user.profile.edit');

		Route::view('user/setting', 'setting')->name('user.setting');
		Route::get('user/get-setting', 'UserController@getUserSetting')->name('user.get-setting');
		Route::post('user/setting/change', 'UserController@updateSetting')->name('user.setting.change');

		// Route::post('/user/password/check', 'UserController@checkOldPassword')->name('user.password.check');
		Route::post('/user/password/change', 'UserController@changePassword')->name('user.password.change');

		Route::post('/user/email/change', 'UserController@changeEmail')->name('user.email.change');
		Route::get('/user/email/update', 'UserController@updateEmail')->name('user.email.update');
	});

	Route::get('/terms/accept', 'UserController@acceptTerms')->name('user.terms.accept');
});