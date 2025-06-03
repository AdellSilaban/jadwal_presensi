<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =================================
// AUTHENTICATION ROUTES
// =================================

Route::get('/', function () {
    // Jika login sebagai volunteer
    if (Auth::guard('volunteer')->check()) {
        $volunteer = Auth::guard('volunteer')->user()->load('divisi', 'subDivisi');
        $divisi = $volunteer->divisi->nama_divisi ?? '';
        $subDivisi = $volunteer->subDivisi->nama_subdivisi ?? '';

        if ($divisi === 'Creative' && $subDivisi === 'Desain') {
            return redirect('/home_vltcreative');
        } elseif ($divisi === 'Konseling') {
            return redirect('/home_vltcreative');
        } else {
            return redirect('/home_vlt');
        }
    }

    // Jika login sebagai koordinator atau kepala
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->jabatan) {
            case 'Kepala LPKKSK':
                return redirect('/dashboard');
            case 'Koordinator Divisi Creative':
            case 'Koordinator Divisi Tim Ibadah Kampus':
            case 'Koordinator Divisi Konseling':
                return redirect('/home_koor');
        }
    }

    // Kalau belum login sama sekali
    return redirect('/login');
});


// ✅ Route untuk user belum login
    // Login Koordinator & Kepala
    Route::get('/login', 'authController@login')->name('login');
    Route::post('/ceklogin', 'authController@ceklogin')->name('ceklogin');

    // Register Koordinator
    Route::get('/register', 'authController@register');
    Route::post('/simpanRegis', 'authController@simpanRegis');

    // Login Volunteer
    Route::get('/loginVol', 'vltController@loginVol')->name('loginVol');
    Route::post('/cekloginVol', 'vltController@cekloginVol')->name('cekloginVol');



Route::middleware(['auth'])->group(function () {

// Halaman form ubah password
Route::get('/ubah_pass', 'authController@ubah_pass')->name('ubah_pass');

// Proses update password
Route::post('/update_pass', 'authController@update_pass')->name('update_pass');

Route::get('/logout', 'authController@logout');

});

// =================================
// VOLUNTEER AUTH ROUTES
// =================================
Route::middleware(['auth:volunteer'])->group(function () {
    Route::get('/home_vlt', 'vltController@home_vlt')->name('home_vlt');
    Route::get('/home_vltcreative', 'vltController@home_vltcreative')->name('home_vltcreative');
    Route::get('/profile_vlt', 'vltController@profile_vlt');
    Route::post('/checkIn/{jadwal_id}', 'vltController@checkIn')->name('checkIn');
    Route::post('/checkOut/{jadwal_id}', 'vltController@checkOut')->name('checkOut');

    Route::get('/volunteer/tugas/{tugas_id}/isi-peran', 'vltController@updatePeran')->name('isi.peran');
    Route::post('/volunteer/tugas/{tugas_id}/simpan-peran', 'vltController@updatePeran')->name('simpan.peran');
    Route::get('/profile_vltCreative', 'vltController@profile_vltCreative');
    Route::post('/updateTaskStatus/{tugas_id}/{status}', 'vltController@updateTaskStatus')->name('updateTaskStatus');

    Route::get('/logoutVol', 'vltController@logoutVol');
});



Route::get('/lihat_sertif', 'vltController@lihat_sertif')->name('lihat_sertif');

