@extends('auth.layouts.app')

@section('login')
<div>
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Nama:</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="phone_number">Nomor Telepon:</label>
            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" required>
            @error('phone_number')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="confirm_password">Konfirmasi Password:</label>
            <input id="confirm_password" type="password" name="confirm_password" required>
            @error('confirm_password')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="gender">Daftar Sebagai:</label>
            <div>
                <label for="customer">Customer</label>
                <input type="radio" id="customer" name="role" value="customer" @if(old('role')=='customer' ) checked @endif required>

                <label for="developer">Developer</label>
                <input type="radio" id="developer" name="role" value="developer" @if(old('gender')=='developer' ) checked @endif required>
            </div>

            @error('role')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit">Daftar</button>
        </div>
        <span>Sudah punya akun? <a href="/login">Login</a></span>
    </form>
</div>
@endsection