<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\User;
use App\divisi;
use App\volunteer;
use App\jadwal;
use App\tugas;
use App\EmailNotification;
use App\Mail\VolunteerEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class koorController extends Controller
{
    public function main(){
        return view('layout/main');
}

// Halaman Home (Tambah Data volunteer, edit, hapus )
public function home_koor()
{
    $user = Auth::user();
    $divisi = divisi::all();
    $today = Carbon::today();

    $volunteer = volunteer::with('divisi')
        ->where('divisi_id', $user->divisi_id)
        ->get();

    foreach ($volunteer as $vol) {
        $akhirAktif = Carbon::parse($vol->akhir_aktif);

        // Update logika perbandingan tanggal
        if ($akhirAktif->isPast()) {
            $vol->status = 'Tidak Aktif';
        } else {
            $vol->status = 'Aktif';
        }

        // Perbarui field status di database
        $vol->save();

        // Format tanggal dan hitung total hari
        $vol->mulai = Carbon::parse($vol->mulai_aktif)->format('d-m-Y');
        $vol->akhir = Carbon::parse($vol->akhir_aktif)->format('d-m-Y');
        $mulai = Carbon::parse($vol->mulai_aktif);
        $akhir = Carbon::parse($vol->akhir_aktif);
        $vol->total_hari = $akhir->diffInDays($mulai) + 1;
    }

    return view('home_koor', compact('volunteer', 'divisi', 'user'));
}

    public function tambah_vlt(){
        $user = Auth::user(); 
        $divisi = divisi::where('divisi_id', $user->divisi_id)->first();
        return view('tambah_vlt', compact('divisi', 'user'));
    }


    public function simpanVlt(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'nim' => 'required|unique:volunteer,nim',
        'fakultas' => 'required',
        'jurusan' => 'required',
        'email' => [
            'required',
            'email',
            'regex:/^[\w\.\-]+@([\w\-]+\.)*ukdw\.ac\.id$/i',
            'unique:volunteer,email',
        ],
        'mulai_aktif' => 'required|date',
        'akhir_aktif' => 'required|date|after_or_equal:mulai_aktif',
        'divisi_id' => 'required|exists:divisi,divisi_id',
    ], [
        'nim.unique' => 'NIM sudah terdaftar. Silakan gunakan yang lain.',
        'email.email' => 'Format email tidak valid.',
        'email.regex' => 'Email harus menggunakan domain ukdw.ac.id.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'akhir_aktif.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan mulai aktif.',
    ]);

    volunteer::create([
        'nama'=> $request->nama,
        'nim'=> $request->nim,
        'fakultas'=> $request->fakultas,
        'jurusan'=> $request->jurusan,
        'email'=> $request->email,
        'mulai_aktif'=> $request->mulai_aktif,
        'akhir_aktif'=> $request->akhir_aktif,
        'status' => 'Aktif', 
        'divisi_id'=> $request->divisi_id,
    ]);

    return redirect('/home_koor')->with('success', 'Data volunteer berhasil disimpan!');
}

    

        public function edit_vlt($vol_id, Request $request){
            $user = Auth::user();
            $volunteer = volunteer::find($vol_id);
            return view('edit_vlt', compact('volunteer', 'user'));
        }

        public function updateVlt(Request $request, $vol_id) { 
            $volunteer = volunteer::find($vol_id);
            $volunteer->update([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'fakultas' => $request->fakultas,
                'jurusan' => $request->jurusan,
                'mulai_aktif'=> $request->mulai_aktif,
                'akhir_aktif'=> $request->akhir_aktif,
            ]);
        
            return redirect('/home_koor')->with('success', 'Data volunteer berhasil diupdate!'); // Redirect dengan pesan sukses
        }



    public function hapus_vlt($vol_id){
        $vol = volunteer::find($vol_id);
        $vol->delete();
        return redirect('/home_koor');
    }

// END END END END END //


// Halaman Jadwal Volunteer
public function jadwal_vlt() {
    $user = Auth::user(); 
    $jadwal = jadwal::with(['volunteers', 'divisi'])->get();
   // dd($jadwal);
    return view('jadwal_vlt', compact('jadwal', 'user'));
}

    public function tambah_jadwal(){
        $user = Auth::user();
        $divisi = divisi::where('divisi_id', $user->divisi_id)->first();
    
        $voldiv = volunteer::with('divisi')
        ->where('divisi_id', Auth::user()->divisi_id) // Filter berdasarkan divisi_id user yang login
        ->get();
    return view('tambah_jadwal', compact('divisi', 'voldiv', 'user'));
}

