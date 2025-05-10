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
     
         Log::info('Data login yang diterima:', $datalogin);
     
         if (Auth::guard('volunteer')->attempt($datalogin)) {
             $volunteer = Auth::guard('volunteer')->user()->load('subDivisi', 'divisi');
     
             Log::info('Login volunteer berhasil untuk user dengan ID:', [$volunteer->vol_id]);
             Log::info('Data volunteer:', [$volunteer]);
     
             $divisi = $volunteer->divisi->nama_divisi ?? '';
             $subDivisi = $volunteer->subDivisi->nama_subdivisi ?? null;
     
             Log::info('Divisi:', [$divisi]);
             Log::info('Sub Divisi:', [$subDivisi]);
     
             // Routing logika berdasarkan divisi & sub divisi
             if ($divisi === 'Creative') {
                 Log::info('Divisi Creative terdeteksi.');
                 if ($subDivisi === 'Desain') {
                     return redirect('/home_vltcreative');
                 } elseif ($subDivisi === 'PKK Live') {
                     return redirect('/home_vlt');
                 } else {
                     return redirect('/home_vlt')->with('warning', 'Sub divisi tidak dikenali.');
                 }
             } elseif ($divisi === 'Konseling') {
                 Log::info('Divisi Konseling terdeteksi.');
                 return redirect('/home_vltcreative');
             } else {
                 return redirect('/home_vlt')->with('error', 'Divisi tidak dikenali.');
             }
         } else {
             return redirect('/loginVol')->with('error', 'Email atau password salah.');
         }
     }
     
     
     
     
     
     
     
     

     

// ////////////////////////////////////////////////////////////////////////////////////////////////////////
public function home_vlt()
{
    $volunteer = Auth::guard('volunteer')->user();

    // Ambil semua jadwal yang berelasi dengan volunteer ini
    $jadwals = $volunteer->jadwals;

    // Ambil semua presensi volunteer
    $presensiList = presensi::where('vol_id', $volunteer->vol_id)->get()->keyBy('jadwal_id');

    // Hitung total jadwal
    $totalJadwal = $jadwals->count();

    // Hitung total kehadiran (hanya yang sudah checkout)
    $totalHadir = $presensiList->filter(function ($p) {
        return $p->check_out !== null;
    })->reduce(function ($carry, $p) {
        $jam = (int) \Carbon\Carbon::createFromFormat('H:i:s', $p->total_jam)->format('H');
        return $carry + $jam;
    }, 0);
    

    // Tempelkan presensi & info "hari ini" ke setiap jadwal
    foreach ($jadwals as $jadwal) {
        $jadwal->my_presensi = $presensiList[$jadwal->jadwal_id] ?? null;
        $jadwal->is_today = Carbon::parse($jadwal->tgl_jadwal)->isToday();

        $jamBuka = Carbon::parse($jadwal->jam_buka);
        $now = Carbon::now();
        $jadwal->canCheckIn = $jadwal->is_today && $now->greaterThanOrEqualTo($jamBuka);
    }

    return view('home_vlt', compact('jadwals', 'volunteer', 'totalJadwal', 'totalHadir'));
}

