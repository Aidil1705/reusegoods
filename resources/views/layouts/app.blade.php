<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between">
        <h1 class="font-bold">MyShop</h1>
        <div>
            <a href="/" class="mr-4">Home</a>
            <a href="#">Produk</a>
        </div>
    </nav>

    <!-- Content -->
    @yield('content')

</body>
</html>