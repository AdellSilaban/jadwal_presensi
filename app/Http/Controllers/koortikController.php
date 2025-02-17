<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class koortikController extends Controller
{
    
    public function home_koorTik(){
        return view('home_koorTik');
    }


    public function jadwal_vltTik(){
        return view('jadwal_vltTik');
    }

    public function validasi_presensiTik(){
        return view('validasi_presensiTik');
    }

    public function tambah_vltTik(){
        return view('tambah_vltTik');
    }

    public function edit_vltTik(){
        return view('edit_vltTik');
    }

    public function tambah_jdwlTik(){
        return view('tambah_jdwlTik');
    }

    public function edit_jdwlTik(){
        return view('edit_jdwlTik');
    }
}
