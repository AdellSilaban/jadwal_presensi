<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            // Validasi data
            $request->validate([
                'nama' => 'required',
                'jabatan' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'divisi_id' => 'required|exists:divisi,divisi_id',
            ]);
        
            // Simpan user
            user::create([
                'divisi_id' => $request->divisi_id,
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


     public function ceklogin(Request $request)
     {
         $datalogin = [
             'email' => $request->email,
             'password' => $request->password,
         ];
     
         if (Auth::attempt($datalogin)) {
             $user = Auth::user();
     
             if ($user->jabatan === 'Kepala LPKKSK') {
                 return redirect('/home_kepalaPKK');
             }
     
             if (
                 $user->jabatan === 'Koordinator Divisi Creative' ||
                 $user->jabatan === 'Koordinator Divisi Tim Ibadah Kampus' ||
                 $user->jabatan === 'Koordinator Divisi Konseling'
             ) {
                 return redirect('/home_koor');
             }
     
             // Jika jabatan tidak dikenali
             Auth::logout();
             return redirect('/login')->with('error', 'Jabatan tidak dikenali.');
         }
     
         // Jika login gagal
         return redirect('/login')->with('error', 'Email atau password salah.');
     }
     

     public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function ubah_pass()
{
    $user = Auth::user();
    return view('ubah_pass', compact('user'));
}

public function update_pass(Request $request)
{
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->with('error', 'Password lama salah.');
    }

    $user->password = Hash::make($request->password_baru);
    $user->save();

    Auth::logout();
    return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login kembali.');

}

}
