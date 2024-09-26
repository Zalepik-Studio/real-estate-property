<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $token = Str::random(32);
                $tokenExpiredAt = Carbon::now()->addMinutes(15); 
                $user->update(['token' => $token, 'token_expired_at' => $tokenExpiredAt]);

                Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($user) {
                    $message->to($user->email)->subject('Reset Password');
                });

                return redirect()->route('forgot-password')->with('status', 'Kami telah mengirim tautan ke ' . $request->email . ' untuk mereset password Anda');
            }

            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        return view('auth/forgot-password');
    }
}
