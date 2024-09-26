@extends('auth.layouts.app')

@section('login')
    <div>
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">Email:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span>{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <span>{{ $message }}</span>
                @enderror
            </div>

            <a href="{{ url('forgot-password') }}">Lupa Password?</a>

            <div>
                <button type="submit">Login</button>
            </div>
            <span>Belum punya akun? <a href="/register">Daftar</a></span>
        </form>
    </div>
@endsection
