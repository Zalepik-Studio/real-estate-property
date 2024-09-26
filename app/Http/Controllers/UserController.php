<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $userId = $request->query('id');
        $user = User::with('properties')->find($userId);

        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone_number' => 'required|string',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            return redirect()->route('profile', ['id' => $user->id])->with('success', 'Profil berhasil diperbarui');
        }

        return view('update-profile', compact('user'));
    }

    public function updateProfilePicture(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:25600',
        ]);

        if ($request->file('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/profile_pictures/' . $user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $fileName = $file->hashName();
            $file->storeAs('public/profile_pictures', $fileName);

            $user->update([
                'profile_picture' => $fileName,
            ]);

            return redirect()->route('profile', ['id' => $user->id])->with('success', 'Foto profil berhasil diperbarui');
        }
    }

    public function deleteProfilePicture()
    {
        $user = auth()->user();
    
        if ($user->profile_picture) {
            Storage::delete('public/profile_pictures/' . $user->profile_picture);
    
            $user->update(['profile_picture' => null]);
    
            return redirect()->route('profile', ['id' => $user->id])->with('success', 'Foto profil berhasil dihapus');
        }

        return redirect()->route('profile', ['id' => $user->id])->with('error', 'Foto profil kosong');
    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
            }

            $user->update(['password' => Hash::make($request->new_password)]);

            return redirect()->back()->with('success', 'Password berhasil diubah');
        }

        return view('update-password');
    }
}
