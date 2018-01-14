@extends('auth.app') @section('content')
    <script type="text/javascript"
            src="{{URL::asset('/javascript/pagejs/register.js')}}"></script>
    <div class="container">
        <div class="wrapper clearfix">
            <div class="userCenter clearfix bg-white">
                <div class="forget-con  clearfix ">
                    <div id="regstep" class="form m-t-40 step-1">
                        <dl>
                            <dt>手机</dt>
                            <dd>
                                <input type="text" id="account" placeholder="请输入手机" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>密码</dt>
                            <dd>
                                <input type="password" id="password" placeholder="密码长度6-12" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>确认密码</dt>
                            <dd>
                                <input type="password" id="repeatpwd" placeholder="" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>验证码</dt>
                            <dd>
                                <input type="text" id="checkCode" class="input short-input "><a id="sendCheckCode"
                                                                                                class="security-btn">验证码</a>
                            </dd>
                        </dl>
                        <a class="button m-t-100" id="register">注册</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        $(function () {
            function postLogin() {
                var data = {
                    "tel": "15510249631212121",
                    "email": "15510249632",
                    "password": "111111"
                };
                CommonUtil.requestService('/auth/register', data, true, "post",
                    function (data) {
                        CommonUtil.redirect();
                    }, function (ex) {
                    });
            }

            /* 注册提交 */
            $("#register").bind("click", function () {
                postLogin();
            });
        });
    </script>
@endsection
