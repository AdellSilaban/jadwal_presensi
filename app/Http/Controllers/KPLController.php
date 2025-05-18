<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\User;
use App\divisi;
use App\volunteer;
use App\jadwal;
use App\tugas;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

class KPLController extends Controller
{


public function dashboard()
{
    $user = Auth::user();
    $total_volunteer = volunteer::count();

    $vol_aktif = volunteer::where('status', 'Aktif')->count();
    $vol_tidak_aktif = volunteer::where('status', 'Tidak Aktif')->count();

    $total_tugas_selesai = DB::table('tugas_volunteer')
        ->where('status_validasi', 'Selesai')
        ->count();

    $total_jam_presensi = DB::table('presensi')
        ->whereNotNull('check_in')
        ->whereNotNull('check_out')
        ->get()
        ->reduce(function ($carry, $row) {
            $start = Carbon::parse($row->check_in);
            $end = Carbon::parse($row->check_out);
            return $carry + $end->diffInMinutes($start);
        }, 0);

    $total_jam_presensi = floor($total_jam_presensi / 60);

    $volunteerPerDivisi = DB::table('volunteer')
    ->join('divisi', 'volunteer.divisi_id', '=', 'divisi.divisi_id')
    ->where('volunteer.status', 'Aktif') // 👈 filter aktif aja
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
        $vlt->total_hari = $akhir->diffInDays($mulai) + 1;
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
            'desk_divisi' => 'required|array|min:1', // pastikan berupa array dan minimal 1 isi
            'desk_divisi.*' => 'nullable|string',  // setiap item harus string
        ]);
    
        // Gabungkan array menjadi string bullet list
        $deskDivisiArray = array_filter($request->desk_divisi); // hilangkan input kosong
        $deskDivisiText = '• ' . implode("\n• ", $deskDivisiArray);
    
        divisi::create([
            'nama_divisi' => $request->nama_divisi,
            'desk_divisi' => $deskDivisiText,
        ]);
    
        return redirect('/div_kepalaPKK')->with('success', 'Data divisi berhasil disimpan.');
    }
    

public function edit_div($divisi_id, Request $request){
    $user = Auth::user();
    $divisi = divisi::find($divisi_id);
    return view('edit_div', compact('divisi', 'user'));
}

public function updateDiv(Request $request, $divisi_id)
{
    $request->validate([
        'nama_divisi' => 'required',
        'desk_divisi' => 'required|array|min:1',
        'desk_divisi.*' => 'nullable|string',

    ]);

    $deskDivisiArray = array_filter($request->desk_divisi); // buang yang kosong
    $deskDivisiText = '• ' . implode("\n• ", $deskDivisiArray);

    $divisi = divisi::findOrFail($divisi_id);
    $divisi->update([
        'nama_divisi' => $request->nama_divisi,
        'desk_divisi' => $deskDivisiText,
    ]);

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

public function nonaktifKoor($id)
{
    $user = User::findOrFail($id);
    $user->status = 'Tidak Aktif';
    $user->save();

    return back()->with('flash', 'Koordinator berhasil dinonaktifkan.')->with('flash_type', 'success');
}


}

