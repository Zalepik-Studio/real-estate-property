@extends('layouts/app')

@section('title', 'Chats')

@section('get-messages')

@php
$user = auth()->user();
@endphp

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

<section>
    @foreach ($chats as $chat)
    @if($chat->chat_label != 'start chats')
    <div>
        <div style="display: flex; align-items: center;">
            <a href="/user?id={{ $chat->sender->id }}">
                <img src="{{ Storage::url('public/profile_pictures/' . $chat->sender->profile_picture) }}" style="border-radius: 50%; width: 45px; height: 45px;">
            </a>
            <p style="margin-left: 10px;"><a href="/user?id={{ $chat->sender->id }}">{{ $chat->sender->name }}</a></p>
        </div> 
        <span>{{ $chat->message }}</span>
        @if($chat->files->count() > 0)
        <div>
            @foreach($chat->files as $chat_files)
                @php
                    $extension = pathinfo($chat_files->file, PATHINFO_EXTENSION);
                @endphp
                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                    <img src="{{ Storage::url('public/images/'.$chat_files->file) }}" alt="{{ $chat_files->file }}" style="max-width: 400px;">
                @elseif($extension == 'mp4')
                        <a href="{{ Storage::url('public/videos/' . $chat_files->file) }}">{{ $chat_files->file }}</a>
                        @if($chat->sender_id == auth()->user()->id)
                        <button onclick="deleteMessage({{ $chat->id }})">Hapus Pesan</button>
                    @endif
                @else
                    <a href="{{ Storage::url('public/documents/'.$chat_files->file) }}">{{ $chat_files->file }}</a>
                    @if($chat->sender_id == auth()->user()->id)
                    <button onclick="deleteMessage({{ $chat->id }})">Hapus Pesan</button>
                @endif
                @endif
                <br>
            @endforeach
        </div>              
        @endif
        @if($chat->sender_id == auth()->user()->id)
            <button onclick="deleteMessage({{ $chat->id }})">Hapus Pesan</button>
        @endif
        <span>{{ $chat->created_at }}</span>
    </div>
    @endif
    @endforeach
</section>

@endsection
