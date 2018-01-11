@extends('auth.app') @section('content')
<script type="text/javascript"
	src="{{URL::asset('/javascript/pagejs/register.js')}}"></script>

<div class="container">
	<div class="wrapper clearfix">
		<div class="userCenter clearfix bg-white">
			<div class="forget-con  clearfix ">
				@if($step==1)
				<div class="step three  clearfix ">
					<ul>
						<li class="active"><i class="icon forget-1"></i> <span>设置登录信息</span></li>
						<li><i class="icon register-2"></i> <span>完善个人资料</span></li>
						<li><i class="icon forget-3"></i> <span>注册成功</span></li>
					</ul>
					<div class="step-line"></div>
				</div>
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
				@endif @if($step==2)
				<div class="step three  clearfix ">
					<ul>
						<li class="active"><i class="icon forget-1"></i> <span>设置登录信息</span></li>
						<li class="active"><i class="icon register-2"></i> <span>完善个人资料</span></li>
						<li><i class="icon forget-3"></i> <span>注册成功</span></li>
					</ul>
					<div class="step-line"></div>
				</div>
				<div id="regstep" class="form m-t-40 step-2">
					<dl>
						<dt>
							<span class="color-red">*</span>登录账号
						</dt>
						<dd>
							<input type="text" id="username" validate="required;len[3:20]"
								val-name="帐号" placeholder="请输入登录账号" class="input ">
						</dd>
					</dl>
					<dl>
						<dt>
							<span class="color-red">*</span>公司名称
						</dt>
						<dd>
							<input type="text" id="company" validate="required"
								val-name="公司名称" placeholder="请输入公司名称" class="input ">
						</dd>
					</dl>
					<dl>
						<dt>
							<span class="color-red">*</span>用户角色
						</dt>
						<dd>
							<select id="usertype" class="input "> @foreach($usertypes as
								$usertype)
								<option value="{{$usertype->id}}">{{$usertype->utypename}}</option>
								@endforeach
							</select>
						</dd>
					</dl>
					<dl>
						<dt>
							<span class="color-red">*</span>邮箱
						</dt>
						<dd>
							<input type="text" id="email" validate="email" val-name="邮箱"
								value="{{$email}}" class="input ">
						</dd>
					</dl>
					<!-- <dl>
						<dt><span class="color-red">*</span>联系人</dt>
						<dd><input type="text" class="input " placeholder="请输入联系人姓名"></dd>
					</dl> -->
					<dl>
						<dt>
							<span class="color-red">*</span>手机
						</dt>
						<dd>
							<input type="text" id="tel" validate="tel" val-name="手机号"
								value="{{$tel}}" class="input ">
						</dd>
					</dl>
					<dl>
						<dt>微信号</dt>
						<dd>
							<input type="text" id="webchat" class="input " placeholder="">
						</dd>
					</dl>
					<a class="button m-t-100" id="ok">下一步</a>
				</div>
				@endif @if($step==3)
				<div class="step three  clearfix ">
					<ul>
						<li class="active"><i class="icon forget-1"></i> <span>设置登录信息</span></li>
						<li class="active"><i class="icon register-2"></i> <span>完善个人资料</span></li>
						<li class="active"><i class="icon forget-3"></i> <span>注册成功</span></li>
					</ul>
					<div class="step-line"></div>
				</div>
				<div id="regstep" class="form m-t-40 step-3">
					<p class="succeed">
						<i class="icon icon-succeed text-top"></i>恭喜您注册成功！ <span
							class="succeed-tip">为保持演示中心数据清洁度，本系统中为您分配的账套有效期为<em>6</em>个月，6个月后账套数据将自动还原到原始状态，重新给您分派一个新的账套
						</span> <br> <span class="succeed-tip"><em>温馨提示：</em><br> <span
							style="color: #f5a902;">您可以通过注册的体验中心账号登录U8、零售、分销、移动系统，登录名为您注册的手机号，密码与体验中心密码一致；<br/>
							O2O演示：登录U8时选择991帐套；登录零售服务器1（与u8适配）时，帐号为：991，密码：111111<br/>
							O2O微信公众号二维码：<img alt="体验中心二维码" src="{{url('/images/o2o.jpg')}}" style="width: 100px;height: 100px;">
						</span> </span>
					</p>
					<a class="button m-t-100" id="taste">马上体验</a>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
