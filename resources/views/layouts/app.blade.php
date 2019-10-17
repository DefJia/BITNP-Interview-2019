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

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ ENV('APP_URL') }}/js/app.js" defer></script>

    <!-- Styles -->
    <link href="{{ ENV('APP_URL') }}/css/font.css" rel="stylesheet">
    <link href="{{ ENV('APP_URL') }}/css/app.css" rel="stylesheet">
    <link href="{{ ENV('APP_URL') }}/css/new.css" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <div id="app">
        <h3 style="margin: 5vh 0">
            @yield('title')
        </h3>

        <div class="container">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <?php
                        $status_code = array('没来', '候场', '准备出发', '面试中', '结束');
                        $status_color = array('secondary', 'primary', 'danger', 'warning', 'success');
                        $text = array('候场人员：签到&选面试教室', '面试官：准备面试', '候场人员：安排出发', '面试官：写评论', '');
                        $html = '';
                        for($i = 0; $i < 5; $i++){
                            $button_text = sprintf('<button type="button" class="btn btn-%s">%s</button>--%s-->', $status_color[$i], $status_code[$i], $text[$i]);
                            $html .= $button_text;
                        }
                        $html = substr($html, 0, -5);
                        echo $html
                    ?>
                </div>
            </div>
        </div>

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
        <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? "https://" : "http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1278117619'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "v1.cnzz.com/z_stat.php%3Fid%3D1278117619%26online%3D2' type='text/javascript'%3E%3C/script%3E"));</script>    
    </footer>

</body>
</html>
