@extends('auth.app') @section('content')
    <script type="text/javascript"
            src="{{URL::asset('/javascript/pagejs/register.js')}}"></script>
    <div class="container">
        <div class="wrapper clearfix">
            <div class="userCenter clearfix bg-white">
                <div class="forget-con  clearfix ">
                    <div id="regstep" class="form m-t-40 step-1">
                        <dl>
                            <dt>手机/邮箱</dt>
                            <dd>
                                <input type="text" id="account" placeholder="请输入手机/邮箱"
                                       validate="required;[email|tel]" val-name="手机/邮箱" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>密码</dt>
                            <dd>
                                <input type="password" id="password" placeholder="密码长度6-12"
                                       validate="required;len[6:12]" val-name="密码" class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>确认密码</dt>
                            <dd>
                                <input type="password" id="repeatpwd" placeholder=""
                                       val-name="重新确认密码" validate="repeat[password]" val-name="重新确认密码"
                                       class="input">
                            </dd>
                        </dl>
                        <dl>
                            <dt>验证码</dt>
                            <dd>
                                <input type="text" id="checkCode" validate="required"
                                       val-name="验证码" class="input short-input "><a id="sendCheckCode"
                                                                                    class="security-btn">验证码</a>
                            </dd>
                        </dl>
                        <a class="button m-t-100" id="next">下一步</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
