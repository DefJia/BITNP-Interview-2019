<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a href="https://www.bitnp.net/">
        <img src="{{env('SITE')}}/images/logo.png" style="height:4vh;text-align: center;margin-right:1vw">
        </a>
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>日期列表
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ env('SITE') }}/list/1/0">10月17日
                        </a>
                        <a class="dropdown-item" href="{{ env('SITE') }}/list/2/0">10月18日
                        </a>
                        <a class="dropdown-item" href="{{ env('SITE') }}/list/3/0">10月19日
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>教室列表
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/10">{{ __('候场教室') }}</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/2/1">{{ __('2A-202') }}</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/2/2">{{ __('2A-203') }}</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>待处理列表
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/5">技术类</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/6">电脑诊所</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/7">数字媒体部</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/8">组织部</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/9">捡漏队列</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>录取名单
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/11">技术类</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/12">电脑诊所</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/13">数字媒体部</a>
                        <a class="nav-link" href="{{ env('SITE') }}/list/0/14">组织部</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>使用指南
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="nav-link" href="https://github.com/DefJia/BITNP-Interview-2019/blob/master/README.md">{{ __('面试系统使用指南') }}</a>
                        <a class="nav-link" href="https://github.com/DefJia/BITNP-Interview-2019/blob/master/README.md">{{ __('巴黎和会使用指南') }}</a>
                    </div>
                </li>
                
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">{{ __('登录') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('登出') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>