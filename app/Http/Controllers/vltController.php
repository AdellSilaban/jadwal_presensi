<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class vltController extends Controller
{
    public function home_vlt(){
        return view('home_vlt');
    }

    public function home_vltcreative(){
        return view('home_vltcreative');
    }

    public function profile_vlt(){
        $user = auth()->user(); // Asumsikan Anda sudah melakukan autentikasi
        $data = $this->getDataProfil($user, $divisi); // Fungsi untuk mengambil data profil berdasarkan divisi
        
        return view('profile', compact('data', 'divisi'));
    }

}
