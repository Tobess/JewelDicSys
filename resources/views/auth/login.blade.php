@extends('layouts.master')

@section('title', '登陆')

@section('container')
<div class="app app-header-fixed  ">


    <div class="container w-xxl w-auto-xs" ng-controller="SigninFormController" ng-init="app.settings.container = false;">
        <a href class="navbar-brand block m-t">黄金珠宝行业词库系统</a>
        <div class="m-b-lg">
            <form name="form" class="form-validation" role="form" method="POST" action="'/auth/login'">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="text-danger wrapper text-center" ng-show="authError">
                    @if (count($errors) > 0)
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="list-group list-group-sm">
                    <div class="list-group-item">
                        <input name="email" type="email" placeholder="Email" class="form-control no-border" required value="{{ old('email') }}">
                    </div>
                    <div class="list-group-item">
                        <input name="password" type="password" placeholder="Password" class="form-control no-border" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">登陆</button>
                <div class="text-center m-t m-b"><a href="/password/email">忘记密码?</a></div>
                <div class="line line-dashed"></div>
                <p class="text-center"><small>没有账号?</small></p>
                <a href="/auth/register" class="btn btn-lg btn-default btn-block">注册账号</a>
            </form>
        </div>
        <div class="text-center">
            <p>
                <small class="text-muted">金企联盟 <br>&copy; 2015</small>
            </p>
        </div>
    </div>


</div>
@endsection

