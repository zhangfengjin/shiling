@extends('auth.app') @section('content')
    <div class="container">
        <div class="wrapper clearfix">
            <div class="login">
                <div class="loginbox">
                    <div class="login-form" id="lgform">
                        <p>
                            <input id="account" type="text" class="input username"
                                   placeholder="手机号">
                        </p>
                        <p>
                            <input id="password" type="password" class="input pwd"
                                   placeholder="密码" value="">
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
                var data={
                    "tel":"15510249632",
                    "password":"111111"
                };
                CommonUtil.requestService('/auth/login', data, true, "post",
                    function (data) {
                        CommonUtil.redirect();
                    }, function (ex) {
                    });
            }

            /* 登录提交 */
            $("#sublogin").bind("click", function () {
                postLogin();
            });
        });
    </script>
@endsection
