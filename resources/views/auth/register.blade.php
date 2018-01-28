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
                var patrn = /^1[3|4|5|7|8]\d{9}$/;
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
