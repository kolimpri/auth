<!DOCTYPE html>
<html lang="en">
<head>
    @include('auth::layouts.common.head')
</head>
<body>
    <!-- Vue App For Auth Screens -->
    <div id="auth-app" v-cloak>
        <!-- Navigation -->
        @if (Auth::check())
            @include('auth::nav.authenticated')
        @else
            @include('auth::nav.guest')
        @endif

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('auth::common.footer')

        <!-- JavaScript Application -->
        <script src="{{ elixir('js/app.js') }}"></script>
    </div>
</body>
</html>
