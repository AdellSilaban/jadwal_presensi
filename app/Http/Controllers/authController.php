<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\divisi;
use App\User;

class authController extends Controller
{
    public function register(){
            return view('register'); 
        }
    
        public function simpanRegis(Request $request){
            User::create([
                'nama'=> $request->nama,
                'jabatan'=> $request->jabatan,
                'email'=> $request->email,
                'password'=> bcrypt($request->password)
            ]);
            return redirect('login')->with('flash', 'YEY BERHASIL')->with('flash_type', 'success');
        }    

    public function login(){
        return view('login');
     }

     public function ceklogin(Request $request){
        $datalogin = [
            'email' => $request -> email,
            'password' => $request -> password,
        ];
        if(Auth::attempt($datalogin)){ 
            //hak akses
            if(Auth::user()->jabatan == 'Kepala LPKKSK'){
                return redirect('/home_kepalaPKK');
            } 
            elseif(Auth::user()->jabatan =='Koordinator Divisi PKK Live'){
                return redirect('/home_koorLive');
            }
            elseif(Auth::user()->jabatan =='Koordinator Divisi Tim Ibadah Kampus'){
                return redirect('/home_koorTik');
            }
            elseif(Auth::user()->jabatan == 'Koordinator Divisi Konseling'){
                return redirect('/home_koorkonsul');
            }
            elseif(Auth::user()->jabatan =='Koodinator Divisi Creative'){
                return redirect('/home_koorcrv');
            }
        }
        else{
            return redirect('/login')->withErrors('Email atau Password tidak sesuai!')->withInput();
        }
    }



     public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
