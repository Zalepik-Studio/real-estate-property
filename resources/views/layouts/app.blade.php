<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Real Estate Property')</title>
</head>
<body>
    <header>

    </header>

    <aside>

    </aside>

    <main>
        @yield('dashboard')
        @yield('get-properties')
        @yield('notifications')
        @yield('add-property')
        @yield('update-property')
        @yield('profile')
        @yield('update-profile')
        @yield('update-password')
        @yield('detail-property')
        @yield('direct-chats')
        @yield('get-chats')
        @yield('chats')
        @yield('get-messages')
    </main>

    <script>
        function deleteMessage(chat_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('delete-message') }}");
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.setRequestHeader("X-CSRF-Token", '{{ csrf_token() }}');
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
            var data = JSON.stringify({ id: chat_id });
            xhr.send(data);
        }

        function stars(button) {
        var form = button.parentElement;
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ route('stars') }}");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response.message);
                } else {
                    console.error(error);
                }
            }
        };
        xhr.send(formData);
    }
    </script>
</body>
</html>