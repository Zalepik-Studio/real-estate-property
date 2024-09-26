@extends('layouts/app')

@section('title', 'Properties')

@section('get-properties')

@php
    $user = auth()->user();
    $user_id = auth()->user()->id;
@endphp

<section>
    <ul>
        @foreach ($properties as $property)
            <li>
                <div style="display: flex; align-items: center;">
                    <a href="{{ url('/user?id=' . $property->user_id) }}">
                        <img src="{{ Storage::url('public/profile_pictures/' . $property->user->profile_picture) }}" alt="{{ $property->user->profile_picture }}" style="border-radius: 50%; width: 55px; height: 55px;">
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

                <form action="{{ route('visit') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $property->user_id }}" name="user_id">
                    <input type="hidden" value="{{ $property->id }}" name="property_id">
                    <button type="submit" style="border: none; background: none; cursor: pointer;">
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
                                        <span>Video Properti</span>
                                        <a href="{{ $filePath }}">{{ $filePath }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </button>
                </form>

                <img style="max-width: 40px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"> <span>{{ number_format($property->averageStars(), 1) }}</span>

                <p>Dokumen Pendukung:</p>
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
            </li>
            @if ($property->stars->isEmpty())
            <form style="display: inline-block; margin-right: 5px;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="1" name="star">
                <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
            </form>

    <form style="display: inline-block; margin-right: 5px;">
        @csrf
        <input type="hidden" value="{{ $property->id }}" name="property_id">
        <input type="hidden" value="2" name="star">
        <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
    </form>

    <form style="display: inline-block; margin-right: 5px;">
        @csrf
        <input type="hidden" value="{{ $property->id }}" name="property_id">
        <input type="hidden" value="3" name="star">
        <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
    </form>

    <form style="display: inline-block; margin-right: 5px;">
        @csrf
        <input type="hidden" value="{{ $property->id }}" name="property_id">
        <input type="hidden" value="4" name="star">
        <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
    </form>

    <form style="display: inline-block; margin-right: 5px;">
        @csrf
        <input type="hidden" value="{{ $property->id }}" name="property_id">
        <input type="hidden" value="5" name="star">
        <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
    </form>
    @else
            @foreach ($property->stars as $stars)
            <form style="display: inline-block; margin-right: 5px;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="1" name="star">
                @if ($stars->star >= 1)
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"></button>
                @else
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
                @endif
            </form>
    
            <form style="display: inline-block; margin-right: 5px;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="2" name="star">
                @if ($stars->star >= 2)
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"></button>
                @else
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
                @endif
            </form>
    
            <form style="display: inline-block; margin-right: 5px;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="3" name="star">
                @if ($stars->star >= 3)
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"></button>
                @else
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
                @endif
            </form>
    
            <form style="display: inline-block; margin-right: 5px;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="4" name="star">
                @if ($stars->star >= 4)
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"></button>
                @else
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
                @endif
            </form>
    
            <form style="display: inline-block;">
                @csrf
                <input type="hidden" value="{{ $property->id }}" name="property_id">
                <input type="hidden" value="5" name="star">
                @if ($stars->star >= 5)
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/gold-star.png') }}"></button>
                @else
                    <button type="button" onclick="stars(this)"><img style="max-width: 30px;" src="{{ Storage::url('public/assets/images/white-star.png') }}"></button>
                @endif
            </form>
        @endforeach
        @endif
        @endforeach
    </ul>
</section>

@endsection

