<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ReGoods')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    @include('partials.navbar-pembeli')

    <!-- Content -->
    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>