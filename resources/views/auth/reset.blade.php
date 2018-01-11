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
						<input type="text" id="account" placeholder="请输入手机/邮箱"
							validate="required;[email|tel]" val-name="手机/邮箱" class="input">
					</dd>
				</dl>
				<dl>
					<dt>验证码</dt>
					<dd>
						<input type="text" id="checkCode" validate="required"
							val-name="验证码" placeholder="请输入您的验证码" class="input short-input "><a
							class="security-btn" id="sendCheckCode">验证码</a>
					</dd>
				</dl>
				<dl>
					<dt>请输入新密码</dt>
					<dd>
						<input type="password" id="password" placeholder="密码长度6-12"
							validate="required;len[6:12]" val-name="密码" class="input">
					</dd>
				</dl>
				<dl>
					<dt>再次确认密码</dt>
					<dd>
						<input type="password" id="repeatpwd" placeholder=""
							val-name="重新确认密码" validate="repeat[password]" val-name="重新确认密码"
							class="input">
					</dd>
				</dl>
				<a href="#" class="button m-t-100" id="resetpwd">重置密码</a>
			</div>
		</div>
	</div>
</div>
@endsection
