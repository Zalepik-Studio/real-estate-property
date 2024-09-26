@extends('layouts/app')

@section('title', 'Dashboard')

@section('notifications')
@php
    $user = auth()->user();
@endphp

<div class="container">
    @if($notifications->isEmpty())
        <p>Belum ada notifikasi terkini</p>
    @else
        <ul>
            @foreach($notifications as $notification)
                @if($notification->user_id == $user->id)
                    <li>{{ $notification->notif_message }}</li>
                    <form action="{{ route('delete-notif') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $notification->id }}" name="id">
                    <button>Hapus</button>
                </form>
                @endif
            @endforeach
        </ul>
    @endif
</div>
@endsection
