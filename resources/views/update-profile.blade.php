@extends('layouts/app')

@section('title', 'Update Profile')

@section('update-profile')

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('/update-profile') }}" method="post">
    @csrf
    <label for="name">Nama:</label>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
    <br>
    <label for="phone_number">Nomor Telepon:</label>
    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
    <br>
    <button type="submit">Update</button>
</form>

@endsection
