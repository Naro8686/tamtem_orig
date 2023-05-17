<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// переход по реферальной ссылке
// логироввание dadata
Route::group(['prefix' => 'ref', 'as' => 'organization-statistic.'], function(){
    Route::get('{data}', 'Client\Api\v1\Organization\OrganizationStatisticController@referalLinkEnter')->name('referal-url-enter');
});

// пришло письмо с токеном по сбросу пароля
Route::get('/passwordreset/{token}', 'Client\ClientController@passwordResetConfirm')->name('password.reset.confirm'); 

// Admin
Route::get('/admin/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.loginform');
Route::post('/admin/login', 'Admin\Auth\LoginController@login')->name('admin.login');
Route::post('/admin/logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

Route::middleware(['auth'])->group(function () { 
    Route::get('/admin/{any?}', 'Admin\AdminController@index')->where('any', '.*')->name('admin');
});

// Site
Route::get('/register/{token}', 'Client\ClientController@registerConfirm')->where('any', '.*')->name('register.confirm');
Route::get('/unsubscribe', 'Client\Api\v1\UserNoticeSubscribeController@unsubscribeNoticeFromMail'); // отписаться от рассылок из письма
Route::get('/about', 'Client\ClientController@about')->name('about.page');
Route::get('/contact', 'Client\ClientController@contact')->name('contact.page');
Route::get('/faq', function(){    
    return view('client.layouts.faq');
})->name('faq.page');
Route::get('/price', function(){    
    return view('client.layouts.price');
})->name('faq.page');
Route::get('/postavschic', function(){    
    return view('client.layouts.postavschic');
})->name('faq.page');

Route::get('/{any?}', 'Client\ClientController@index')->where('any', '.*')->name('client');