// =================================
// KOORDINATOR ROUTES
// =================================
Route::middleware(['auth'])->group(function () {
    Route::get('/home_koor', 'koorController@home_koor')->name('home_koor');
    Route::get('/jadwal_vlt', 'koorController@jadwal_vlt');
    Route::get('/data_presensi', 'koorController@data_presensi')->name('data_presensi');
    Route::get('/filter-presensi', 'koorController@filterPresensi')->name('filterPresensi');
    Route::get('/download-presensi/{vol_id}', 'koorController@downloadPresensi')->name('download.presensi');
    Route::get('/presensi_pdf', 'koorController@presensi_pdf')->name('presensi_pdf');

    Route::get('/tambah_vlt', 'koorController@tambah_vlt');
    Route::get('/edit_vlt/{vol_id}', 'koorController@edit_vlt')->name('edit_vlt');
    Route::put('/updateVlt/{vol_id}', 'koorController@updateVlt')->name('updateVlt'); 
    Route::post('/simpanVlt', 'koorController@simpanVlt');
    Route::delete('/hapus_vlt/{vol_id}', 'koorController@hapus_vlt')->name('hapus_vlt');

    Route::get('/tambah_jadwal', 'koorController@tambah_jadwal');
    Route::get('/edit_jadwal/{jadwal_id}', 'koorController@edit_jadwal')->name('edit_jadwal'); 
    Route::put('/updateJadwal/{jadwal_id}', 'koorController@updateJadwal')->name('updateJadwal'); 
    Route::delete('/hapus_jdwl/{jadwal_id}', 'koorController@hapus_jdwl')->name('hapus_jdwl');
    Route::post('/simpanjadwal', 'koorController@simpanjadwal');

    Route::post('/cekEmail', 'koorController@cekEmail');
    Route::get('/kirimEmail/{vol_id}', 'koorController@kirimEmail')->name('kirimEmail');

    Route::get('/profile_koor', 'koorController@profile_koor')->name('profile_koor');

    Route::get('/ajukanPeninjauan/{vol_id}', 'koorController@ajukanPeninjauan')->name('ajukanPeninjauan');

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

    Route::get('/sub_divisi', 'koorController@sub_divisi')->name('sub_divisi');
    Route::get('/tambah_sub', 'koorController@tambah_sub')->name('tambah_sub');
    Route::post('/simpan_sub', 'koorController@simpan_sub');
    Route::get('/edit_sub/{sub_divisi_id}', 'koorController@edit_sub')->name('edit_sub'); 
    Route::put('/update_sub/{sub_divisi_id}', 'koorController@update_sub')->name('update_sub'); 
    Route::delete('/hapus_sub/{sub_divisi_id}', 'koorController@hapus_sub')->name('hapus_sub');

    Route::get('/edit_presensi/{presensi_id}', 'koorController@edit_presensi')->name('edit_presensi');
    Route::put('/updatePresensi/{presensi_id}', 'koorController@updatePresensi')->name('updatePresensi');

    Route::get('/validasi_task', 'koorController@validasi_task')->name('validasi_task');
    Route::post('/validasi_task/submit', 'koorController@submit')->name('validasi.task.submit');
    // Route untuk menampilkan form upload (GET)
    Route::get('/formuploadSertif', 'koorController@formuploadSertif')->name('formuploadSertif');

    // Route untuk proses upload file (POST)
    Route::post('/uploadSertif', 'koorController@uploadSertif')->name('uploadSertif');


    
});

// =================================
// KEPALA LPKKSK ROUTES
// =================================
Route::middleware(['auth'])->group(function () {
    Route::get('/home_kepalaPKK', 'KPLController@home_kepalaPKK');
    Route::get('/dashboard', 'KPLController@dashboard')->name('dashboard');
    Route::get('/div_kepalaPKK', 'KPLController@div_kepalaPKK');
    Route::get('/koor_kepalaPKK', 'KPLController@koor_kepalaPKK');
    Route::put('/nonaktifKoor/{user_id}', 'KPLController@nonaktifKoor')->name('nonaktifKoor');
    Route::get('/tambah_div', 'KPLController@tambah_div');
    Route::post('/simpanDiv', 'KPLController@simpanDiv');
    Route::get('/edit_div/{div_id}', 'KPLController@edit_div')->name('edit_div'); 
    Route::put('/updateDiv/{div_id}', 'KPLController@updateDiv')->name('updateDiv');
    Route::delete('/hapus_div/{divisi_id}', 'KPLController@hapus_div')->name('hapus_div');
    Route::get('/export-volunteer-pdf', 'KPLController@exportVolunteerPDF')->name('export.volunteer.pdf');
    Route::put('/update_divVol/{vol_id}', 'KPLController@update_divVol')->name('update_divVol');

    Route::post('/hentikanVolunteer/{vol_id}', 'KPLController@hentikanVolunteer')->name('hentikanVolunteer');
    Route::post('/pulihkanVolunteer/{vol_id}', 'KPLController@pulihkanVolunteer')->name('pulihkanVolunteer');

    Route::get('/coba', 'KPLController@coba');
});

// =================================
// RESET PASSWORD (NON-MIDDLEWARE)
// =================================
Route::get('/reset-password/{token}', 'koorController@reset_now')->name('reset_password');
Route::get('/reset-password/invalid', 'koorController@showInvalidTokenForm')->name('reset');
Route::post('/updatePassword', 'vltController@updatePassword')->name('updatePassword');