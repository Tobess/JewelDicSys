@extends('layouts.master')

@section('title', '用户注册')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">姓名</label>
							<div class="col-md-6">
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">电子邮箱</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">密码</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">密码确认</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">验证码</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="token">
                            </div>
                            <div class="col-md">
                                <a id="tokenBtn" type="button" class="btn" onClick="send_verify_code()">
                                    获取
                                </a>
                            </div>
                        </div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
    <script>
        function send_verify_code() {
            var name = $("#name").val(),
                email = $("#email").val();
            if (name && email) {
                var stopLoading = false;
                loading();
                $.ajax({
                    url: "/auth/verify-code",
                    data:{'email':email, 'name': name},
                    type: "GET",
                    dataType:'json',
                    success:function(data){
                        if (data) {
                            data.message ? alert(data.message) : alert('验证码发送成功，请联系系统管理，所要验证码。');
                        } else {
                            stopLoading = true;
                            alert('未知错误。');
                        }
                    },
                    error:function(error) {
                        stopLoading = true;
                        alert('验证码发送失败。');
                    }
                });
            } else {
                alert('请输入姓名和电子邮箱地址。');
                return;
            }

            var time = 60,
                $btn = $("#tokenBtn");
            function loading() {
                var timer = setInterval(function(){
                    $btn.text(time + '秒后重新获取');
                    $btn[0].disabled = true;
                    time--;
                    if (time == 0 || stopLoading) {
                        $btn[0].disabled = false;
                        $btn.text('获取');
                        clearInterval(timer);
                    }
                }, 1000);
            }
        }
    </script>
@stop
