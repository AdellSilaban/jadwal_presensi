<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\divisi;
use App\volunteer;
use App\jadwal;


class koorController extends Controller
{
    public function main(){
        return view('layout/main');
}

    public function home_koorLive(){
        $divisi = divisi::all();
        //ngambil data volunteer berdasarkan divisi
        $volunteer = volunteer::with('divisi')->whereHas('divisi', function($query) {
            $query->where('nama_divisi', 'PKK Live');
        })->get();
        return view('home_koorLive', compact('divisi'), compact('volunteer'));
    }


    public function jadwal_vltLive(){
        return view('jadwal_vltLive');
    }

    public function validasi_presensiLive(){
        return view('validasi_presensiLive');
    }

    public function tambah_vltLive(){
        return view('tambah_vltLive');
    }

    public function simpanVltLive(Request $request){
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
            return redirect('home_koorLive')->with('flash', 'YEY BERHASIL')->with('flash_type', 'success');

    }

    public function edit_vltLive(){
        return view('edit_vltLive');
    }

    public function tambah_jdwlLive(){
        return view('tambah_jdwlLive');
    }

    public function edit_jdwlLive(){
        return view('edit_jdwlLive');
    }

    public function cekEmail(Request $request)
{
    $email = $request->email;

    // Logic untuk memeriksa apakah email sudah ada di database
    $user = User::where('email', $email)->first();

    if ($user) {
        return response()->json(['status' => 'not available']);
    } else {
        return response()->json(['status' => 'available']);
    }
}
    
}
