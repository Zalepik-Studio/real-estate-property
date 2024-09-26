@extends('auth/layouts/app') 

@section('forgot-password')
    <div>
        @if (session('status'))
            <div>
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('forgot-password') }}" method="POST">
            @csrf

            <div>
                <label for="email">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span>
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
