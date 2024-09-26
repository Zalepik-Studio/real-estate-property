@extends('auth/layouts/app')

@section('reset-password')
<div>
    <div>{{ __('Reset Password') }}</div>

    @if (session('status'))
    <div>
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('reset-password') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">

            @error('password')
            <span role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" name="confirm_password" required autocomplete="new-password">
            
            @error('confirm_password')
            <span role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</div>
@endsection
