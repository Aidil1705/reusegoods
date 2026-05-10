<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ asset('images/logo/logo.jpeg') }}" type="image/x-icon">
    <title>@yield('title', 'ReGoods')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    @include('partials.navbar-pembeli')

    <!-- Content -->
    <main class="p-6">
        @yield('content')
    </main>
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>