<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chats;
use App\Models\ChatFiles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ChatsController extends Controller
{
    public function createChat(Request $request)
    {
        $request->validate([
            'sender_id' => 'required',
            'receiver_id' => 'required',
        ]);

        $existingChats = Chats::where(function ($query) use ($request) {
            $query->where('sender_id', $request->sender_id)
                ->where('receiver_id', $request->receiver_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->receiver_id)
                ->where('receiver_id', $request->sender_id);
        })->first();

        if ($existingChats) {
            $chat = $existingChats;
        } else {
            $chat_id = Str::random(6);

            Chats::create([
                'chat_id' => $chat_id,
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'chat_label' => 'start chats',
            ]);

            $chat = Chats::create([
                'chat_id' => $chat_id,
                'sender_id' => $request->receiver_id,
                'receiver_id' => $request->sender_id,
                'chat_label' => 'start chats',
            ]);
        }

        return redirect()->route('chats', ['chat_id' => $chat->chat_id]);
    }

    public function getChats()
    {
        $user = auth()->user();

        $chats = Chats::where('sender_id', $user->id)
            ->orWhere('receiver_id', '!=', $user->id)
            ->get();
        return view('get-chats', ['chats' => $chats]);
    }

    public function deleteChats(Request $request)
    {
        $request->validate([
            'chat_id' => 'required',
        ]);

        $chats = Chats::where('chat_id', $request->chat_id)->get();

        if ($chats->isNotEmpty()) {
            foreach ($chats as $chat) {
                $chatFiles = ChatFiles::where('chat_id', $chat->id)->get();

                foreach ($chatFiles as $chatFile) {
                    Storage::delete('public/images/' . $chatFile->file);
                    Storage::delete('public/videos/' . $chatFile->file);
                    Storage::delete('public/documents/' . $chatFile->file);
                }

                ChatFiles::where('chat_id', $chat->id)->delete();
            }

            Chats::where('chat_id', $request->chat_id)->delete();

            return redirect()->back()->with('success', 'Obrolan berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Obrolan tidak ditemukan');
        }
    }

    public function deleteMessage(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $chatFiles = ChatFiles::where('chat_id', $request->id)->get();

        foreach ($chatFiles as $chatFile) {
            Storage::delete('public/images/' . $chatFile->file);
            Storage::delete('public/videos/' . $chatFile->file);
            Storage::delete('public/documents/' . $chatFile->file);
        }

        ChatFiles::where('chat_id', $request->id)->delete();

        Chats::where('id', $request->id)->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus']);
    }

    public function getMessages(Request $request)
    {
        $chat_id = $request->query('chat_id');

        if ($chat_id) {
            $chats = Chats::with('sender', 'receiver', 'files')
                ->where('chat_id', $chat_id)
                ->orderBy('id')
                ->orderBy('created_at')
                ->get();

            if ($chats->isNotEmpty()) {
                return view('get-messages', compact('chats', 'chat_id'));
            } else {
                return response()->json(['message' => 'Obrolan tidak ditemukan'], 404);
            }
        }
    }

    public function chats(Request $request)
    {
        $chat_id = $request->query('chat_id');

        if ($chat_id) {
            $chats = Chats::with('sender', 'receiver', 'files')
                ->where('chat_id', $chat_id)
                ->orderBy('id')
                ->orderBy('created_at')
                ->get();
            if ($chats->isNotEmpty()) {
                return view('chats', compact('chat_id', 'chats'));
            } else {
                return response()->json(['message' => 'Obrolan tidak ditemukan'], 404);
            }
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'sender_id' => 'required',
            'receiver_id' => 'required',
        ]);

        $chat = Chats::create([
            'chat_id' => $request->chat_id,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->input('message', null),
        ]);

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileName = $file->hashName();
                $mimeType = $file->getMimeType();

                if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                    $file->storeAs('public/images', $fileName);
                } elseif ($mimeType == 'video/mp4') {
                    $file->storeAs('public/videos', $fileName);
                } elseif (in_array($mimeType, [
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/pdf',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ])) {
                    $file->storeAs('public/documents', $fileName);
                }

                ChatFiles::create([
                    'chat_id' => $chat->id,
                    'file' => $fileName,
                ]);
            }
        }

        return response()->json(['message' => 'Pesan berhasil dikirim']);
    }
}
