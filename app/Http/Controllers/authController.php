<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    // Validasi input
    $request->validate([
        'nama' => 'required',
        'jabatan' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'divisi_id' => 'required|exists:divisi,divisi_id',
    ]);

    // Jika jabatan adalah koordinator, pastikan belum ada yang aktif di divisi itu
    if (str_contains($request->jabatan, 'Koordinator')) {
        $cek = User::where('divisi_id', $request->divisi_id)
            ->where('jabatan', 'like', 'Koordinator%')
            ->where('status', 'Aktif')
            ->first();

        if ($cek) {
            return back()
                ->withInput()
                ->with('flash', 'Koordinator untuk divisi ini sudah aktif. Tidak dapat mendaftar.')
                ->with('flash_type', 'danger');
        }
    }

    // Simpan user baru
    User::create([
        'divisi_id' => $request->divisi_id,
        'nama' => $request->nama,
        'jabatan' => $request->jabatan,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'status' => 'Aktif' // <- ditambahkan agar status default = aktif
    ]);

    return redirect('/login')
        ->with('flash', 'YEY BERHASIL')
        ->with('flash_type', 'success');
}

        
         

    public function login(){
        return view('login');
     }


//      public function ceklogin(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);
    
//     // Ambil user berdasarkan email
//     $user = User::where('email', $request->email)->first();

//     // Cek apakah user ditemukan dan statusnya aktif
//     if (!$user || $user->status !== 'Aktif') {
//         return redirect('/login')->with('error', 'Akun tidak aktif atau tidak ditemukan.');
//     }

//     // Lanjut login jika akun aktif
//     $credentials = [
//         'email' => $request->email,
//         'password' => $request->password,
//     ];

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
//         Log::info('LOGIN BERHASIL');

//         if ($user->jabatan === 'Kepala LPKKSK') {
//             return redirect('/home_kepalaPKK');
//         }

//         if (
//             $user->jabatan === 'Koordinator Divisi Creative' ||
//             $user->jabatan === 'Koordinator Divisi Tim Ibadah Kampus' ||
//             $user->jabatan === 'Koordinator Divisi Konseling'
//         ) {
//             return redirect('/home_koor');
//         }
       


//         // Jika jabatan tidak dikenali
//         Auth::logout();
//         return redirect('/login')->with('error', 'Jabatan tidak dikenali.');
//     }

//     return back()->with('error', 'Email atau password salah.');
// }

public function ceklogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->status !== 'Aktif') {
        return back()->with('error', 'Akun tidak aktif atau tidak ditemukan.');
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        Log::info('LOGIN BERHASIL', ['jabatan' => $user->jabatan]);

        if ($user->jabatan === 'Kepala LPKKSK') {
            return redirect('/home_kepalaPKK');
        }

        if (in_array($user->jabatan, [
            'Koordinator Divisi Creative',
            'Koordinator Divisi Tim Ibadah Kampus',
            'Koordinator Divisi Konseling'
        ])) {
            return redirect('/home_koor');
        }

        Auth::logout();
        return back()->with('error', 'Jabatan tidak dikenali.');
    }

    return back()->with('error', 'Email atau password salah.');
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
