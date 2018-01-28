@extends('auth.app') @section('content')
    <style type="text/css">
        .chosen-container-multi .chosen-choices {
            /*height: 37px !important;*/
        }

        .forget-con {
            padding-top: 0px !important;
        }
    </style>
    <div class="container">
        <div class="wrapper clearfix">
            <div class="userCenter clearfix bg-white">
                <div class="forget-con  clearfix ">
                    <div id="regstep" class="form m-t-40 step-1">
                        <dl>
                            <dt>姓名</dt>
                            <dd>
                                <input type="text" id="username" placeholder="请输入姓名" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>账号</dt>
                            <dd>
                                <input type="text" id="account" placeholder="请输入手机或邮箱" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>密码</dt>
                            <dd>
                                <input type="password" id="password" placeholder="密码长度6-12" class="input">
                            </dd>
                        </dl>
                        {{--<dl>
                            <dt>确认密码</dt>
                            <dd>
                                <input type="password" id="repeatpwd" placeholder="" class="input">
                            </dd>
                        </dl>--}}
                        <dl>
                            <dt>验证码</dt>
                            <dd>
                                <input type="text" id="code" class="input short-input "><a id="sendCheckCode"
                                                                                           class="security-btn">验证码</a>
                            </dd>
                        </dl>
                        <dl>
                            <dt>科目</dt>
                            <div style="text-indent:8px;">
                                <select id="course" data-placeholder="科目"
                                        class="form-control chosen-select" multiple>
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}">{{$course->value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </dl>
                        <a class="button m-t-100" id="register">注册</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        $(function () {
            function init() {
                var config = {
                    ".chosen-select": {},
                    ".chosen-select-deselect": {
                        allow_single_deselect: true
                    },
                    ".chosen-select-no-single": {
                        disable_search_threshold: 10
                    },
                    ".chosen-select-no-results": {
                        no_results_text: "Oops, nothing found!"
                    },
                    ".chosen-select-width": {
                        width: "95%"
                    }
                };
                for (var selector in config) {
                    $(selector).chosen(config[selector])
                }
                $(".chosen-container").css({
                    "width": $("#username").width(),
                    "height": 30
                });
            }

            function isEmail(str) {
                var patrn = /^[A-Za-z0-9]+([-_.][A-Za-z0-9]+)*@([A-Za-z0-9]+[-.])+[A-Za-z0-9]{2,5}$/;
                return patrn.test(str);
            }

            // 是否为电话号码
            function isTel(str) {
                var patrn = /^1\d{10}$/;
                return patrn.test(str);
            }

            init();
            function postLogin() {
                var data = {
                    "username": $("#username").val(),
                    "password": $("#password").val(),
                    "verify": $("#code").val()
                };
                var account = $("#account").val();
                if (isEmail(account) || isTel(account)) {
                    if (isEmail(account)) {
                        data.email = account;
                    } else {
                        data.phone = account;
                    }
                    var courses = [];
                    $.each($("#course").val(), function () {
                        var course = {};
                        course.subjectId = this;
                        courses.push(course);
                    });
                    data.subject = courses;
                    CommonUtil.requestService('/auth/register', data, true, "get",
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
                        $("#sendCheckCode").css("pointer-events","none");
                        $("#sendCheckCode").html("发送中...");
                    },
                    setCCDisabled: function (timeMark) {
                        $("#sendCheckCode").attr("disabled", "disabled");// 不可用
                        $("#sendCheckCode").css("pointer-events","none");
                        $("#sendCheckCode").html(timeMark + "秒获取");
                    },
                    removeCCDisabled: function () {
                        clearInterval(codeTimer);
                        $("#sendCheckCode").removeAttr("disabled");// 移除不可用属性
                        $("#sendCheckCode").css("pointer-events","inherit");
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

            /* 注册提交 */
            $("#register").bind("click", function () {
                postLogin();
            });
        });
    </script>
@endsection
