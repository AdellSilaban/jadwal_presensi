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
use App\presensi;
use App\SubDivisi;
use App\tugas_volunteer;
use App\sertif;
use App\EmailNotification;
use App\Mail\VolunteerEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PDF;

class koorController extends Controller
{
    public function main(){
        return view('layout/main');
}

// Halaman Home (Tambah Data volunteer, edit, hapus )
public function home_koor()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }
    
    $divisi = divisi::all();
    $volunteer = volunteer::with(['divisi', 'subDivisi']) // Pastikan subDivisi terload dengan benar
        ->where('divisi_id', $user->divisi_id)
        ->get();

    if ($volunteer->isEmpty()) {
        \Log::info('Tidak ada volunteer dalam divisi ini');
    }

    \Log::info('Volunteer Data:', ['volunteers' => $volunteer]);

    // Melakukan update status volunteer
    foreach ($volunteer as $vol) {
        $akhirAktif = Carbon::parse($vol->akhir_aktif);
        $vol->status = $akhirAktif->isPast() ? 'Tidak Aktif' : 'Aktif';
        $vol->save();
    }

    return view('home_koor', compact('volunteer', 'divisi', 'user'));
}



    public function tambah_vlt(){
    $user = Auth::user(); 
    // Cek apakah divisi ditemukan untuk user
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();

    // Pastikan divisi ditemukan, jika tidak beri pesan error
    if (!$divisi) {
        return redirect()->route('home_koor')->with('error', 'Divisi tidak ditemukan');
    }

    // Ambil subDivisi berdasarkan divisi_id
    $subDivisi = SubDivisi::where('divisi_id', $divisi->divisi_id)->get();

    return view('tambah_vlt', compact('divisi', 'subDivisi', 'user'));
}



    public function simpanVlt(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'nim' => 'required|unique:volunteer,nim',
        'fakultas' => 'required',
        'jurusan' => 'required',
        'bank_no_rek' => 'required',
        'email' => 'required|email|unique:volunteer,email',
        'mulai_aktif' => 'required|date',
        'akhir_aktif' => 'required|date|after_or_equal:mulai_aktif',
        'divisi_id' => 'required|exists:divisi,divisi_id',
        // 'sub_divisi_id' => 'required|exists:sub_divisi,sub_divisi_id',
        'sub_divisi_id' => 'nullable|exists:sub_divisi,sub_divisi_id',

    ], [
        'nim.unique' => 'NIM sudah terdaftar. Silakan gunakan yang lain.',
        'email.email' => 'Format email tidak valid.',
        'akhir_aktif.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan mulai aktif.',
    ]);

    volunteer::create([
        'nama'=> $request->nama,
        'nim'=> $request->nim,
        'fakultas'=> $request->fakultas,
        'jurusan'=> $request->jurusan,
        'bank_no_rek'=> $request->bank_no_rek,
        'email'=> $request->email,
        'mulai_aktif'=> $request->mulai_aktif,
        'akhir_aktif'=> $request->akhir_aktif,
        'status' => 'Aktif', 
        'status_etik' => 'normal', 
        'divisi_id'=> $request->divisi_id,
        'sub_divisi_id'=> $request->sub_divisi_id,
    ]);
    
    return redirect('/home_koor')->with('success', 'Data volunteer berhasil disimpan!');
}

    

        public function edit_vlt($vol_id, Request $request){
            $user = Auth::user();
            $volunteer = volunteer::find($vol_id);
                // Cek apakah divisi ditemukan untuk user
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();

    // Pastikan divisi ditemukan, jika tidak beri pesan error
    if (!$divisi) {
        return redirect()->route('home_koor')->with('error', 'Divisi tidak ditemukan');
    }
            $subDivisi = SubDivisi::where('divisi_id', $divisi->divisi_id)->get();
            return view('edit_vlt', compact('volunteer', 'user', 'subDivisi', 'divisi'));
        }

        public function updateVlt(Request $request, $vol_id) { 
            $volunteer = volunteer::find($vol_id);
            $volunteer->update([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'email' => $request->email,
                'fakultas' => $request->fakultas,
                'jurusan' => $request->jurusan,
                'mulai_aktif'=> $request->mulai_aktif,
                'akhir_aktif'=> $request->akhir_aktif,
                'sub_divisi_id'=> $request->sub_divisi_id,
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
    
    $jadwal = jadwal::with(['volunteers', 'divisi'])
        ->whereHas('divisi', function ($q) use ($user) {
            $q->where('nama_divisi', $user->divisi->nama_divisi);
        })
        ->get();

    return view('jadwal_vlt', compact('jadwal', 'user'));
}


public function tambah_jadwal()
{
    $user = Auth::user();
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();

    // Cek jika user berada di divisi Creative
    if ($user->divisi_id === 1) {  // Anggap divisi Creative memiliki divisi_id = 1
        // Jika divisi adalah Creative, tampilkan hanya volunteer yang ada di sub-divisi PKK Live
        $voldiv = volunteer::with('divisi', 'subdivisi')
            ->where('divisi_id', Auth::user()->divisi_id)
            ->whereHas('SubDivisi', function ($query) {
                $query->where('nama_subdivisi', 'PKK Live');
            })
            ->get();
    } else {
        // Jika bukan divisi Creative, tampilkan semua volunteer dalam divisi tersebut
        $voldiv = volunteer::with('divisi', 'subdivisi')
            ->where('divisi_id', Auth::user()->divisi_id)
            ->get();
    }

    return view('tambah_jadwal', compact('divisi', 'voldiv', 'user'));
}


public function simpanjadwal(Request $request)
{
        $jadwal = jadwal::create([
            'divisi_id' => $request->divisi_id, 
            'tgl_jadwal' => $request->tgl_jadwal,
            'agenda' => $request->agenda,
            'jam_buka' => Carbon::parse($request->jam_buka)->format('H:i:s'),
            'jam_tutup' => Carbon::parse($request->jam_tutup)->format('H:i:s'),
        ]);

        $jadwal->volunteers()->sync($request->petugas); 
        return redirect('jadwal_vlt');
}


    public function edit_jadwal($jadwal_id, Request $request){
        $user = Auth::user();
        $jadwal = jadwal::with(['divisi', 'volunteers'])->find($jadwal_id);
        $divisi = divisi::where('divisi_id', $user->divisi_id)->first();
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
                'jam_buka' => $request->jam_buka,
                'jam_tutup' => $request->jam_tutup,
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



//Data  Presensi
public function data_presensi() {
    $user = Auth::user(); // Koordinator login
    $filterApplied = false;

    // Ambil volunteer berdasarkan:
    // - divisi sesuai koordinator
    // - kalau divisi Creative, filter juga berdasarkan sub divisi
    $volunteers = volunteer::whereHas('divisi', function ($query) use ($user) {
        $query->where('divisi_id', $user->divisi_id);
    });

    // Jika divisi Creative → filter juga berdasarkan sub divisi (misalnya hanya "Desain")
    if ($user->divisi->nama_divisi === 'Creative') {
        $volunteers = $volunteers->whereHas('subDivisi', function ($query) use ($user) {
            // Diasumsikan nama_subdivisi pada user digunakan sebagai patokan filter
            $query->where('nama_subdivisi', $user->subDivisi->nama_subdivisi ?? 'PKK Live');
        });
    } else {
        // Untuk divisi lain, tetap tolak sub divisi "Desain"
        $volunteers = $volunteers->whereHas('subDivisi', function ($query) {
            $query->where('nama_subdivisi', '!=', 'PKK Live');
        });
    }

    $volunteers = $volunteers->get();

    // Jika ada filter manual di request
    if (request()->has('filter')) {
        $filterApplied = true;

        $volunteers = $volunteers->filter(function ($volunteer) use ($user) {
            if ($user->divisi->nama_divisi === 'Creative') {
                return $volunteer->subDivisi->nama_subdivisi === ($user->subDivisi->nama_subdivisi ?? 'PKK Live');
            } else {
                return $volunteer->subDivisi->nama_subdivisi !== 'PKK Live';
            }
        });
    }

    // Filter presensi hanya milik volunteer yang sesuai
    $presensi = presensi::with('volunteer')->whereHas('volunteer', function ($query) use ($user) {
        $query->whereHas('divisi', function ($q) use ($user) {
            $q->where('divisi_id', $user->divisi_id);
        });

        if ($user->divisi->nama_divisi === 'Creative') {
            $query->whereHas('subDivisi', function ($q) use ($user) {
                $q->where('nama_subdivisi', $user->subDivisi->nama_subdivisi ?? 'PKK Live');
            });
        } else {
            $query->whereHas('subDivisi', function ($q) {
                $q->where('nama_subdivisi', '!=', 'PKK Live');
            });
        }
    })->get();

    // Ambil ID volunteer dari dropdown (jika dipilih)
$volunteerId = request()->input('vol_id');

    // Hitung total jam
    $totalHours = 0;

if ($volunteerId) {
    $presensi = presensi::where('vol_id', $volunteerId)->get();
    $filterApplied = true;

    // Hitung total jam kerja hanya jika volunteer dipilih
    foreach ($presensi as $p) {
        if ($p->check_in && $p->check_out) {
            $checkIn = \Carbon\Carbon::parse($p->check_in);
            $checkOut = \Carbon\Carbon::parse($p->check_out);
            $durationInHours = floor($checkIn->diffInSeconds($checkOut) / 3600);
            $totalHours += $durationInHours;
        }
    }
}


    return view('data_presensi', compact('presensi', 'user', 'totalHours', 'volunteers', 'filterApplied'));
}




public function filterPresensi(Request $request)
{
    $user = Auth::user();
    $volunteerId = $request->input('vol_id');
    
    $filterApplied = false;
    $presensi = [];

    // Jika filter diterapkan
    if ($volunteerId) {
        $presensi = presensi::where('vol_id', $volunteerId)->get();
        $filterApplied = true;
    }

    // Ambil daftar volunteer sesuai divisi user login
    $volunteers = volunteer::whereHas('divisi', function ($query) use ($user) {
        $query->where('divisi_id', $user->divisi_id);
    });

    // Tambahan filter jika divisi-nya adalah Creative
    if ($user->divisi->nama_divisi === 'Creative') {
        $volunteers = $volunteers->whereHas('subDivisi', function ($query) use ($user) {
            $query->where('nama_subdivisi', $user->subDivisi->nama_subdivisi ?? 'PKK Live');
        });
    } else {
        // Untuk divisi non-Creative, tetap hindari sub divisi Desain
        $volunteers = $volunteers->whereHas('subDivisi', function ($query) {
            $query->where('nama_subdivisi', '!=', 'PKK Live');
        });
    }

    $volunteers = $volunteers->get();

    // Total Jam
    $totalHours = 0;
    foreach ($presensi as $p) {
        if ($p->check_in && $p->check_out) {
            $checkIn = \Carbon\Carbon::parse($p->check_in);
            $checkOut = \Carbon\Carbon::parse($p->check_out);

            $durationInSeconds = $checkIn->diffInSeconds($checkOut);
            $durationInHours = floor($durationInSeconds / 3600);

            $totalHours += $durationInHours;
        }
    }

    return view('data_presensi', compact('user', 'presensi', 'volunteers', 'totalHours', 'filterApplied'));
}





public function downloadPresensi($vol_id)
{
    // Ambil data berdasarkan volunteerId
    $presensi = presensi::where('vol_id', $vol_id)->get();

     // Ambil data volunteer terkait
     $volunteer = volunteer::findOrFail($vol_id);

      // Hitung total jam
    $totalHours = 0;
    foreach ($presensi as $p) {
        if ($p->check_in && $p->check_out) {
            $checkIn = \Carbon\Carbon::parse($p->check_in);
            $checkOut = \Carbon\Carbon::parse($p->check_out);

            $durationInSeconds = $checkIn->diffInSeconds($checkOut);
            $durationInHours = floor($durationInSeconds / 3600);
            $totalHours += $durationInHours;
        }
    }

    // Proses data dan generate PDF
    $pdf = PDF::loadView('presensi_pdf', compact('presensi', 'volunteer', 'totalHours'));

    // Download PDF
    return $pdf->download('presensi_volunteer.pdf');
}





///////////////////////////////////KOORDINATOR CREATIVE/////////////////////////////////
public function home_koorcrv(){
    $user = Auth::user(); 
    $divisi=divisi::all();
    
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

public function task_mn()
{
    $user = Auth::user();

    // Ambil hanya tugas yang sesuai dengan divisi koordinator yang login
    $tugas = tugas::with(['volunteers', 'divisi'])
        ->where('divisi_id', $user->divisi_id)
        ->get();

    return view('task_mn', compact('tugas', 'user'));
}


public function tambah_task()
{
    $user = Auth::user();
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();

    // Inisialisasi volunteer
    $voldiv = collect(); // Default kosong

    if ($user->jabatan === 'Koordinator Divisi Creative') {
        // Ambil volunteer yang sub divisinya 'Desain' dalam divisi Creative
        $voldiv = volunteer::with('divisi', 'subdivisi')
            ->where('divisi_id', $user->divisi_id)
            ->whereHas('SubDivisi', function ($query) {
                $query->where('nama_subdivisi', 'Desain');
            })
            ->get();
    } elseif ($user->jabatan === 'Koordinator Divisi Konseling') {
        // Ambil semua volunteer dari divisi Konseling
        $voldiv = volunteer::with('divisi', 'subdivisi')
            ->where('divisi_id', $user->divisi_id)
            ->get();
    }

    return view('tambah_task', compact('divisi', 'voldiv', 'user'));
}


public function simpan_task(Request $request)
{
        $tugas = tugas::create([
            'divisi_id' => $request->divisi_id, 
            'desk_tgs' => $request->desk_tgs,
            'deadline' => $request->deadline,
            'link_gdrive' => $request->link_gdrive,
        ]);

        $tugas->volunteers()->sync($request->petugas); 
        return redirect('task_mn');
}

public function edit_task($tugas_id, Request $request)
{
    $user = Auth::user();
    
    // Ambil data tugas beserta relasi divisi dan volunteers
    $tugas = tugas::with(['divisi', 'volunteers'])->find($tugas_id);
    
    // Ambil divisi user yang sedang login
    $divisi = divisi::where('divisi_id', $user->divisi_id)->first();
    
    // Ambil semua volunteer yang statusnya 'Aktif' dan sub divisinya 'Desain'
    $allVolunteers = volunteer::where('status', 'Aktif')
                               ->whereHas('subDivisi', function ($query) {
                                   $query->where('nama_subdivisi', 'Desain'); // ✅ diperbaiki
                               })
                               ->get();
    
    // Ambil ID volunteer yang sudah terkait dengan tugas
    $selectedVolunteers = $tugas->volunteers->pluck('vol_id')->toArray();
    
    return view('edit_task', compact('tugas', 'selectedVolunteers', 'user', 'allVolunteers', 'divisi'));
}


public function updateTask(Request $request, $tugas_id)
{
    $request->validate([
        'divisi_id' => 'required|exists:divisi,divisi_id',
        'desk_tgs' => 'required|string',
        'deadline' => 'required|date',
        'volunteers' => 'nullable|array',
        'volunteers.*' => 'exists:volunteer,vol_id',
    ]);

    $tugas = tugas::findOrFail($tugas_id);
    $tugas->update([
        'divisi_id' => $request->divisi_id,
        'desk_tgs' => $request->desk_tgs,
        'deadline' => $request->deadline,
    ]);

    // Simpan relasi volunteer ke tugas_volunteer
    // Cuma ID volunteer (tanpa kolom tambahan dulu)
    $volunteers = $request->volunteers ?? [];

    $syncData = [];
    foreach ($volunteers as $vol_id) {
        $syncData[$vol_id] = [
            'status' => 'Belum Dikerjakan', // default atau bisa disesuaikan dari input
            'peran' => null,
            'status_validasi' => 'Pending',
            'revisi_catatan' => null,
        ];
    }

    $tugas->volunteers()->sync($syncData);

    return redirect('/task_mn')->with('success', 'Tugas dan data volunteer berhasil diperbarui!');
}


public function hapus_task($tugas_id){
    $tugas = tugas::find($tugas_id);
    $tugas->delete();
    return redirect('/task_mn');
}

public function profile_koor() {
    $user = Auth::user();
    return view('profile_koor', compact('user'));
}

//sub divisi
public function sub_divisi()
{
    $user = Auth::user();

    // Ambil divisi_id dari user
    $divisiId = $user->divisi_id;

    // Ambil semua sub divisi yang terkait dengan divisi tersebut
    $subdivisi = SubDivisi::where('divisi_id', $divisiId)->get();

    return view('sub_divisi', compact('user', 'subdivisi'));
}


public function tambah_sub() {
    $user = Auth::user();
    return view('tambah_sub', compact('user'));
}

public function simpan_sub(Request $request)
{
        $subDivisi = SubDivisi::create([
            'divisi_id' => $request->divisi_id, 
            'nama_subdivisi' => $request->nama_subdivisi,
        ]);

        return redirect('sub_divisi');
}

public function edit_sub($sub_divisi_id) {
    $user = Auth::user();
    $subDiv = SubDivisi::findOrFail($sub_divisi_id);
    return view('edit_sub', compact('user', 'subDiv'));
}

public function update_sub(Request $request, $sub_divisi_id)
{
    $request->validate([
        'nama_subdivisi' => 'required|string|max:255',
    ]);

    $subDiv = SubDivisi::findOrFail($sub_divisi_id);
    $subDiv->nama_subdivisi = $request->nama_subdivisi;
    $subDiv->save();

    return redirect('/sub_divisi')->with('success', 'Sub divisi berhasil diperbarui.');
}


public function hapus_sub($sub_divisi_id) {
    $sub = SubDivisi::findOrFail($sub_divisi_id);
    $sub->delete();
    return redirect('/sub_divisi')->with('success', 'Sub divisi berhasil dihapus.');
}
public function ajukanPeninjauan($vol_id)
{
    $volunteer = volunteer::findOrFail($vol_id);
    
    // Hanya jika status masih 'normal'
    if ($volunteer->status_etik === 'normal') {
        $volunteer->status_etik = 'dalam_peninjauan';
        $volunteer->save();
    }

    return redirect()->back()->with('success', 'Peninjauan status etik telah diajukan.');
}



public function edit_presensi($presensi_id) {
    $presensi = presensi::with('volunteer')->findOrFail($presensi_id); // PERBAIKI DI SINI
    return view('edit_presensi', compact('presensi'));
}


public function updatePresensi(Request $request, $presensi_id) {
    // Validasi input dari form
    $request->validate([
        'check_in' => 'required|date_format:Y-m-d\TH:i',
        'check_out' => 'required|date_format:Y-m-d\TH:i|after:check_in',
    ]);

    // Ambil data presensi beserta relasi ke jadwal
    $presensi = presensi::with('jadwal')->findOrFail($presensi_id);

    // Konversi input string ke datetime
    $checkIn = \Carbon\Carbon::parse($request->check_in);
    $checkOut = \Carbon\Carbon::parse($request->check_out);

    // Ambil data jadwal dari relasi
    $jadwal = $presensi->jadwal;

    if (!$jadwal) {
        return back()->withErrors([
            'jadwal' => 'Jadwal tidak ditemukan untuk presensi ini.'
        ])->withInput();
    }

    // Gabungkan tanggal jadwal + jam buka/tutup
    $tanggal = \Carbon\Carbon::parse($jadwal->tgl_jadwal);

    $jamBuka = $tanggal->copy()->setTimeFromTimeString($jadwal->jam_buka);   // ex: 08:00:00
    $jamTutup = $tanggal->copy()->setTimeFromTimeString($jadwal->jam_tutup); // ex: 17:00:00

    // Validasi waktu terhadap jadwal
    if ($checkIn->lt($jamBuka)) {
        return back()->withErrors([
            'check_in' => 'Check-in tidak boleh sebelum jam buka jadwal (' . $jamBuka->format('H:i') . ')'
        ])->withInput();
    }

    if ($checkOut->gt($jamTutup)) {
        return back()->withErrors([
            'check_out' => 'Check-out tidak boleh setelah jam tutup jadwal (' . $jamTutup->format('H:i') . ')'
        ])->withInput();
    }

    // Simpan jika valid
    $presensi->update([
        'check_in' => $checkIn,
        'check_out' => $checkOut,
    ]);

    return redirect('/data_presensi')->with('success', 'Jam presensi berhasil diperbarui!');
}

public function validasi_task(Request $request)
{
    $user = Auth::user();
    $filterApplied = false;

    // Ambil desk_tgs yang hanya punya volunteer dari divisi si koordinator
    $desk_tgs = tugas::whereHas('volunteers', function ($q) use ($user) {
        $q->where('divisi_id', $user->divisi_id);
    })->select('desk_tgs')->distinct()->get();

    $tugasFiltered = collect();

    if ($request->filled('desk_tgs')) {
        $filterApplied = true;

        $tugasFiltered = tugas::with(['volunteers' => function ($query) use ($user) {
                $query->where('divisi_id', $user->divisi_id);
            }])
            ->where('desk_tgs', $request->desk_tgs)
            ->get()
            ->filter(function ($tugas) {
                return $tugas->volunteers->isNotEmpty(); // ⬅️ ini ganti arrow function
            });
    }

    return view('validasi_task', compact('user', 'desk_tgs', 'tugasFiltered', 'filterApplied'));
}


public function submit(Request $request)
{
    $request->validate([
        'tugas_id' => 'required|exists:tugas,tugas_id',
        'vol_id' => 'required|exists:volunteer,vol_id',
        'status_validasi' => 'required|in:Selesai,Revisi',
        'revisi_catatan' => 'nullable|string',
    ]);

    // Siapkan data dasar
    $dataUpdate = [
        'status_validasi' => $request->status_validasi,
        'revisi_catatan' => $request->status_validasi === 'Revisi' ? $request->revisi_catatan : null,
        'updated_at' => now(),
    ];

    // Ubah status sesuai validasi
    if ($request->status_validasi === 'Revisi') {
        $dataUpdate['status'] = 'Sedang Dikerjakan';
    } elseif ($request->status_validasi === 'Selesai') {
        $dataUpdate['status'] = 'Tugas Selesai';
    }

    DB::table('tugas_volunteer')
        ->where('tugas_id', $request->tugas_id)
        ->where('vol_id', $request->vol_id)
        ->update($dataUpdate);

    return redirect()->back()->with('success', 'Status validasi berhasil diperbarui.');
}



public function formuploadSertif()
{
    $user = Auth::user(); // buat topbar tetap jalan
    return view('formuploadSertif', compact('user'));
}

public function uploadSertif(Request $request)
{
    $request->validate([
        'periode_ke' => 'required|integer|min:1|max:4',
        'files.*' => 'required|file|mimes:pdf|max:2048',
    ]);

    $files = $request->file('files');
    $periode = $request->periode_ke;

    $berhasil = 0;
    $gagal = 0;
    $ditolak = [];

    foreach ($files as $file) {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $volunteer = volunteer::where('nim', $filename)->first();

        if ($volunteer) {
            // Hitung total semester aktif (gunakan ceil agar volunteer pertengahan semester dihitung)
            $mulai = \Carbon\Carbon::parse($volunteer->mulai_aktif);
            $akhir = \Carbon\Carbon::parse($volunteer->akhir_aktif);
            $totalSemester = ceil($mulai->diffInMonths($akhir) / 6);
            $periodeMax = floor($totalSemester / 2); // 1 periode = 2 semester

            if ($periode <= $periodeMax) {
                // Cek apakah sudah pernah upload di periode ini
                $sudahAda = sertif::where('vol_id', $volunteer->vol_id)
                    ->where('periode_ke', $periode)
                    ->exists();

                if (!$sudahAda) {
                    $path = $file->store('sertif', 'public');

                    sertif::create([
                        'vol_id' => $volunteer->vol_id,
                        'periode_ke' => $periode,
                        'file_sertifikat' => $path,
                    ]);

                    $berhasil++;
                } else {
                    $ditolak[] = "$filename (sudah upload di periode ke-$periode)";
                }

            } else {
                $ditolak[] = "$filename (aktif $totalSemester semester → belum memenuhi periode ke-$periode)";
            }

        } else {
            $gagal++;
        }
    }

    // Bangun pesan umpan balik
    $message = "$berhasil file berhasil diupload.";
    if ($gagal > 0) $message .= " $gagal gagal dikenali (NIM tidak ditemukan).";
    if (count($ditolak) > 0) {
        $list = implode(', ', $ditolak);
        $message .= " File ditolak untuk: $list.";
    }

    return redirect()->back()->with('success', $message);
}

}