<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
            ]);

            if ($request->password !== $request->confirm_password) {
                return back()->withErrors([
                    'confirm_password' => 'Konfirmasi password tidak sama',
                ])->withInput();
            }

            $user = User::where('token', $request->token)->first();

            if (!$user || Carbon::now()->gt($user->token_expired_at)) {
                return redirect()->route('forgot-password')->with('status', 'Token tidak valid atau sudah kadaluarsa');
            }

            $user->update([
                'password' => Hash::make($request->password),
                'token' => null,
                'token_expired_at' => null,
            ]);

            return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset');
        }

        return view('auth/reset-password', ['token' => $request->route('token')]);
    }
}
