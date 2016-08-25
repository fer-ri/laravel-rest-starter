<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up Confirmation</title>
</head>
<body>
    <h1>Thanks for signing up!</h1>

    <p>
        Please <a href='{{ url("auth/activate/{$user->activation_code}") }}'>confirm your email address</a>!
    </p>
</body>
</html>