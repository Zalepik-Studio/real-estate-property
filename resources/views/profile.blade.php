@extends('layouts/app')

@section('title', $user->name)

@section('profile')

<?php
$user_id = auth()->user()->id;
?>

@if ($user)

<img src="{{ Storage::url('public/profile_pictures/' . $user->profile_picture) }}" style="border-radius: 50%; width: 55px; height: 55px;">

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

@if($user->id === $user_id)
<form action="{{ route('update-profile-picture') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="profile_picture" required>
    <button type="submit">Update Profile Picture</button>
</form>
@endif

<br>

@if($user->id === $user_id)
<form action="{{ route('delete-profile-picture') }}" method="POST">
    @csrf
    <button type="submit">Delete</button>
</form>
@endif

<form action="{{ route('chats') }}" method="post">
    @csrf
    <input type="hidden" value="{{ $user_id }}" name="sender_id">
    <input type="hidden" value="{{ $user->id }}" name="receiver_id">
    <button type="submit">Kirim Pesan</button>
</form>

<p>Nama: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Nomor Telepon: {{ $user->phone_number }}</p>

@if($user->id === $user_id)
<a href="{{ url('update-profile') }}">Update Profile</a>
@endif

@if($user->properties->isNotEmpty())
    <ul>
        @foreach($user->properties as $property)
        <li>
            <div style="display: flex; align-items: center;">
                <a href="{{ url('/user?id=' . $property->user_id) }}">
                    <img src="{{ Storage::url('public/profile_pictures/' . $property->user->profile_picture) }}" alt="{{ $property->user->profile_picture }}" style="border-radius: 50%; width: 55px; height: 55px;">
                </a>                
                <p style="margin-left: 10px;"><a href="{{ url('/user?id=' . $property->user_id) }}">{{ $property->user->name }}</a></p>
            </div>
            
            @if($user->id === $user_id)
            <form action="{{ route('delete-property') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <button type="submit">Delete</button>
            </form>

            <a href="{{ route('update-property', ['id' => $property->id]) }}">Update</a>

            @endif
            <form action="{{ route('visit') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $property->user_id }}" name="user_id">
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <button type="submit" style="cursor: pointer;">
                    <a href="/property?id={{ $property->id }}">
                        <h3>{{ $property->property_name }}</h3>
                        <p>{{ $property->property_desc }}</p>
                        <p>Lokasi: {{ $property->property_location }}</p>
                        <p>Harga: Rp{{ number_format($property->property_price, 0, ',', '.') }}</p>
                        <ul>
                            @foreach ($property->files as $file)
                                @php
                                    $filePath = Storage::url('public/images/' . $file->property_file);
                                    if (!Storage::exists('public/images/' . $file->property_file)) {
                                        $filePath = Storage::url('public/videos/' . $file->property_file);
                                    }
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                @endphp
                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                    <li><img style="max-width: 750px;" src="{{ $filePath }}" alt="{{ $file->property_file }}"></li>
                                @elseif (in_array($fileExtension, ['mp4']))
                                    <li>
                                        <video style="max-width: 750px;" controls>
                                            <source src="{{ $filePath }}" type="video/{{ $fileExtension }}">
                                            Browser Anda tidak mendukung pemutaran video ini
                                        </video>
                                    </li>
                                @endif
                            @endforeach
                        </ul>                                       
                    </a>
                </button>
                <p>Dokumen Pendukung</p>
                <ul>
                    @foreach ($property->files as $file)
                        @php
                            $filePath = Storage::url('public/documents/' . $file->property_file);
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        @endphp
            
                        @if (in_array($fileExtension, ['doc', 'docx', 'pdf', 'xls', 'xlsx']))
                            <li><a href="{{ $filePath }}">{{ $file->property_file }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </form>
            <span>Dikunjungi sebanyak {{ $property->visitsCount() }} kali</span>
        </li>
        @endforeach
    </ul>
@endif

@else
<p>Pengguna tidak ditemukan</p>
@endif

@endsection
