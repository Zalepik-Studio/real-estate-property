<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <p>Klik tautan berikut untuk mereset password Anda</p>
    <a href="{{ url('reset-password/' . $token) }}">Reset Password</a>
</body>
</html>