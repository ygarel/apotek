<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;  
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function index()
    {
        $pengguna = User::all();
        return view('pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        $pengguna = User::all();
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|min:3',
            'email'=> 'required|email',
            'role'=> 'required',
        ]);

        $defaultPassword = Str::substr($request->email, 0, 3) . Str::substr($request->name, 0, 3);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
            'password' => bcrypt($defaultPassword),
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambahkan Akun!');
    }

    public function edit($id)
    {
        $pengguna = User::find($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email'=> 'required|email',
            'role'=> 'required',
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
            'password' => bcrypt($defaultPassword),
        ]);
        return redirect()->route('pengguna.akun')->with('success', 'Berhasil Mengubah Data');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil Menghapus Data');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.alpha_dash' => 'Password harus diisi huruf dan karakter tanpa spasi',
        ]);

        $user = $request->only(['email', 'password']);
        if(Auth::attempt($user)) {
            return redirect()->route('home.page');
        } else {
            return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan data yang benar!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout!');
    }
}

?>