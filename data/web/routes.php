<?php
Route::get('/', 'HomeController@showHomePage')->name('index');
Route::view('login', 'login')->name('login')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');

Route::view('register', 'register')->name('register')->middleware('guest');
Route::post('register', 'Auth\RegisterController@sign_up')->name('register');
Route::name('register.')->prefix('register')->group(function () {
	Route::get('confirm/{token}', 'User\VerifyEmail@verify')->name('confirm');
});

Route::post('send-confirmation', 'Auth\RegisterController@sendConfirmation')->name('send-confirmation');
Route::post('check-confirmation', 'Auth\RegisterController@checkConfirmation')->name('check-confirmation');

Route::get('pricing', 'PackageController@showPricing')->name('pricing');
Route::get('pricing/country', 'PackageController@getCountries')->name('pricing.country');
Route::get('pricing/search/{name}', 'PackageController@search')->name('pricing.search');

Route::view('recoveries/new', 'email')->name('recoveries.new');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('checkout', 'OrderController@orderConfirmationView')->name('checkout');
Route::get('confirm-order', 'OrderController@userPackageConfirm')->name('confirm-order');
Route::post('order', 'OrderController@packageOrder')->name('order');
Route::view('order-confirmation', 'order_success')->name('order-confirmation');

Route::get('send-mail', 'OrderController@userPackageConfirm')->name('send-mail');
Route::get('test-reg', 'Auth\RegisterController@sign_up')->name('test-reg');

Route::get('terms', 'HomeController@showTerms')->name('terms');
Route::view('privacy', 'privacy')->name('privacy');

Route::get('contact-us', 'HomeController@showContactPage')->name('contact-us');
Route::post('contact-us', 'HomeController@contact')->name('contact-us');

Route::view('documentation', 'documentation.web-documentation')->name('documentation');
Route::get('faq', 'FaqController@index')->name('faq');