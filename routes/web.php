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

Route::get('/', function () {
    return view('index');
});

Route::get('/security', 'SecurityController@index');

Route::post('/security/savedata', 'SecurityController@saveData');

Route::post('/security/createreport', 'SecurityController@createReport');

Route::get('/security/process', 'SecurityController@process');

Route::get('/security/testdb', 'SecurityController@testdb');

Route::get('/security/{domain}', 'SecurityController@show');


Route::get('/seo/process', 'SeoController@process');

Route::get('/magento/process', 'MagentoController@process');


Route::get('/aboutus', function () {	
    return view('aboutus');
});


