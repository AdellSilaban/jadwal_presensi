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
return view('/login');
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
Route::get('/jadwal_vlt', 'koorController@jadwal_vlt');
Route::get('/validasi_presensi', 'koorController@validasi_presensi');
Route::get('/home_koor', 'koorController@home_koor');
Route::get('/tambah_vlt', 'koorController@tambah_vlt');
Route::get('/edit_vlt/{vol_id}', 'koorController@edit_vlt')->name('edit_vlt');
Route::put('/updateVlt/{vol_id}', 'koorController@updateVlt')->name('updateVlt'); 
Route::post('/simpanVlt', 'koorController@simpanVlt');
Route::delete('/hapus_vlt/{vol_id}', 'koorController@hapus_vlt')->name('hapus_vlt');

//jadwal
Route::get('/tambah_jadwal', 'koorController@tambah_jadwal');
Route::get('/edit_jadwal/{jadwal_id}', 'koorController@edit_jadwal')->name('edit_jadwal'); 
Route::put('/updateJadwal/{jadwal_id}', 'koorController@updateJadwal')->name('updateJadwal'); 
Route::delete('/hapus_jdwl/{jadwal_id}', 'koorController@hapus_jdwl')->name('hapus_jdwl');
Route::post('/simpanjadwal', 'koorController@simpanjadwal');



//mengirim email
Route::post('/cekEmail', 'koorController@cekEmail');
Route::get('/kirimEmail/{vol_id}', 'koorController@kirimEmail')->name('kirimEmail');

// Route untuk menampilkan form reset password (gunakan salah satu nama yang konsisten)
Route::get('/reset-password/{token}', 'koorController@reset_now')->name('reset_password'); // Gunakan 'reset_password' sebagai nama yang umum untuk tautan email

// Route untuk menampilkan kembali form reset password jika token tidak valid
Route::get('/reset-password/invalid', 'koorController@showInvalidTokenForm')->name('reset'); // Route terpisah untuk token tidak valid

// Route untuk memproses pengiriman form reset password
Route::post('/updatePassword', 'vltController@updatePassword')->name('updatePassword');

///koordinator Creative
Route::get('/home_koorcrv', 'koorController@home_koorcrv');
Route::get('/task_mn', 'koorController@task_mn');
Route::get('/tambah_task', 'koorController@tambah_task');
Route::post('/simpan_task', 'koorController@simpan_task');
Route::get('/edit_task/{tugas_id}', 'koorController@edit_task')->name('edit_task'); 
Route::put('/updateTask/{tugas_id}', 'koorController@updateTask')->name('updateTask'); 
Route::delete('/hapus_task/{tugas_id}', 'koorController@hapus_task')->name('hapus_task');

Route::get('/tambah_vltcrv', 'koorController@tambah_vltcrv');
Route::post('/simpanVltcrv', 'koorController@simpanVltcrv');
Route::get('/edit_vltcrv/{vol_id}', 'koorController@edit_vltcrv')->name('edit_vltcrv');
Route::put('/updateVltcrv/{vol_id}', 'koorController@updateVltcrv')->name('updateVltcrv'); 
Route::delete('/hapus_vltcrv/{vol_id}', 'koorController@hapus_vltcrv')->name('hapus_vltcrv');

//vltController
Route::get('/loginVol', 'vltController@loginVol')->name('loginVol');
Route::post('/cekloginVol', 'vltController@cekloginVol')->name('cekloginVol');
Route::get('/home_vlt', 'vltController@home_vlt')->name('home_vlt');
Route::get('/home_vltcreative', 'vltController@home_vltcreative');
Route::get('/profile_vlt', 'vltController@profile_vlt');
Route::get('/checkIn/{jadwal_id}', 'vltController@checkIn')->name('checkIn');
Route::get('/checkOut/{jadwal_id}', 'vltController@checkOut')->name('checkOut');


//volunteer creative
Route::get('/home_vltcreative', 'vltController@home_vltcreative')->name('home_vltcreative');
Route::get('/volunteer/tugas/{tugas_id}/isi-peran', 'vltController@updatePeran')->name('isi.peran');
Route::post('/volunteer/tugas/{tugas_id}/simpan-peran', 'vltController@updatePeran')->name('simpan.peran');

Route::post('/updateTaskStatus/{tugas_id}/{status}', 'vltController@updateTaskStatus')->name('updateTaskStatus');



//kepala LPKKSK
Route::get('/home_kepalaPKK', 'KPLController@home_kepalaPKK');
Route::get('/div_kepalaPKK', 'KPLController@div_kepalaPKK');
Route::get('/tambah_div', 'KPLController@tambah_div');
Route::post('/simpanDiv', 'KPLController@simpanDiv');
Route::get('/edit_div/{div_id}', 'KPLController@edit_div')->name('edit_div'); 
Route::put('/updateDiv/{div_id}', 'KPLController@updateDiv')->name('updateDiv');
Route::delete('/hapus_div/{divisi_id}', 'KPLController@hapus_div')->name('hapus_div');