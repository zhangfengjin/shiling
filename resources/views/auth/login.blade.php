@extends('auth.app') @section('content')
    <style type="text/css">
        .security-btn {
            height: 30px;
        }

        .input-no-icon {
            background-image: none !important;
        }
    </style>
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
                        <p>
                            <input type="text" id="code" class="input short-input input-no-icon"><a id="sendCheckCode"
                                                                                                    class="security-btn">验证码</a>
                        </p>
                        {{--<p class="t-right">
                            <a href="{{ url('/auth/reset') }}">忘记密码？</a>
                        </p>--}}
                        <a id="sublogin" class="button button-login"> 登 录 </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script type="application/javascript">
        $(function () {
            function isEmail(str) {
                var patrn = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                return patrn.test(str);
            }

            // 是否为电话号码
            function isTel(str) {
                var patrn = /^1\d{10}$/;
                return patrn.test(str);
            }

            function postLogin() {
                var data = {
                    "password": $("#password").val(),
                    "bs": "1",
                    "verify": $("#code").val()
                };
                var account = $("#account").val();
                if (isEmail(account) || isTel(account)) {
                    if (isEmail(account)) {
                        data.email = account;
                    } else {
                        data.phone = account;
                    }
                    CommonUtil.requestService('/auth/login', data, true, "post",
                        function (data) {
                            CommonUtil.redirect();
                        }, function (ex) {
                        });
                }
            }

            var Verify = function (util) {
                var codeTimer;
                return util = {
                    setCCSending: function () {
                        $("#sendCheckCode").attr("disabled", "disabled");// 不可用
                        $("#sendCheckCode").css("pointer-events", "none");
                        $("#sendCheckCode").html("发送中...");
                    },
                    setCCDisabled: function (timeMark) {
                        $("#sendCheckCode").attr("disabled", "disabled");// 不可用
                        $("#sendCheckCode").css("pointer-events", "none");
                        $("#sendCheckCode").html(timeMark + "秒获取");
                    },
                    removeCCDisabled: function () {
                        clearInterval(codeTimer);
                        $("#sendCheckCode").removeAttr("disabled");// 移除不可用属性
                        $("#sendCheckCode").css("pointer-events", "inherit");
                        $("#sendCheckCode").html("验证码");
                    },
                    codeTimer: function (timeMark) {
                        codeTimer = setInterval(function () {
                            if (timeMark < 0) {
                                util.removeCCDisabled();
                                return true;
                            }
                            util.setCCDisabled(timeMark);
                            timeMark--;
                        }, 1000);
                    },
                    sendCheckCode: function () {
                        var account = $("#account").val();
                        if (isEmail(account) || isTel(account)) {
                            data = {};
                            if (isEmail(account)) {
                                data.email = account;
                            } else {
                                data.phone = account;
                            }
                            util.setCCSending();
                            CommonUtil.requestService('/verify/code', data, true,
                                "get", function (data) {// 验证码已发送出去
                                    var timeMark = 120;
                                    util.codeTimer(timeMark);// 计时开始服务器发送验证码
                                }, function (ex) {// 网络异常
                                    util.removeCCDisabled();
                                });
                        }
                    }
                };
            }();

            $("#sendCheckCode").bind("click", function () {
                Verify.sendCheckCode();
            });

            /* 登录提交 */
            $("#sublogin").bind("click", function () {
                postLogin();
            });
        });
    </script>
@endsection
