@extends('layouts/app')

@section('title', 'Direct Chats')

@php
$user = auth()->user();
@endphp

@section('chats')
<section id="messages">
</section>

<form id="sendMessage" enctype="multipart/form-data">
    @csrf
    <input type="hidden" value="{{ $chats->first()->chat_id }}" name="chat_id">
    <input type="hidden" value="{{ $user->id }}" name="sender_id">
    <input type="hidden" value="{{ $chats->first()->sender_id }}" name="receiver_id">

    <input type="file" id="file" name="file[]" multiple><br>
    <input type="text" name="message"><br>
    <button type="submit">Kirim Pesan</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchMessages();
        setInterval(fetchMessages, 1000);
    });

    function fetchMessages() {
        const chat_id = '{{ $chat_id }}';
        fetch(`{{ url('get-messages') }}?chat_id=${chat_id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('messages').innerHTML = html;
        })
        .catch(error => {
            console.error(error);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("sendMessage").addEventListener("submit", function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('send-message') }}");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        console.log(response.message);
                    } else {
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.send(formData);
        });
    });
</script>
@endsection
