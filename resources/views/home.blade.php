@extends('layouts.master')

@section('title', '首页')

@section('container')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					<a href="/auth/login">登陆</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
