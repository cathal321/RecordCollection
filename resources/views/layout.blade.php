<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Record Collection')</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
