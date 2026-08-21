<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('home');
        }else{
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {

        // dd($request->all());
        $data = [
            'nip' => $request->input('nip'),
            'password' => $request->input('password'),
        ];

        $check_mhs = User::where('nip', $request->input('nip'))->first();
        if (Auth::Attempt($data)) {
            if (strtolower($check_mhs->role->role) == 'mahasiswa') {

                if (!$check_mhs->mahasiswa->jurusan || 
                !$check_mhs->mahasiswa->prodi || 
                !$check_mhs->mahasiswa->ormawa) {
                    return redirect()->route('mahasiswa.edit', $check_mhs->mahasiswa->id);
                }
                else{
                    return redirect('/dashboard2');
                }
            }
            return redirect('/dashboard2');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}