<?php

// Route::middleware('throttle:120,1')->group(function () {
	Route::middleware('auth.send')->name('v1.')->prefix('v1')->group(function () {
		Route::post('message', 'Api\User\UserSendSms@sendSms')->name('message');
		Route::get('messages', 'Api\User\GetMessages@getMessages')->name('messages');
		Route::get('contacts', 'Api\User\GetContacts@getContacts')->name('contacts');

		Route::get('balance', 'Api\User\GetBalance@getBalance')->name('balance');
	});

	Route::get('validate', 'UserController@checkUserName')->name('validate');

	Route::middleware(['admin.sms'])->name('send.')->prefix('send')->group(function () {
		Route::post('message', 'Api\Admin\AdminSendSms@sendSms')->name('message');
	});

	Route::post('send-phone', 'Api\User\UserSendSms@testSomePhoneNumber')->name('send-phone')->middleware('auth.send');

	Route::get('send-international', 'UserController@internationalSms')->name('send-international');
// });


Route::name('operator.')->prefix('operator')->group(function () {
	Route::post('logs' ,'ReceiptSmsLogFromOperator@receiptLog')->name('logs')->middleware('nhn.auth');
	Route::get('logs', 'ReceiptSmsLogFromOperator@receiptLog')->name('logs')->middleware('nhn.auth');

	Route::post('data', 'ReceiptSmsLogFromOperator@dexatelData')->name('data')->middleware('dexatel.auth');

	
	// Route::post('send-nhn', 'ReceiptSmsLogFromOperator@sendNhn');
	// Route::post('send-dexatel', 'ReceiptSmsLogFromOperator@sendDexatel');
});