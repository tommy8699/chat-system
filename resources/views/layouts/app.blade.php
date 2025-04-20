<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatovací Systém</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav>
        <ul>
            <li><a href="{{ route('chats.index') }}">Chaty</a></li>
            <li><a href="{{ route('messages.index') }}">Správy</a></li>
            <li><a href="{{ route('auth.logout') }}">Odhlásiť sa</a></li>
        </ul>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
