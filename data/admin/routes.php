<?php

Route::name('admin.')->prefix('admin')->group(function() {

	Route::post('test', 'TestController@test')->name('test');
	Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('login');
	Route::post('/login', 'Auth\AdminLoginController@login')->name('login.submit');

	Route::post('/password/check', 'AdminController@checkOldPassword')->name('password.check');
	Route::post('/password/change', 'AdminController@changePassword')->name('password.change');

	Route::middleware('admin.auth')->group(function() {
		Route::get('/', 'DashboardController@index')->name('index');

		Route::get('/phpinfo', 'DashboardController@getPhpInfo');

		Route::name('dashboard.')->prefix('dashboard')->group(function() {
			Route::get('/', 'DashboardController@index')->name('index');
			Route::get('operators', 'DashboardController@getOperatorsUsage')->name('operators');
			Route::get('deliveries', 'DashboardController@getDeliveryRate')->name('deliveries');
			Route::get('packages', 'DashboardController@getPackageUsage')->name('packages');
			Route::get('registration', 'DashboardController@getUserRegistration')->name('registration');
			Route::get('packagebar', 'DashboardController@getPackageBarChart')->name('packagebar');
		});

		Route::name('order.')->prefix('order')->group(function() {
			Route::get('index/{package?}', 'UserPackageController@index')->name('index');
			Route::get('list', 'UserPackageController@getOrders')->name('list');
			Route::post('update', 'UserPackageController@updateOrder')->name('update');

			Route::post('users', 'UserPackageController@getUsers')->name('users');
			Route::post('packages', 'UserPackageController@getPackages')->name('packages');
			Route::post('create', 'UserPackageController@create')->name('create');

			Route::get('notifications', 'UserPackageController@getNotifications')->name('notifications');
		});

		Route::view('payg-order', 'admin-views.payg-order')->name('payg-order');
		Route::name('payg-order.')->prefix('payg-order')->group(function() {
			Route::get('/orders', 'PaygOrderController@getOrders')->name('orders');
			Route::post('/filter', 'PaygOrderController@filter')->name('filter');
			Route::post('/update', 'PaygOrderController@updateOrder')->name('update');
		});

		Route::name('operator-log.')->prefix('operator-log')->group(function() {
			Route::get('/', 'OperatorLogController@index')->name('index');
			Route::get('/list', 'OperatorLogController@getSmsLogs')->name('list');
			Route::get('/detail/{id}', 'OperatorLogController@getLogDetails')->name('detail');
		});

		Route::name('newsletter.')->prefix('newsletter')->group(function() {
			Route::get('/', 'NewsletterController@index')->name('index');
			Route::post('/send', 'NewsletterController@send')->name('send');
		});

		Route::name('api.')->prefix('api')->group(function() {
			Route::get('/', 'ApiKeyController@index')->name('index');
			Route::get('tokens', 'ApiKeyController@getTokens')->name('tokens');
			Route::post('create', 'ApiKeyController@create')->name('create');
			Route::get('delete/{id}', 'ApiKeyController@deleteToken')->name('delete');
		});

		Route::get('operator', 'OperatorController@viewOperators')->name('operator');
		Route::name('operator.')->prefix('operator')->group(function() {
			Route::get('list/{country_id}', 'OperatorController@getOperators')->name('list');
			Route::get('search/{name}', 'OperatorController@search');
			Route::post('create', 'OperatorController@create')->name('create');
			Route::post('update', 'OperatorController@update')->name('update');
			Route::post('delete/{id}', 'OperatorController@delete')->name('delete');

			Route::get('numbers/{id}', 'OperatorController@getNumbers')->name('numbers');
			Route::post('numbers/create', 'OperatorController@createNumber')->name('numbers.create');
			Route::post('numbers/delete/{id}', 'OperatorController@deleteNumber')->name('numbers.delete');

			Route::get('async-myanmar', 'OperatorController@getMyanmarOperators')->name('async-myanmar');
			Route::get('async-by-country/{country_id}', 'OperatorController@asyncOperators')->name('async-by-country');
		});

		Route::name('user.')->prefix('user')->group(function() {
			Route::get('/index/{user?}', 'UserController@index')->name('index');
			Route::get('/list', 'UserController@getUsers')->name('list');
			Route::get('/view/{id}', 'UserController@view')->name('view');
			Route::get('/order/{id}', 'UserController@showOrders')->name('order');

			Route::post('/create', 'UserController@create')->name('create');
			Route::get('/edit/{id}', 'UserController@edit')->name('edit');
			Route::post('/update', 'UserController@update')->name('update');
			Route::post('/delete', 'UserController@delete')->name('delete');
			Route::post('/block', 'UserController@block')->name('block');

			Route::get('/get-usd', 'UserController@getUsdRate')->name('get-usd');
			Route::post('/update-usd', 'UserController@updateUsd')->name('update-usd');
			Route::post('/password/change', 'UserController@changePassword')->name('password.change');

			Route::post('/add-credit', 'UserController@addCredit')->name('add-credit');

			Route::get('/rate/get', 'UserController@getUserRates')->name('rate.get');
			Route::post('/rate/add', 'UserController@addUserRate')->name('rate.add');
			Route::post('/rate/update', 'UserController@updateUserRate')->name('rate.update');
			Route::post('/rate/delete/{id}', 'UserController@deleteUserRate')->name('rate.delete');

			Route::get('/unpaid/get', 'UserController@getUnpaidInvoice')->name('unpaid.get');
			Route::post('/unpaid/send-invoice', 'UserController@sendInvoice')->name('unpaid.send');
		});

		Route::view('country', 'admin-views.country')->name('country');
		Route::name('country.')->prefix('country')->group(function() {
			Route::get('list', 'CountryController@getCountries')->name('list');
			Route::get('search/{name}', 'CountryController@search');
			Route::post('create', 'CountryController@create')->name('create');
			Route::post('update', 'CountryController@update')->name('update');
			Route::post('status/{id}', 'CountryController@changeStatus')->name('status');
			Route::post('delete/{id}', 'CountryController@delete')->name('delete');

			Route::get('async', 'CountryController@getAsyncCountries')->name('async');

			Route::get('select', 'CountryController@getCountryForSelectBox')->name('select');

		});

		Route::view('package', 'admin-views.package')->name('package');
		Route::name('package.')->prefix('package')->group(function() {
			Route::get('/packages', 'PackageController@getPackages')->name('packages');
			Route::post('/create', 'PackageController@create')->name('create');
			Route::post('/update', 'PackageController@update')->name('update');

			Route::post('/promotion/create', 'PackageController@createPromotion')->name('promotion.create');
			Route::post('/promotion/update', 'PackageController@updatePromotion')->name('promotion.update');
			Route::post('/promotion/delete/{id}', 'PackageController@deletePromotion')->name('promotion.delete');
		});

		Route::view('user-sender', 'admin-views.user-sender')->name('user-sender');
		Route::name('user-sender.')->prefix('user-sender')->group(function() {
			Route::get('list', 'UserSenderController@getSenders')->name('list');
			Route::get('search/{name}', 'UserSenderController@search');
			Route::post('create', 'UserSenderController@create')->name('create');
			Route::post('update', 'UserSenderController@update')->name('update');
			Route::post('delete/{id}', 'UserSenderController@delete')->name('delete');
		});

		Route::view('sender', 'admin-views.sender')->name('sender');
		Route::name('sender.')->prefix('sender')->group(function() {
			Route::get('list', 'SenderController@getSenders')->name('list');
			Route::get('search/{name}', 'SenderController@search');
			Route::post('create', 'SenderController@create')->name('create');
			Route::post('update', 'SenderController@update')->name('update');
			Route::post('delete', 'SenderController@delete')->name('delete');

			Route::name('users.')->prefix('users')->group(function() {
				Route::get('{sender_id}', 'SenderController@getUsers');
				Route::post('create', 'SenderController@createSenderUser')->name('create');
				Route::post('delete', 'SenderController@deleteSenderUser')->name('delete');
			});

			Route::get('name/{id}', 'SenderController@getSenderName')->name('name');

			Route::view('{id}', 'admin-views.sender-detail')->name('sender-detail');
			Route::name('sender-detail.')->prefix('sender-detail')->group(function() {
				Route::get('list', 'SenderController@getSenderDetails')->name('list');
				Route::get('search/{name}', 'SenderController@searchSenderDetails');
				Route::post('create', 'SenderController@createSenderDetail')->name('create');
				Route::post('update', 'SenderController@updateSenderDetail')->name('update');
				Route::post('delete', 'SenderController@deleteSenderDetail')->name('delete');
			});
		});

		Route::middleware('admin.level3')->group(function() {
			Route::name('purchase.')->prefix('purchase')->group(function() {
				Route::get('/', 'PurchaseController@index')->name('index');
				Route::get('/list', 'PurchaseController@getPurchaseList')->name('list');

				Route::post('/create', 'PurchaseController@create')->name('create');
				Route::get('/edit/{id}', 'PurchaseController@edit')->name('edit');
				Route::post('/update', 'PurchaseController@update')->name('update');
				Route::post('/delete', 'PurchaseController@delete')->name('delete');

				Route::get('/balance', 'PurchaseController@getBalance')->name('balance');
			});

			Route::name('intl-purchase.')->prefix('intl-purchase')->group(function() {
				Route::get('/', 'IntlPurchaseController@index')->name('index');
				Route::get('/list', 'IntlPurchaseController@getPurchaseList')->name('list');

				Route::post('/create', 'IntlPurchaseController@create')->name('create');
				Route::get('/edit/{id}', 'IntlPurchaseController@edit')->name('edit');
				Route::post('/update', 'IntlPurchaseController@update')->name('update');
				Route::post('/delete', 'IntlPurchaseController@delete')->name('delete');
			});
		});

		Route::middleware('admin.level4')->group(function() {
			Route::view('list', 'admin-views.list')->name('list');
			Route::name('list.')->prefix('list')->group(function() {
				Route::get('/show', 'AdminController@getAdmins')->name('show');
				Route::get('search/{name}', 'AdminController@search');

				Route::post('/create', 'AdminController@create')->name('create');
				Route::post('/update', 'AdminController@update')->name('update');
				Route::post('/delete/{id}', 'AdminController@delete')->name('delete');
			});

			Route::view('telecom','admin-views.telecoms')->name('telecom');
			Route::name('telecom.')->prefix('telecom')->group(function() {
				Route::get('list', 'TelecomController@getTelecoms')->name('list');
				Route::post('create', 'TelecomController@create')->name('create');
				Route::post('update', 'TelecomController@update')->name('update');
				Route::post('delete/{id}', 'TelecomController@delete')->name('delete');
			});
		});

		Route::get('telecom/select', 'TelecomController@asyncTelecom')->name('select');

		Route::get('/compose', 'MessageController@compose')->name('compose');
		Route::post('/message', 'MessageController@sendSms')->name('message');
		Route::post('schedule-message', 'MessageController@createMessageSchedule')->name('schedule-message');

		Route::get('schedule', 'ScheduleController@index')->name('schedule.index');
		Route::get('schedule/cancel', 'ScheduleController@cancelSchedule')->name('schedule.cancel');

		Route::get('log/detail', 'OperatorLogController@getLogById')->name('log.detail');

		Route::get('/logout', 'Auth\AdminLoginController@logout')->name('logout');

		Route::view('gateway','admin-views.gateway')->name('gateway');
		Route::name('gateway.')->prefix('gateway')->group(function() {
			Route::get('list', 'GatewayController@getGateways')->name('list');
			Route::post('create', 'GatewayController@create')->name('create');
			Route::post('update', 'GatewayController@update')->name('update');
			Route::post('delete/{id}', 'GatewayController@delete')->name('delete');
		});

		Route::view('default-endpoint','admin-views.default-endpoint')->name('default-endpoint');
		Route::name('default-endpoint.')->prefix('default-endpoint')->group(function() {
			Route::get('list', 'DefaultEndpointController@endpointList')->name('list');
			Route::post('set-point', 'DefaultEndpointController@setDefaultEndpoint')->name('set-point');
			Route::post('create', 'DefaultEndpointController@create')->name('create');
			Route::post('update', 'DefaultEndpointController@update')->name('update');
		});

		Route::view('article','admin-views.faq.list')->name('article');
		Route::name('article.')->prefix('article')->group(function() {
			Route::get('list', 'ArticleController@getArticles')->name('list');
			Route::view('create','admin-views.faq.create')->name('create');
			Route::get('edit/{article_id}','ArticleController@editView')->name('edit');
			Route::post('create-article','ArticleController@create')->name('create-article');
			Route::post('update-article','ArticleController@update')->name('update-article');
			Route::post('delete/{id}', 'ArticleController@delete')->name('delete');
		});

		Route::get('terms', 'TermsController@index')->name('terms');
		Route::name('terms.')->prefix('terms')->group(function() {
			Route::get('/edit', 'TermsController@edit')->name('edit');
			Route::post('/update', 'TermsController@update')->name('update');
		});

		Route::name('setting.')->prefix('setting')->group(function() {
			Route::view('/','admin-views.setting')->name('index');
			Route::post('default','DefaultSettingController@manage')->name('default');
			Route::get('default','DefaultSettingController@index')->name('default');
		});
	});
});

Route::name('dashboard-user.')->prefix('dashboard-user')->group(function() {
	Route::get('/login', 'Auth\LogsLoginController@showLoginForm')->name('login');
	Route::post('/login', 'Auth\LogsLoginController@login')->name('login.submit');

	Route::middleware('admin.log')->group(function() {
		Route::get('/', 'UserLogs\LogsController@index')->name('index');
		Route::get('list', 'UserLogs\LogsController@getUsers')->name('list');
		Route::get('view/{id}', 'UserLogs\LogsController@viewUserLog')->name('view');
		Route::get('logs', 'UserLogs\LogsController@getLogs')->name('logs');
		Route::get('detail/{id}', 'UserLogs\LogsController@getLogDetail')->name('detail');

		Route::view('search', 'admin-view-logs.search')->name('search');
		Route::post('search', 'UserLogs\SearchController@search');


		Route::get('logout', 'Auth\LogsLoginController@logout')->name('logout');
	});
});