public function checkIn(Request $request, $jadwal_id)
{
    \Log::info('➡️ START CHECK IN', [
        'jadwal_id' => $jadwal_id,
        'volunteer_id' => Auth::guard('volunteer')->id(),
    ]);

    $volunteer = Auth::guard('volunteer')->user();
    if (!$volunteer) {
        \Log::error('❌ Volunteer tidak ditemukan.');
        return redirect()->back()->with('error', 'Akun volunteer tidak ditemukan.');
    }

    $jadwal = jadwal::find($jadwal_id);
    if (!$jadwal) {
        \Log::error('❌ Jadwal tidak ditemukan.', ['jadwal_id' => $jadwal_id]);
        return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
    }

    \Log::info('📅 Cek Hari H:', [
        'jadwal_tanggal' => $jadwal->tgl_jadwal,
        'today' => Carbon::today()->toDateString(),
        'is_today' => Carbon::parse($jadwal->tgl_jadwal)->toDateString() === Carbon::today()->toDateString(),
    ]);

    if (Carbon::parse($jadwal->tgl_jadwal)->toDateString() !== Carbon::today()->toDateString()) {
        return redirect()->back()->with('error', 'Check-in hanya bisa dilakukan pada hari H.');
    }

    $now = Carbon::now();

    \Log::info('⏰ Cek jam buka/tutup:', [
        'jam_buka' => $jadwal->jam_buka,
        'jam_tutup' => $jadwal->jam_tutup,
        'sekarang' => $now->format('H:i:s')
    ]);

    try {
        $jamBuka = Carbon::createFromFormat('H:i:s', $jadwal->jam_buka);
        $jamTutup = Carbon::createFromFormat('H:i:s', $jadwal->jam_tutup);
    } catch (\Exception $e) {
        \Log::error('❌ Format jam salah.', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Format jam pada jadwal tidak valid.');
    }

    if (!$now->between($jamBuka, $jamTutup)) {
        return redirect()->back()->with('error', 'Check-in hanya bisa dilakukan antara jam ' . $jadwal->jam_buka . ' - ' . $jadwal->jam_tutup);
    }

    $existing = presensi::where('jadwal_id', $jadwal_id)
        ->where('vol_id', $volunteer->vol_id)
        ->first();

    if ($existing) {
        \Log::warning('⚠️ Sudah check-in sebelumnya.', [
            'jadwal_id' => $jadwal_id,
            'vol_id' => $volunteer->vol_id
        ]);
        return redirect()->back()->with('error', 'Kamu sudah check-in untuk jadwal ini.');
    }

    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');

    if (!$latitude || !$longitude) {
        \Log::warning('⚠️ Lokasi tidak ditemukan.');
        return redirect()->back()->with('error', 'Gagal mendapatkan lokasi. Aktifkan GPS.');
    }

    \Log::info('📍 Lokasi diterima:', [
        'latitude' => $latitude,
        'longitude' => $longitude
    ]);

    // Lokasi kampus UKDW
    $kampusLat = -7.7682121;
    $kampusLng =  110.4083341;
    $jarak = $this->hitungJarak($latitude, $longitude, $kampusLat, $kampusLng);

    \Log::info('📏 Jarak ke kampus:', ['jarak_km' => $jarak]);

    if ($jarak > 0.15) {
        return redirect()->back()->with('error', 'Presensi hanya bisa dilakukan di kawasan kampus.');
    }

    $presensi = new presensi();
    $presensi->jadwal_id = $jadwal_id;
    $presensi->vol_id = $volunteer->vol_id;
    $presensi->check_in = $now;
    $presensi->status = 'Diproses';

    $saved = $presensi->save();
    \Log::info('Presensi disimpan?', ['saved' => $saved]);

    if ($saved) {
        \Log::info('✅ Berhasil simpan presensi.', [
            'jadwal_id' => $jadwal_id,
            'vol_id' => $volunteer->vol_id,
        ]);
    } else {
        \Log::error('❌ Gagal simpan presensi.', [
            'jadwal_id' => $jadwal_id,
            'vol_id' => $volunteer->vol_id,
        ]);
    }

    return $saved
        ? redirect()->route('home_vlt')->with('success', 'Berhasil Check-in')
        : redirect()->back()->with('error', 'Gagal menyimpan data presensi.');
}



public function checkOut(Request $request, $jadwal_id)
{
    $volunteer = Auth::guard('volunteer')->user();
    $desk_tgs = $request->input('desk_tgs');

    $presensi = presensi::where('jadwal_id', $jadwal_id)
        ->where('vol_id', $volunteer->vol_id)
        ->first();

    if (!$presensi) {
        return redirect()->back()->with('error', 'Presensi tidak ditemukan. Pastikan sudah check-in.');
    }

    if ($presensi->check_out) {
        return redirect()->back()->with('error', 'Sudah melakukan check-out sebelumnya.');
    }

    $jadwal = jadwal::findOrFail($jadwal_id);
    $now = Carbon::now();
    $jamTutup = Carbon::createFromFormat('H:i:s', $jadwal->jam_tutup);

    if (!$now->lessThan($jamTutup)) {
        return redirect()->back()->with('error', 'Check-out hanya bisa dilakukan sebelum jam tutup.');
    }

    // Hitung durasi yang akan ditambahkan
    $durasiBaruDetik = 0;
    if ($presensi->check_in && $now->gt($presensi->check_in)) {
        $durasiBaruDetik = $now->diffInSeconds(Carbon::parse($presensi->check_in));
    }

    // Hitung total durasi bulan ini
    $totalDetikBulanIni = presensi::where('vol_id', $volunteer->vol_id)
        ->whereNotNull('check_out')
        ->whereMonth('check_out', $now->month)
        ->whereYear('check_out', $now->year)
        ->get()
        ->reduce(function ($carry, $item) {
            $durasi = Carbon::createFromFormat('H:i:s', $item->total_jam);
            return $carry + ($durasi->hour * 3600 + $durasi->minute * 60 + $durasi->second);
        }, 0);

    // Validasi apakah melebihi 72 jam (259200 detik)
    if (($totalDetikBulanIni + $durasiBaruDetik) > (72 * 3600)) {
        return redirect()->back()->with('error', 'Total jam presensi bulan ini sudah melebihi 72 jam.');
    }

    // Simpan data presensi
    $presensi->check_out = $now;
    $presensi->desk_tgs = $desk_tgs;
    $presensi->total_jam = gmdate('H:i:s', $durasiBaruDetik);
    $presensi->status = 'Diproses';
    $presensi->save();

    return redirect()->route('home_vlt')->with('success', 'Berhasil Check-out!');
}




public function hitungJarak($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371000; // radius bumi dalam meter
    $x = deg2rad($lon2 - $lon1) * cos(deg2rad(($lat1 + $lat2) / 2));
    $y = deg2rad($lat2 - $lat1);
    $jarak = sqrt($x * $x + $y * $y) * $R;
    return $jarak / 1000; // dalam kilometer
}
 


 ///////////////////////////////////////////////////////////////////////////////////////////////

public function profile_vlt()
{
    $volunteer = Auth::guard('volunteer')->user();

    // Ambil data volunteer lengkap + relasi divisi
    $volunteer = Volunteer::with('divisi')->where('email', $volunteer->email)->first();

    if (!$volunteer) {
        abort(404, 'Volunteer tidak ditemukan.');
    }

    // Format tanggal langsung di sini
    $volunteer->mulai_aktif_formatted = Carbon::parse($volunteer->mulai_aktif)->format('d M Y');
    $volunteer->akhir_aktif_formatted = Carbon::parse($volunteer->akhir_aktif)->format('d M Y');

    $divisi = $volunteer->divisi;

    return view('profile_vlt', compact('volunteer', 'divisi'));
}

public function profile_vltCreative()
{
    $volunteer = Auth::guard('volunteer')->user();

    // Ambil data volunteer lengkap + relasi divisi
    $volunteer = Volunteer::with('divisi')->where('email', $volunteer->email)->first();

    if (!$volunteer) {
        abort(404, 'Volunteer tidak ditemukan.');
    }

    // Format tanggal langsung di sini
    $volunteer->mulai_aktif_formatted = Carbon::parse($volunteer->mulai_aktif)->format('d M Y');
    $volunteer->akhir_aktif_formatted = Carbon::parse($volunteer->akhir_aktif)->format('d M Y');

    $divisi = $volunteer->divisi;

    return view('profile_vltCreative', compact('volunteer', 'divisi'));
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

    $tasks = $tasks->map(function ($task) {
        $deadline = \Carbon\Carbon::parse($task->deadline);
        $task->isDeadlinePassed = $deadline->isPast();
        $task->daysLeft = $deadline->diffInDays(now());
        $task->showAlert = ($task->daysLeft === 2 && !$task->isDeadlinePassed);
        return $task;
    });
    

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

public function logoutVol()
{
    Auth::guard('volunteer')->logout(); // logout dari guard volunteer
    return redirect('/loginVol');       // redirect ke halaman login volunteer
}
}
 

 
 
 
 
 
 




























