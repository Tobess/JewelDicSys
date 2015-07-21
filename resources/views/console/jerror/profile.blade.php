@extends('console.main')

@section('content')
<!-- content -->
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
        <ul class="breadcrumb m-b-none">
            <li><a class="text-muted">系统管理</a></li>
            <li><a class="text-muted" href="/console/jerror">错误反馈</a></li>
            <li class="active"><a class="text-muted">{{ $row->file_id }}</a></li>
        </ul>
        <div class="padder">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav pull-right">
                        <li><a href="http://file.fromai.cn/{{ $row->file_group }}/{{ $row->file_name }}" target="_blank" tabindex="0">下载EXCEL</a></li>
                    </ul>
                    <h4 class="m-n font-thin h4">{{ $row->companyName }}({{ $row->domain }})</h4>
                    <small class="text-muted">{{ $row->mobile }}({{ $row->userName }})</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            <th style="width:20px;"></th>
                            <th>错误消息</th>
                            <th style="width:106px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($rows as $idx => $msg)
                            <tr>
                                <td>{{ $idx+1 }}</td>
                                <td>{{ $msg }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / content -->
@stop