@extends('console.main')

@section('content')
<!-- content -->
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
        <ul class="breadcrumb m-b-none">
            <li><a class="text-muted">系统管理</a></li>
            <li class="active"><a class="text-muted">员工列表</a></li>
        </ul>
        <div class="padder">
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            <th style="width:20px;">
                                <label class="i-checks m-b-none">
                                    <input type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>电子邮件</th>
                            <th>用户姓名</th>
                            <th>最后登陆</th>
                            <th style="width:106px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($admins as $admin)
                        <tr>
                            <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->updated_at }}</td>
                            <td>
                                <button class="btn btn-xs btn-info m-b-none" type="button">编辑</button>
                                <button class="btn btn-xs btn-danger m-b-none" type="button">删除</button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 hidden-xs">
                            @include('layouts.blocks.jumper', ['paginator' => $admins])
                        </div>
                        <div class="col-sm-4 text-center">
                            @include('layouts.blocks.counter', ['paginator' => $admins])
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            @include('layouts.blocks.pager', ['paginator' => $admins])
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
<!-- / content -->
@stop