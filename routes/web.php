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

Route::get('/', function () {
    return view('layout/main');
});
//authController
// Route::group(['middleware' => ['guest']], function(){
Route::get('/register', 'authController@register');
Route::post('/simpanRegis','authController@simpanRegis');
Route::get('/login', 'authController@login');
Route::post('/ceklogin', 'authController@ceklogin');
Route::get('/logout', 'authController@logout');

// });
//koorController
///Koordinator PKK Live
Route::get('/jadwal_vltLive', 'koorController@jadwal_vltLive');
Route::get('/validasi_presensiLive', 'koorController@validasi_presensiLive');
Route::get('/home_koorLive', 'koorController@home_koorLive');
Route::get('/tambah_vltLive', 'koorController@tambah_vltLive');
Route::get('/edit_vltLive', 'koorController@edit_vltLive');
Route::get('/tambah_jdwlLive', 'koorController@tambah_jdwlLive');
Route::get('/edit_jdwlLive', 'koorController@edit_jdwlLive'); 

Route::post('/simpanVltLive', 'koorController@simpanVltLive');
Route::post('/cekEmail', 'koorController@cekEmail');


///koordinator Creative
Route::get('/home_koorcrv', 'koorcreativeController@home_koorcrv');
Route::get('/task_mn', 'koorcreativeController@task_mn');
Route::get('/tambah_task', 'koorcreativeController@tambah_task');
Route::get('/edit_task', 'koorcreativeController@edit_task');

///koordinator konseling
Route::get('/jadwal_vltkonsul', 'koorkonsulController@jadwal_vltkonsul');
Route::get('/validasi_presensikonsul', 'koorkonsulController@validasi_presensikonsul');
Route::get('/home_koorkonsul', 'koorkonsulController@home_koorkonsul');
Route::get('/tambah_vltkonsul', 'koorkonsulController@tambah_vltkonsul');
Route::get('/edit_vltkonsul', 'koorkonsulController@edit_vltkonsul');
Route::get('/tambah_jdwlkonsul', 'koorkonsulController@tambah_jdwlkonsul');
Route::get('/edit_jadwalkonsul', 'koorkonsulController@edit_jadwalkonsul');

///Koordinator TIK
Route::get('/jadwal_vltTik', 'koortikController@jadwal_vltTik');
Route::get('/validasi_presensiTik', 'koortikController@validasi_presensiTik');
Route::get('/home_koorTik', 'koortikController@home_koorTik');
Route::get('/tambah_vltTik', 'koortikController@tambah_vltTik');
Route::get('/edit_vltTik', 'koortikController@edit_vltTik');
Route::get('/tambah_jdwlTik', 'koortikController@tambah_jdwlTik');
Route::get('/edit_jdwlTik', 'koortikController@edit_jdwlTik');

//vltController
Route::get('/home_vlt', 'vltController@home_vlt');
Route::get('/home_vltcreative', 'vltController@home_vltcreative');
Route::get('/profile_vlt', 'vltController@profile_vlt');

//kepala LPKKSK
Route::get('/home_kepalaPKK', 'KPLController@home_kepalaPKK');
