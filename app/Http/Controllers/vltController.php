<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\divisi;
use App\volunteer;
use App\jadwal;
use App\presensi;
use App\tugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // Tambahkan baris ini
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class vltController extends Controller
{

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'token' => 'required',
        ]);
    
        $volunteer = volunteer::where('reset_token', $request->token)->first();
        
        if (!$volunteer) {
            return redirect()->route('reset')->with('error', 'Token reset password tidak valid.');
        }
    
        $volunteer->password = Hash::make($request->password); // Langsung update kolom password di tabel volunteer
        $volunteer->reset_token = null;
        $volunteer->reset_token_expires_at = null;
        $volunteer->save();
    
        return redirect('/loginVol')->with('success', 'Password berhasil diubah.');
    }


    public function loginVol(){
        return view('loginVol');
     }


     public function cekloginVol(Request $request)
     {
         $datalogin = [
             'email' => $request->email,
             'password' => $request->password,
         ];

         if (Auth::guard('volunteer')->attempt($datalogin)) {
             $volunteer = Auth::guard('volunteer')->user();
             Log::info('Login volunteer berhasil untuk user dengan ID:', [$volunteer->vol_id]);
     
             // Cek nama divisinya
             $divisi = $volunteer->divisi->nama_divisi ?? null;
     
             if ($divisi === 'Creative') {
                 return redirect()->route('home_vltcreative');
             } else {
                 return redirect()->route('home_vlt');
             }
     
         } else {
             Log::info('Login volunteer gagal dengan kredensial:', $datalogin);
             return redirect('/loginVol')->with('error', 'Email atau password salah.');
         }
     }
     

////////////////////////////////////////////////////////////////////////////////////////////////////////

public function home_vlt()
{
    $volunteer = Auth::guard('volunteer')->user();

    // Ambil semua jadwal yang berelasi dengan volunteer ini
    $jadwals = $volunteer->jadwals; // pastikan relasi jadwals() ada di model Volunteer

    // Ambil semua presensi volunteer
    $presensiList = Presensi::where('vol_id', $volunteer->vol_id)->get()->keyBy('jadwal_id');

    // Hitung total jadwal
    $totalJadwal = $jadwals->count();

    // Hitung total kehadiran (hanya yang sudah checkout)
    $totalHadir = $presensiList->filter(function ($p) {
        return $p->check_out !== null;
    })->count();

    // Tempelkan presensi ke setiap jadwal
    foreach ($jadwals as $jadwal) {
        $jadwal->my_presensi = $presensiList[$jadwal->jadwal_id] ?? null;
    }

    return view('home_vlt', compact('jadwals', 'volunteer', 'totalJadwal', 'totalHadir'));
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkIn($jadwal_id)
    {
        $volunteer = Auth::guard('volunteer')->user();
    
        // Cek apakah sudah check-in sebelumnya biar nggak dobel
        $existing = presensi::where('jadwal_id', $jadwal_id)
                            ->where('vol_id', $volunteer->vol_id)
                            ->first();
    
        if ($existing) {
            return redirect()->back()->with('error', 'Kamu sudah check-in untuk jadwal ini.');
        }
    
        $presensi = new presensi();
        $presensi->jadwal_id = $jadwal_id;
        $presensi->vol_id = $volunteer->vol_id;
        $presensi->check_in = now();
        $presensi->save();
    
        return redirect()->route('home_vlt')->with('success', 'Berhasil Check-in');
    }

    public function checkOut($jadwal_id)
{
    $volunteer = Auth::guard('volunteer')->user();

    // Cari data presensi user untuk jadwal ini
    $presensi = presensi::where('jadwal_id', $jadwal_id)
                        ->where('vol_id', $volunteer->vol_id)
                        ->first();

    // Kalau belum pernah check-in
    if (!$presensi) {
        return redirect()->back()->with('error', 'Kamu belum check-in untuk jadwal ini.');
    }

    // Kalau sudah check-out, cegah dobel
    if ($presensi->check_out) {
        return redirect()->back()->with('error', 'Kamu sudah check-out sebelumnya.');
    }

    // Set waktu check-out sekarang
    $presensi->check_out = now();

    // Hitung total jam (dalam format HH:MM:SS)
    $checkInTime = \Carbon\Carbon::parse($presensi->check_in);
    $checkOutTime = \Carbon\Carbon::parse($presensi->check_out);
    $totalDuration = $checkInTime->diff($checkOutTime);
    $presensi->total_jam = $totalDuration->format('%H:%I:%S');


    $presensi->save();

    return redirect()->route('home_vlt')->with('success', 'Berhasil Check-out');
}

    

    public function profile_vlt(){
        $user = auth()->user(); // Asumsikan Anda sudah melakukan autentikasi
        $data = $this->getDataProfil($user, $divisi); // Fungsi untuk mengambil data profil berdasarkan divisi
        
        return view('profile', compact('data', 'divisi'));
    }



 ////////////////////////////////////////////CREATIVE PUNYA///////////////////////////////////
 public function home_vltcreative()
{
    $volunteer = Auth::guard('volunteer')->user();

    if (!$volunteer) {
        return redirect()->route('login.volunteer');
    }

    // Ambil semua tugas dengan data pivot (status & peran)
    $tasks = $volunteer->tugas()->withPivot('status', 'peran')->get();

    // Hitung total semua tugas
    $totalTask = $tasks->count();

    // Hitung berdasarkan status dari tabel pivot
    $totalSelesai = $tasks->where('pivot.status', 'Tugas Selesai')->count();
    $totalBelum   = $tasks->where('pivot.status', 'Belum Dikerjakan')->count();

    return view('home_vltcreative', compact('volunteer', 'tasks', 'totalTask', 'totalSelesai', 'totalBelum'));
}

public function updateTaskStatus($tugas_id, $status)
{
    $volunteer = Auth::guard('volunteer')->user();

    // Update status di pivot
    $volunteer->tugas()->updateExistingPivot($tugas_id, ['status' => $status]);

    return redirect()->route('home_vltcreative')->with('success', 'Status berhasil diperbarui!');
}



public function updatePeran(Request $request, $tugas_id)
{
    $request->validate([
        'peran' => 'required|string|max:255',
    ]);

    $volunteer = Auth::guard('volunteer')->user(); // pakai guard volunteer

    if (!$volunteer) {
        return redirect()->back()->withErrors(['msg' => 'Gagal mendapatkan data volunteer.']);
    }

    $volunteer_id = $volunteer->vol_id;

    $tugas = tugas::findOrFail($tugas_id);

    // Simpan atau update peran di tabel pivot
    $tugas->volunteers()->syncWithoutDetaching([
        $volunteer_id => ['peran' => $request->peran]
    ]);

    return redirect()->back()->with('success', 'Peran berhasil disimpan!');
}


}
 

 
 
 
 
 
 




























