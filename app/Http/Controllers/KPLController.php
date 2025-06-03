<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\User;
use App\divisi;
use App\volunteer;
use App\jadwal;
use App\tugas;
use App\desk_div;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

class KPLController extends Controller
{


public function dashboard(Request $request)
{
    $user = Auth::user();
    $total_volunteer = volunteer::count();

    $vol_aktif = volunteer::where('status', 'Aktif')->count();
    $vol_tidak_aktif = volunteer::where('status', 'Tidak Aktif')->count();

    $bulan_tugas = $request->bulan_tugas;
    $tahun_tugas = $request->tahun_tugas;

    $bulan_presensi = $request->bulan_presensi;
    $tahun_presensi = $request->tahun_presensi;

    // --- Total volunteer
    $total_volunteer = DB::table('volunteer')->count();
    $vol_aktif = DB::table('volunteer')->where('status', 'Aktif')->count();
    $vol_tidak_aktif = DB::table('volunteer')->where('status', 'Tidak Aktif')->count();

    // --- Presensi (Total Jam)
    $presensi = DB::table('presensi')
        ->whereNotNull('check_in')
        ->whereNotNull('check_out');

    if ($bulan_presensi && $tahun_presensi) {
        $presensi->whereMonth('check_in', $bulan_presensi)
                 ->whereYear('check_in', $tahun_presensi);
    }

    $total_jam_presensi = $presensi->get()->reduce(function ($carry, $row) {
        $start = Carbon::parse($row->check_in);
        $end = Carbon::parse($row->check_out);
        return $carry + $end->diffInMinutes($start);
    }, 0);
    $total_jam_presensi = floor($total_jam_presensi / 60); // menit → jam

    // --- Tugas selesai
    $tugas = DB::table('tugas_volunteer')->where('status_validasi', 'Selesai');

    if ($bulan_tugas && $tahun_tugas) {
        $tugas->whereMonth('updated_at', $bulan_tugas)
              ->whereYear('updated_at', $tahun_tugas);
    }

    $total_tugas_selesai = $tugas->count();

    // --- Volunteer per divisi
    $volunteerPerDivisi = DB::table('volunteer')
        ->join('divisi', 'volunteer.divisi_id', '=', 'divisi.divisi_id')
        ->where('volunteer.status', 'Aktif')
        ->select('divisi.nama_divisi', DB::raw('count(*) as total'))
        ->groupBy('divisi.nama_divisi')
        ->get();

    return view('dashboard', compact(
        'user',
        'total_volunteer',
        'vol_aktif',
        'vol_tidak_aktif',
        'total_tugas_selesai',
        'total_jam_presensi',
        'volunteerPerDivisi'
    ));
}



public function home_kepalaPKK()
{
    $user = Auth::user(); 
    $volunteer = volunteer::with(['divisi', 'subDivisi'])->get();
    $divisi = divisi::all();

    // Format tanggal dan hitung total hari untuk masing-masing volunteer
    $volunteer->map(function ($vlt) {
        $mulai = Carbon::parse($vlt->mulai_aktif);
        $akhir = Carbon::parse($vlt->akhir_aktif);
        $vlt->mulai = $mulai->format('d-m-Y');
        $vlt->akhir = $akhir->format('d-m-Y');
        $totalBulan = $mulai->diffInMonths($akhir) + 1;
        $vlt->total_bulan = $totalBulan;
        return $vlt;
    });

    return view('home_kepalaPKK', compact(
        'user', 
        'volunteer', 
        'divisi',
    ));
}


    public function div_kepalaPKK(){
        $user = Auth::user(); 
        $divisi=divisi::all();
        return view('div_kepalaPKK', compact('divisi', 'user'));
    }

    public function tambah_div(){
        $user = Auth::user();
        return view('tambah_div', compact('user'));
    }

