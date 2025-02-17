<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class koorkonsulController extends Controller
{
    public function home_koorkonsul(){
        return view('home_koorkonsul');
    }

    public function jadwal_vltkonsul(){
        return view('jadwal_vltkonsul');
    }

    public function validasi_presensikonsul(){
        return view('validasi_presensikonsul');
    }

    public function tambah_vltkonsul(){
        return view('tambah_vltkonsul');
    }

    public function edit_vltkonsul(){
        return view('edit_vltkonsul');
    }

    public function tambah_jdwlkonsul(){
        return view('tambah_jdwlkonsul');
    }

    public function edit_jadwalkonsul(){
        return view('edit_jadwalkonsul');
    }
}