public function simpanjadwal(Request $request)
{
        $jadwal = jadwal::create([
            'divisi_id' => $request->divisi_id, 
            'tgl_jadwal' => $request->tgl_jadwal,
            'agenda' => $request->agenda,
        ]);

        $jadwal->volunteers()->sync($request->petugas); 
        return redirect('jadwal_vlt');
}


    public function edit_jadwal($jadwal_id, Request $request){
        $user = Auth::user();
        $jadwal = jadwal::with(['divisi', 'volunteers'])->find($jadwal_id);
        $divisi = divisi::all();
        $allVolunteers = Volunteer::where('status', 'Aktif')->get();
        
        $selectedVolunteers = $jadwal->volunteers->pluck('vol_id')->toArray();
        return view('edit_jadwal', compact('jadwal', 'selectedVolunteers', 'divisi', 'user', 'allVolunteers'));

    }

    public function updateJadwal(Request $request, $jadwal_id) { 
            $jadwal = jadwal::find($jadwal_id);
            $jadwal->update([
                'divisi_id' => $request->divisi_id,
                'tgl_jadwal' => $request->tgl_jadwal,
                'agenda' => $request->agenda,
            ]);
        
            $jadwal->volunteers()->sync($request->petugas); 
            return redirect('/jadwal_vlt')->with('success', 'Data volunteer berhasil diupdate!'); // Redirect dengan pesan sukses
        }

    public function hapus_jdwl($jadwal_id){
        $jadwal = jadwal::find($jadwal_id);
        $jadwal->delete();
        return redirect('/jadwal_vlt');
    }

    // END END END END END //

    public function kirimEmail(Request $request, $vol_id)
{
    $volunteer = volunteer::find($vol_id);
    $token = Str::random(60);
    $expiresAt = Carbon::now()->addHours(24);

    $volunteer->reset_token = $token;
    $volunteer->reset_token_expires_at = $expiresAt;
    $volunteer->save();

    $resetLink = route('reset_password', ['token' => $token]);

    // Kirim email
    Mail::to($volunteer->email)->send(new ResetPasswordMail($volunteer, $resetLink));

    return redirect()->back()->with('success', 'Email reset password berhasil dikirim.');
}

/////////////////// Reset Password Volunteer/////////////////////////

public function reset_now(Request $request, $token): View
{
    $volunteer = volunteer::where('reset_token', $token)->first();

    if (!$volunteer) {
        return view('reset_now')->with('error', 'Token reset password tidak valid.')->with('token', $token);
    }

    return view('reset_now')->with('token', $token);
}



// Validasi Presensi
public function validasi_presensi(){
    $user = Auth::user(); 
    return view('validasi_presensi', compact('user'));
}



///////////////////////////////////KOORDINATOR CREATIVE/////////////////////////////////
public function home_koorcrv(){
    $user = Auth::user(); 
    $divisi=divisi::all();
    //ngambil data volunteer berdasarkan divisi
    // $volunteer = volunteer::with('divisi')->whereHas('divisi', function($query) {$query->where('nama_divisi', 'PKK Live');})->get();
    
    $volunteer = volunteer::with('divisi')
    ->where('divisi_id', $user->divisi_id) // Filter berdasarkan divisi_id user
    ->get();
    return view('home_koorcrv', compact( 'volunteer', 'divisi', 'user'));
}


public function tambah_vltcrv(){
    $user = Auth::user();
    $divisi = divisi::where('divisi_id', $user->divisi_id)->get();
    return view('tambah_vltcrv', compact('divisi', 'user'));
}


public function simpanVltcrv(Request $request){
        volunteer::create([
            'nama'=> $request->nama,
            'nim'=> $request->nim,
            'fakultas'=> $request->fakultas,
            'jurusan'=> $request->jurusan,
            'email'=> $request->email,
            'periode'=> $request->periode,
            'divisi_id'=> $request->divisi_id,
            'password'=> bcrypt($request->password)
        ]);
        return redirect('/home_koorcrv');         
}

    public function edit_vltcrv($vol_id, Request $request){
        $volunteer = volunteer::find($vol_id);
        return view('edit_vltcrv', compact('volunteer'));
    }

    public function updateVltcrv(Request $request, $vol_id) { 
        $volunteer = volunteer::find($vol_id);
        $volunteer->update([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'fakultas' => $request->fakultas,
            'jurusan' => $request->jurusan,
            'periode' => $request->periode,
        ]);
    
        return redirect('/home_koorcrv')->with('success', 'Data volunteer berhasil diupdate!'); // Redirect dengan pesan sukses
    }

public function hapus_vltcrv($vol_id){
    $vol = volunteer::find($vol_id);
    $vol->delete();
    return redirect('/home_koorcrv');
}

public function task_mn(){
         $user = Auth::user();
        $tugas = tugas::with(['volunteers', 'divisi'])->get();
        
        return view('task_mn', compact('tugas', 'user'));
}

public function tambah_task(){
    $user = Auth::user();
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();
    
    $voldiv = volunteer::with('divisi')
    ->where('divisi_id', Auth::user()->divisi_id) // Filter berdasarkan divisi_id user yang login
    ->get();

    return view('tambah_task', compact( 'divisi', 'voldiv', 'user'));
}

public function simpan_task(Request $request)
{
        $tugas = tugas::create([
            'divisi_id' => $request->divisi_id, 
            'desk_tgs' => $request->desk_tgs,
            'deadline' => $request->deadline,
        ]);

        $tugas->volunteers()->sync($request->petugas); 
        return redirect('task_mn');
}

public function edit_task($tugas_id, Request $request){
    $tugas = tugas::with(['divisi', 'volunteers'])->find($tugas_id);
    $divisi = divisi::all();
    $selectedVolunteers = $tugas->volunteers->pluck('vol_id')->toArray();
    return view('edit_task', compact('tugas', 'selectedVolunteers', 'divisi'));

}

public function updateTask(Request $request, $tugas_id) { 
    $tugas = tugas::find($tugas_id);
    $tugas->update([
        'divisi_id' => $request->divisi_id, 
        'desk_tgs' => $request->desk_tgs,
        'deadline' => $request->deadline,
    ]);

    $tugas->volunteers()->sync($request->petugas);  
    return redirect('/task_mn')->with('success', 'Data volunteer berhasil diupdate!'); // Redirect dengan pesan sukses
}

public function hapus_task($tugas_id){
    $tugas = tugas::find($tugas_id);
    $tugas->delete();
    return redirect('/task_mn');
}


}