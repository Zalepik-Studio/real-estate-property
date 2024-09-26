<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotifController extends Controller
{
    public function notifications()
    {
        $notifications = Notifications::orderBy('created_at', 'desc')->get();
        return view('notifications', compact('notifications'));
    }
    
    public function deleteNotif(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
    
        Notifications::where('id', $request->id)->delete();
    
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    } 
}
