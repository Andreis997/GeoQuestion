<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BrokerApp') }}</title>
    @yield('specificHead')

</head>
<body class="sidebar-pinned">
<!--sidebar Ends-->
<main class="" style="">
    @yield('content')
</main>
@yield('specificFooter')
</body>
</html>
