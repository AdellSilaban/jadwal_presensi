<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\User;
use App\divisi;
use App\volunteer;
use App\jadwal;
use App\tugas;

class KPLController extends Controller
{
    public function home_kepalaPKK(){
        $user = Auth::user(); 
        $volunteer=volunteer::all();
        return view('home_kepalaPKK', compact('user', 'volunteer'));
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

}
