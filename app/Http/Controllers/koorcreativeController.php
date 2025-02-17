<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class koorcreativeController extends Controller
{
    public function home_koorcrv(){
        return view('home_koorcrv');
    }

    public function task_mn(){
        return view('task_mn');
    }

    public function tambah_task(){
        return view('tambah_task');
    }

    public function edit_task(){
        return view('edit_task');
    }
}
