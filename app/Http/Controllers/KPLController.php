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

class KPLController extends Controller
{
    public function home_kepalaPKK()
    {
        $user = Auth::user(); 
        $volunteer = volunteer::with(['divisi', 'subDivisi'])->get();
        $divisi = divisi::all();
    
        // Format tanggal dan hitung total hari untuk masing-masing volunteer
        $volunteer->map(function ($vlt) {
            $mulai = Carbon::parse($vlt->mulai_aktif);
            $akhir = Carbon::parse($vlt->akhir_aktif);
            $vlt->mulai = $mulai->format('Y-m-d');
            $vlt->akhir = $akhir->format('Y-m-d');
            $vlt->total_hari = $akhir->diffInDays($mulai) + 1;
            return $vlt;
        });
    
        return view('home_kepalaPKK', compact('user', 'volunteer', 'divisi'));
    }

    public function div_kepalaPKK(){
        $user = Auth::user(); 
        $divisi=divisi::all();
        return view('div_kepalaPKK', compact('divisi', 'user'));
    }

    public function tambah_div(){
        return view('tambah_div');
    }

    public function simpanDiv(Request $request){

        $request->validate([
            'nama_divisi' => 'required',
            'desk_divisi' => 'required'
        ]);
        
        divisi::create([
            'nama_divisi'=> $request->nama_divisi,
            'desk_divisi'=> $request->desk_divisi,
        ]);
        return redirect('/div_kepalaPKK');         
}

public function edit_div($divisi_id, Request $request){
    $user = Auth::user();
    $divisi = divisi::find($divisi_id);
    return view('edit_div', compact('divisi', 'user'));
}

public function updateDiv(Request $request, $divisi_id) { 
    $divisi = divisi::find($divisi_id);
    $divisi->update([
        'nama_divisi'=> $request->nama_divisi,
        'desk_divisi'=> $request->desk_divisi,
    ]);

    return redirect('/div_kepalaPKK')->with('success', 'Data volunteer berhasil diupdate!'); // Redirect dengan pesan sukses
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

}

