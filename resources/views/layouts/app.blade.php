<!DOCTYPE html>
<html lang="en">
<head>
    @include('auth::layouts.common.head')
</head>
<body>
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
</body>
</html>