    public function simpanDiv(Request $request)
{
    $request->validate([
        'nama_divisi' => 'required',
        'deskripsi' => 'required|array|min:1', // input array dari deskripsi
        'deskripsi.*' => 'nullable|string',    // setiap deskripsi harus berupa string
    ]);


    // Simpan divisi ke tabel `divisi`
    $divisi = divisi::create([
        'nama_divisi' => $request->nama_divisi,
    ]);

  foreach ($request->deskripsi as $desk) {
    if (!empty($desk)) {
        DB::table('desk_div')->insert([
            'divisi_id' => $divisi->divisi_id,
            'deskripsi' => $desk,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

return redirect('/div_kepalaPKK')->with('success', 'Data divisi berhasil disimpan.');

    return redirect('/div_kepalaPKK')->with('success', 'Data divisi berhasil disimpan.');
}

    

public function edit_div($divisi_id, Request $request)
{
    $user = Auth::user();

    $divisi = divisi::with('desk_div')->find($divisi_id);

    return view('edit_div', compact('divisi', 'user'));
}


public function updateDiv(Request $request, $divisi_id)
{
    $request->validate([
        'nama_divisi' => 'required',
        'deskripsi' => 'required|array|min:1',
        'deskripsi.*' => 'nullable|string',
        'deskripsi_id' => 'required|array|min:1',
        'deskripsi_id.*' => 'nullable|integer',
    ]);

    // Update nama divisi
    $divisi = divisi::findOrFail($divisi_id);
    $divisi->update([
        'nama_divisi' => $request->nama_divisi,
    ]);

   
foreach ($request->deskripsi as $index => $isi) {
    $id = $request->deskripsi_id[$index] ?? null;

    if ($id !== null && trim($isi) === '') {
        // Hapus deskripsi yang dikosongkan
        desk_div::where('deskripsi_id', $id)->delete();
    } elseif ($id !== null && $isi !== null) {
        // Update deskripsi yang diisi
        desk_div::where('deskripsi_id', $id)->update([
            'deskripsi' => $isi,
            'updated_at' => now()
        ]);
    } elseif ($id === null && trim($isi) !== '') {
        // Tambah deskripsi baru
        desk_div::create([
            'divisi_id' => $divisi_id,
            'deskripsi' => $isi,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

    return redirect('/div_kepalaPKK')->with('success', 'Data divisi berhasil diupdate!');
}



public function hapus_div($divisi_id){
    $divisi = divisi::find($divisi_id);
    $divisi->delete();
    return redirect('/div_kepalaPKK');
}

public function exportVolunteerPDF()
{
    $volunteer = volunteer::with('divisi')->get(); // ambil semua data volunteer beserta relasi divisi
    $pdf = PDF::loadView('vol_pdf', compact('volunteer'));
    return $pdf->download('data-volunteer.pdf');
}


public function update_divVol(Request $request, $vol_id)
    {
        // Validasi input
        $request->validate([
            'divisi_id' => 'required|exists:divisi,divisi_id'
        ]);

        // Cari volunteer-nya
        $volunteer = volunteer::findOrFail($vol_id);

        // Update divisi_id-nya
        $volunteer->divisi_id = $request->divisi_id;
        $volunteer->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Divisi volunteer berhasil diperbarui!');
    }

 public function hentikanVolunteer($vol_id)
{
    $volunteer = volunteer::findOrFail($vol_id);
    $volunteer->status_etik = 'Dihentikan';
    $volunteer->status = 'Tidak Aktif'; // Tambahan ini
    $volunteer->save();

    return redirect()->back()->with('success', 'Volunteer berhasil dihentikan.');
}


public function pulihkanVolunteer($vol_id)
{
    $volunteer = volunteer::findOrFail($vol_id);
    $volunteer->status_etik = 'Normal';
    $volunteer->save();

    return redirect()->back()->with('success', 'Volunteer berhasil dipulihkan.');
}

public function koor_kepalaPKK()
{
    $user = Auth::user();
    $koordinator = User::where('jabatan', 'like', 'Koordinator%')->with('divisi')->get();
    return view('koor_kepalaPKK', compact('koordinator', 'user'));
}

public function nonaktifKoor($user_id)
{
    $user = User::findOrFail($user_id);
    $user->status = 'Tidak Aktif';
    $user->save();

    return back()->with('flash', 'Koordinator berhasil dinonaktifkan.')->with('flash_type', 'success');
}


}

