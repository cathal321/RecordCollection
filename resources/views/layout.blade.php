<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Record Collection')</title>
    <style>
        :root {
            --bg: #121212;
            --surface: #1e1e1e;
            --border: #333;
            --text: #e4e4e4;
            --text-muted: #9a9a9a;
            --accent: #8ab4f8;
        }

        html {
            font-size: 125%;
        }


        body {
            margin: 0;
            font-family: sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        a { color: var(--accent); }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
