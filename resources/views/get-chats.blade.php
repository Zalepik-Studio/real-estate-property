@extends('layouts/app')

@section('title', 'Direct Chats')

@section('fetch-chats')

<?php
$displayed_id = collect();
?>

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach($chats as $chat)
    @if(!$displayed_id->contains($chat->sender_id) || !$displayed_id->contains($chat->receiver_id))
        <section>
            <div style="display: flex; align-items: center;">
                <a href="/chats?chat_id={{ $chat->chat_id }}">
                    <img src="{{ Storage::url('public/profile_pictures/' . $chat->receiver->profile_picture) }}" style="border-radius: 50%; width: 55px; height: 55px;">
                </a>
                <p style="margin-left: 10px;"><a href="/chats?chat_id={{ $chat->chat_id }}">{{ $chat->receiver->name }}</a></p>
                <form action="{{ route('delete-chats') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $chat->chat_id }}" name="chat_id">
                    <button>Hapus chat</button>
                </form>
            </div>                  
        </section>
        <?php
            $displayed_id->push($chat->sender_id);
            $displayed_id->push($chat->receiver_id);
        ?>
    @endif
@endforeach
