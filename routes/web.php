<?php
//
//Route::get('pricing','PackageController@index')->name('pricing');
//Route::view('register','web.register')->name('register');
//Route::post('register','User\SignUp@sign_up')->name('register');
//Route::view('login','web.login')->name('login');
//Route::post('login','User\Authentication@login')->name('login');
//
//Route::name('register.')->prefix('register')->group(function () {
//    Route::get('confirm/{token}', 'User\VerifyEmail@verify')->name('confirm');
//});
//
//Route::view('/','web.home')->name('index');
//Route::get('logout','User\Authentication@logout')->name('logout');
//Route::view('recoveries/new','web.email')->name('recoveries.new');
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
//Route::get('checkout', 'OrderController@orderConfirmationView')->name('checkout');
//Route::get('sendmail', 'OrderController@sendmail')->name('sendmail');
//Route::post('order','OrderController@confirmOrder')->name('order');
//Route::view('order-confirmation','web.order_success')->name('order-confirmation');
//
//Route::name('dashboard.')->prefix('dashboard')->middleware('auth.user')->group(function () {
//    Route::view('/', 'user.dashboard')->name('index');
//    Route::get('logs', 'SmsLogController@index')->name('logs');
//    Route::get('operator', 'DashboardController@networkOperatorChart')->name('operator');
//    Route::get('deliver', 'DashboardController@deliveryRate')->name('deliver');
//});
//
//Route::name('list.')->prefix('list')->middleware('auth.user')->group(function () {
//    Route::view('/','user.contacts.groups')->name('index');
//    Route::get('groups', 'GroupController@getGroups')->name('groups');
//    Route::get('contacts/{id}', 'CotnactController@getContacts')->name('contacts');
//    Route::get('async-group', 'GroupController@asyncGroups')->name('async-group');
//});
//
//Route::name('group.')->prefix('group')->middleware('auth.user')->group(function () {
//    Route::post('create', 'GroupController@create')->name('create');
//    Route::post('update', 'GroupController@update')->name('update');
//    Route::get('delete/{id}', 'GroupController@delete')->name('delete');
//    Route::get('detail','GroupController@groupDetailView')->name('detail');
//});
//
//Route::name('contact.')->prefix('contact')->middleware('auth.user')->group(function () {
//    Route::view('create', 'user.contacts.create_contact')->name('create');
//    Route::post('create', 'CotnactController@create')->name('create');
//    Route::post('edit', 'CotnactController@editContact')->name('edit');
//    Route::get('detail/{id}', 'CotnactController@getContactDetail')->name('detail');
//    Route::get('delete/{id}', 'CotnactController@deleteContact')->name('delete');
//    Route::get('view', 'CotnactController@viewContact')->name('view');
//});
//
//Route::name('rest_api.')->prefix('rest_api')->middleware('auth.user')->group(function () {
//    Route::post('create', 'ApiTokenController@create')->name('create');
//    Route::get('tokens', 'ApiTokenController@getTokens')->name('tokens');
//    Route::get('delete/{id}', 'ApiTokenController@deleteToken')->name('get');
//    Route::view('keys', 'user.api_credential')->name('api_keys');
//});
//
//Route::view('compose', 'user.compose.compose')->middleware('auth.user')->name('compose');
//Route::get('autocomplete', 'CotnactController@async_contacts')->middleware('auth.user')->name('autocomplete');
//
//Route::post('test', 'SendMessageController@sendSms')->name('test');
//
