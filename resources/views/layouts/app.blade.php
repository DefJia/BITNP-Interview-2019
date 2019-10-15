@if(Session::has('message'))
    <script type="text/javascript">
        alert('{{Session::get('message')}}');
    </script>
@endif

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <!--
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    -->

    <!-- Styles -->
    <link href="{{ asset('css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/new.css') }}" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <div id="app">
        <h3 style="margin: 5vh 0">
            @yield('title')
        </h3>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <footer >
        <hr style="width: 61.8%; color: #ccc"/>
        Copyright @ 2019 <a href="https://www.bitnp.net/" style="color: black"><u>BITNP</u></a>. All Rights Reserved.
        <br/>
        <a href="http://www.bit.edu.cn/" style="color: black"><u>北京理工大学</u></a>
        <a href="https://www.bitnp.net/" style="color: black"><u>网络开拓者协会</u></a>
        <br/>
        <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? "https://" : "http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1277809266'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s23.cnzz.com/z_stat.php%3Fid%3D1277809266%26online%3D2' type='text/javascript'%3E%3C/script%3E"));</script>
    </footer>

</body>
</html>
