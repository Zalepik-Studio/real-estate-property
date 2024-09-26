@extends('layouts/app')

@section('title', 'Update Password')

@section('update-password')

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('update-password') }}">
    @csrf
    <div>
        <label for="old_password">Password Lama</label>
        <input type="password" name="old_password" id="old_password" required>
        @error('old_password')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="new_password">Password Baru</label>
        <input type="password" name="new_password" id="new_password" required>
        @error('new_password')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
        @error('new_password_confirmation')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <button type="submit">Update Password</button>
</form>

@endsection
