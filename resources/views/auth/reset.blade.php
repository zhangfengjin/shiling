@extends('auth.app') @section('content')
    <script type="text/javascript"
            src="{{URL::asset('/javascript/pagejs/reset.js')}}"></script>

    <!-- <div id="errormsg"></div> -->
    <div class="wrapper clearfix">
        <div class="userCenter clearfix bg-white">
            <div class="forget-con  clearfix ">
                <div id="reset" class="form m-t-40 step-1">
                    <dl>
                        <dt>手机/邮箱</dt>
                        <dd>
                            <input type="text" id="account" placeholder="请输入手机/邮箱" val-name="手机/邮箱" class="input">
                        </dd>
                    </dl>
                    <dl>
                        <dt>验证码</dt>
                        <dd>
                            <input type="text" id="code" placeholder="请输入您的验证码" class="input short-input "><a
                                    class="security-btn" id="sendCheckCode">验证码</a>
                        </dd>
                    </dl>
                    <dl>
                        <dt>请输入新密码</dt>
                        <dd>
                            <input type="password" id="password" placeholder="密码长度6-12" class="input">
                        </dd>
                    </dl>
                    <dl>
                        <dt>再次确认密码</dt>
                        <dd>
                            <input type="password" id="repeatpwd" placeholder="再次确认密码"
                                   class="input">
                        </dd>
                    </dl>
                    <a href="#" class="button m-t-100" id="resetpwd">重置密码</a>
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

            function postReset() {
                var data = {
                    "password": $("#password").val(),
                    "confirm": $("#confirm").val(),
                    "bs": "1",
                    "verify": $("#code").val()
                };
                if (isEmail(account) || isTel(account)) {
                    if (isEmail(account)) {
                        data.email = account;
                    } else {
                        data.phone = account;
                    }
                }
                CommonUtil.requestService('/auth/reset', data, true, "post",
                    function (data) {
                        CommonUtil.redirect();
                    }, function (ex) {
                    });
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
            $("#resetpwd").bind("click", function () {
                //postReset();
            });
        });
    </script>
@endsection
