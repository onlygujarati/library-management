<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>You have borrowed the book: <strong>{{ $book->title }}</strong>.</p>

    <p>Thank you for using our library system!</p>

</body>
</html>