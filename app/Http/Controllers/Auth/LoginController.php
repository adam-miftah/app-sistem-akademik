<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      $user = Auth::user();
      if ($user->role === 'mahasiswa') {
        return redirect()->intended('/mahasiswa/dashboard');
      } elseif ($user->role === 'dosen') {
        return redirect()->intended('/dosen/dashboard');
      } elseif ($user->role === 'admin') {
        return redirect()->intended('/admin/dashboard');
      }
    }

    return back()->withErrors([
      'email' => 'Email atau password salah.',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }
}