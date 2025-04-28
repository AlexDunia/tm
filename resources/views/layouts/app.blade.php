<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="\css\admin.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
    <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"></script>

    <!-- Additional Libraries -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #13121a;
            color: white;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }

        .site-main {
            min-height: calc(100vh - 200px);
            padding-bottom: 50px;
        }

        .site-footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #a0aec0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Global Header -->
        @include('_header')

        <main class="site-main">
            @yield('content')
        </main>

        <!-- Global Footer -->
        <footer class="site-footer">
            @include('_footer')
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
