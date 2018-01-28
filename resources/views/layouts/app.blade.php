<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '领师管理平台') }}</title>
    <!-- Styles -->
    <!-- Bootstrap -->
    <link href="{{url('css/plugins/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('css/plugins/font-awesome/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress 前端页面加载进度条 -->
    <link href="{{url('css/plugins/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{url('css/custom.min.css')}}" rel="stylesheet">
    <!-- Chosen 下拉--搜索、多选-->
    <link href="{{url('css/plugins/chosen/chosen.css')}}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{url('js/plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery -->
    <script src="{{url('js/plugins/device/device.min.js')}}"></script>
    <!-- Scripts -->
    <script src="{{url('js/plugins/jquery.cookie/jquery.cookie.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{url('js/plugins/bootstrap/bootstrap.min.js')}}"></script>
    {{--html控件resize事件--}}
    <script src="{{url('js/plugins/jquery-resize/jquery.ba-resize.min.js')}}"></script>
    <!-- FastClick 在移动浏览器上发生介于轻敲及点击之间的指令时，能够让你摆脱300毫秒的延迟.让你的应用程序更加灵敏迅捷-->
    <script src="{{url('js/plugins/fastclick/fastclick.js')}}"></script>
    <!-- NProgress 前端页面记载进度条-->
    <script src="{{url('js/plugins/nprogress/nprogress.js')}}"></script>
    <!-- Chosen 下拉-->
    <script src="{{url('js/plugins/chosen/chosen.jquery.js')}}"></script>
    <!-- layer 层-->
    <script src="{{url('js/plugins/layer/layer.js')}}"></script>
    {{--表单校验--}}
    <script src="{{url('js/plugins/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{url('js/plugins/parsleyjs/i18n/zh_cn.js')}}"></script>
    <script src="{{url('js/common.js')}}"></script>
</head>
<body class="footer_fixed {{$share["bodyNav"]}} body_overflow">
<div class="container body">
    <div class="main_container main_container_overflow">
        <div id="left_menu" class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-laptop"></i>
                        <span>{{ config('app.name', '领师管理平台') }}</span></a>
                </div>
                <div class="clearfix"></div>
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            {{--<li><a href="{{url('')}}"><i class="fa fa-home fa-lg"></i> Dashboard</a></li>--}}
                            @foreach($share["menus"] as $menu)
                                <li>
                                    @if(empty($menu["childrens"]))
                                        <a href="{{url('').$menu['href']}}"><i
                                                    class="fa fa-{{$menu['icon']}} fa-lg"></i> {{$menu["name"]}}</a>
                                    @else
                                        <a><i class="fa fa-{{$menu['icon']}}"></i> {{$menu["name"]}} <span
                                                    class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            @foreach($menu["childrens"] as $mc)
                                                <li><a href="{{$mc["href"]}}">{{$mc["name"]}}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <img src="{{$share["user"]["avatar"]}}" alt="">
                                @if($share["user"]) {{$share["user"]["userName"]}} @endif
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                {{--<li><a href="http://account.weibo.com/set/index" target="_blank"> 个人信息</a></li>--}}
                                <li><a href="{{url('/auth/logout')}}"> 退出</a></li>
                            </ul>
                        </li>
                        {{--<li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="" alt="图片"/></span>
                                        <span> <span>John Smith</span><span class="time">3 mins ago</span></span>
                                        <span class="message">Film festivals used to be do-or-die moments for movie makers. They were where</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong><a href="{{url('/demo/ui/news')}}">查看所有</a></strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>--}}
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->
        <!-- page content -->

        <div class="right_col right_col_fixed" role="main">
            @if(!empty($name)){{$name}}<br>@endif
            @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Copyright © 2015 微博广告交易平台 All rights reserved.
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- Custom Theme Scripts -->
<script src="{{url('js/custom.js')}}"></script>

</body>
</html>
