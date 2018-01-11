<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width"/>
    <title>{{ config('app.name', '领师管理平台') }}</title>
    <!-- ajax psot\put\delete等非get请求时必须 -->
    <meta name="_token" content="{{ csrf_token() }}" charset="utf-8"/>
    <link href="{{URL::asset('css/plugins/bootstrap/bootstrap.min.css')}}"
          rel="stylesheet"/>
    <link href="{{URL::asset('css/auth_custom.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('css/auth_login.css')}}" rel="stylesheet"/>

    <script src="{{url('js/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{url('js/plugins/jquery.cookie/jquery.cookie.js')}}"></script>
    {{--表单校验--}}
    <script src="{{url('js/plugins/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{url('js/plugins/parsleyjs/i18n/zh_cn.js')}}"></script>
    <!-- layer 层-->
    <script src="{{url('js/plugins/layer/layer.js')}}"></script>
    <script src="{{url('js/common.js')}}"></script>
</head>
<body>
<div class="header">
    <div class="wrapper clearfix">
        <a href="{{url('/')}}" class="logo"> {{--<img
                    src="{{url('/images/homeimgs/logo.png')}}" alt="">--}} <span
                    id="homeNavigate">会员登录</span>
        </a> @if(empty($user))<span class="header-span"> 您还没有登录账号？<a
                    href="{{ url('/register') }}" class="register">点击注册</a>
			</span> @endif
    </div>
</div>
@yield('content')
<div class="footer">
    <p>版权所有</p>
</div>
</body>
</html>