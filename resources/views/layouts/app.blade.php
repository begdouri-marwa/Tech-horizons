<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tech Horizons')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <ul>
                @auth
                    <!-- Role-based Navigation -->
                    @if(auth()->user()->role === 'subscriber')
                        <li><a href="{{ route('subscriber.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('subscriber.themes') }}">Themes</a></li>
                        <li><a href="{{ route('subscriber.history') }}">History</a></li>
                    @elseif(auth()->user()->role === 'theme_manager')
                        <li><a href="{{ route('theme_manager.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('theme_manager.themes') }}">Moderate Themes</a></li>
                        <li><a href="{{ route('theme_manager.articles') }}">Moderate Articles</a></li>
                        <li><a href="{{ route('theme_manager.proposed_articles') }}">Proposed Articles</a></li>
                    @elseif(auth()->user()->role === 'editor')
                        <li><a href="{{ route('editor.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('editor.users') }}">Manage Users</a></li>
                        <li><a href="{{ route('editor.themes') }}">Manage Themes</a></li>
                        <li><a href="{{ route('editor.issues') }}">Manage Issues</a></li>
                    @endif

                    <!-- Common Authenticated User Links -->
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                @else
                    <!-- Guest Navigation -->
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('subscriber.themes') }}">Themes</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>© {{ date('Y') }} Tech Horizons. All rights reserved.</p>
    </footer>
</body>
</html>
