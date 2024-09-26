@extends('layouts/app')

@section('title', $property->property_name)

@section('detail-property')

<?php
$user_id = auth()->user()->id;
?>

<section>
    <div style="display: flex; align-items: center;">
        <a href="{{ url('/user?id=' . $property->user_id) }}">
            <img src="{{ Storage::url('public/profile_pictures/' . $property->user->profile_picture) }}" style="border-radius: 50%; width: 55px; height: 55px;">
        </a>        
        <p style="margin-left: 10px;"><a href="{{ url('/user?id=' . $property->user_id) }}">{{ $property->user->name }}</a></p>
    </div>

    @if($property->user_id === $user_id)
    <form action="{{ route('delete-property') }}" method="POST">
        @csrf
        <input type="hidden" value="{{ $property->id }}" name="property_id">
        <button type="submit">Delete</button>
    </form>

    <a href="{{ route('update-property', ['id' => $property->id]) }}">Update</a>
    @endif

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
    <span>Dikunjungi sebanyak {{ $property->visitsCount() }} kali</span>
</section>
@endsection