@extends('layouts/app')

@section('title', 'Dashboard')

@section('dashboard')

@php
    $user = auth()->user();
@endphp

<form action="{{ route('search') }}" method="GET">
    <label for="search">Cari Properti</label>
    <input type="text" id="search" placeholder="Cari berdasarkan lokasi..." name="search">
    <button type="submit">Cari</button>
</form>

<div>
    <label for="filter">Filter</label>
    <select id="filter" onchange="filterProperties(this)">
        <option value="" selected disabled>Pilih opsi</option>
        <option value="most_visited">Paling banyak dikunjungi</option>
    </select>
</div>

@if ($user && ($user->role == 'admin' || $user->role == 'developer'))
    <a href="/add-property">Unggah Properti</a><br>
@endif

<a href="{{ url('/dashboard') }}">Home</a>
<a href="{{ url('/direct-chats') }}">Direct Chats</a>
<a href="{{ url('/notifications') }}">Notifikasi</a>
<a href="{{ url('/settings') }}">Settings</a>
<a href="{{ route('logout') }}">Logout</a>

<section id="getProperties">   
</section>

@endsection

<script>
    function filterProperties(select) {
        var filter = select.value;
        if (filter === 'most_visited') {
            window.location.href = '{{ route("most-visited") }}?filter=most_visited';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProperties();
        setInterval(fetchProperties, 1000); 
    });

    function fetchProperties() {
        fetch('{{ url('get-properties') }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('getProperties').innerHTML = html;
        })
        .catch(error => {
            console.error(error);
        });
    }
</script>

