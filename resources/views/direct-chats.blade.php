@extends('layouts/app')

@section('title', 'Direct Chats')

@section('direct-chats')

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

<section id="chats">
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchChats();
        setInterval(fetchChats, 1000); 
    });

    function fetchChats() {
        fetch('{{ url('get-chats') }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('chats').innerHTML = html;
        })
        .catch(error => {
            console.error(error);
        });
    }
</script>
@endsection