<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/peta-sebaran', 'PetaSebaranController@index');
Route::get('/peta-sebaran/search', 'PetaSebaranController@search')->name('peta-search');

Route::get('/', 'HomeController@index');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/gradient', 'HomeController@gradient');
Route::resource('/admin/pasien','PasienController');

Route::prefix('admin')->group(function () {
  
    Route::get('/pasien', 'PasienController@index')->name('pasien');
    Route::post('/pasien', 'PasienController@store')->name('pasien-store');
    Route::get('/pasien/{tanggal}', 'PasienController@show')->name('pasien-show');
    Route::put('/pasien/{id}', 'PasienController@update')->name('pasien-update');
    Route::delete('/pasien/{id}', 'PasienController@destroy')->name('pasien-delete');

    Route::get('/kabupaten', 'KabupatenController@index')->name('kabupaten');

    Route::get('/pasien-per-kelurahan', 'PasienPerKelurahanController@index')->name('pasien-per-kelurahan');
    Route::post('/pasien-per-kelurahan', 'PasienPerKelurahanController@store')->name('pasien-per-kelurahan-store');
    Route::get('/pasien-per-kelurahan/{tanggal}', 'PasienPerKelurahanController@show')->name('pasien-per-kelurahan-show');
    Route::put('/pasien-per-kelurahan/{id}', 'PasienPerKelurahanController@update')->name('pasien-per-kelurahan-update');
    Route::delete('/pasien-per-kelurahan/{id}', 'PasienPerKelurahanController@destroy')->name('pasien-per-kelurahan-delete');
    Route::get('/pasien-per-kelurahan/getKecamatan/{kabupaten}', 'PasienPerKelurahanController@getKecamatan')->name('pasien-kecamatan');
    Route::get('/pasien-per-kelurahan/getKelurahan/{kecamatan}', 'PasienPerKelurahanController@getKelurahan')->name('pasien-kelurahan');
});
