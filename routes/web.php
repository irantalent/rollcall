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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('rollcall/arrive', 'RollcallController@arriveNow')->name('rollcall.arrive');
Route::get('rollcall/depart', 'RollcallController@departNow')->name('rollcall.depart');
Route::post('rollcall/store-at', 'RollcallController@storeAt')->name('rollcall.storeAt');
Route::resource('rollcall', 'RollcallController');

Route::get('test', function () {

});

Route::get('payslip/calculate-time-for-me', 'PayslipController@calculateMyPayslipHoursOfCurrentMonth')->name('payslip.calculateForMeTemp');

