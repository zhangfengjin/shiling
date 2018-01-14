@extends('auth.app') @section('content')
    <div class="container">
        <div class="wrapper clearfix">
            <div class="login">
                <div class="loginbox">
                    <div class="login-form" id="lgform">
                        <p>
                            <input id="account" type="text" class="input username"
                                   placeholder="手机号" val-name="帐号"
                                   value="{{Cookie::get('userAccount')}}">
                        </p>
                        <p>
                            <input id="password" type="password" class="input pwd"
                                   placeholder="密码" val-name="密码" value="">
                        </p>
                        <p>
                            <input type="text" id="vercode" val-name="验证码"
                                   class="input verify-input"><img id="verify" class="verify"
                                                                   alt="验证码" src="{{url('/verify/image?acid='.time())}}"
                                                                   title="点击更换验证码">
                        </p>
                        <p class="t-right">
                            <a href="{{ url('/auth/reset') }}">忘记密码？</a>
                        </p>
                        <a id="sublogin" class="button button-login"> 登 录 </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script type="application/javascript">
        $(function () {
            function postLogin() {
                var data = util.validateLogin();
                if (data) {
                    CommonUtil.requestService('/auth/login', data, true, "post",
                        function (data) {
                            if (data.success) {
                                CommonUtil.redirect(data.url);
                            } else {// 登录失败
                                layer.msg(data.error);
                                util.setVerCode();
                            }
                        }, function (ex) {
                            layer.msg(ex.error);
                            util.setVerCode();
                        });
                }
            }

            /* 登录提交 */
            $("#sublogin").bind("click", function () {
                postLogin();
            });
        });
    </script>
@endsection
