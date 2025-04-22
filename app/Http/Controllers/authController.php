<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\divisi;
use App\User;

class authController extends Controller
{
    public function register(){
        $divisi=divisi::all();
            return view('register', compact('divisi')); 
        }
    
        public function simpanRegis(Request $request)
        {
            // Validasi dasar
            $request->validate([
                'nama' => 'required',
                'jabatan' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                // divisi_id hanya wajib jika bukan Kepala LPKKSK
                'divisi_id' => $request->jabatan !== 'Kepala LPKKSK' 
                    ? 'required|exists:divisi,divisi_id' 
                    : 'nullable',
            ]);
        
            // Simpan user
            User::create([
                'divisi_id' => $request->jabatan === 'Kepala LPKKSK' ? null : $request->divisi_id,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        
            return redirect('/login')
                ->with('flash', 'YEY BERHASIL')
                ->with('flash_type', 'success');
        }
         

    public function login(){
        return view('login');
     }


     public function ceklogin(Request $request) {
        $datalogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($datalogin)) {
            if (Auth::user()->jabatan == 'Kepala LPKKSK') {
                return redirect('/home_kepalaPKK');

            } elseif (Auth::user()->jabatan == 'Koordinator Divisi PKK Live' ||
                      Auth::user()->jabatan == 'Koordinator Divisi Tim Ibadah_Kampus' ||
                      Auth::user()->jabatan == 'Koordinator Divisi Konseling' ||
                      Auth::user()->jabatan == 'Koordinator Divisi Creative' ) {
                return redirect('/home_koor');

        }
    }
}

     public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
