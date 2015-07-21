@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">系统管理</a></li>
    <li class="active"><a class="text-muted">错误反馈</a></li>
@stop

@section('toolRight')
<div class="input-group">
    <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入企业号、手机号查询" value="{{ isset($query) && $query ? $query : ''}}">
        <span class="input-group-btn">
          <button class="btn btn-sm btn-default" type="button" onclick="window.location.href='?query='+$('#searchQueryBox').val();">搜!</button>
        </span>
</div>
@stop

@section('tableTitle')
    <th style="width:20px;">
        <label class="i-checks m-b-none">
            <input type="checkbox"><i></i>
        </label>
    </th>
    <th>企业号</th>
    <th>手机号</th>
    <th>状态</th>
    <th>上报时间</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td>{{ $row->domain }}({{ $row->companyName }})</td>
        <td>{{ $row->mobile }}({{ $row->userName }})</td>
        <td><span class="badge {{ $row->checked ? 'bg-success' : 'bg-danger' }}">{{ $row->checked ? '已阅' : '未阅'}}</span></td>
        <td>{{ $row->updated_at }}</td>
        <td>
            <a class="btn btn-xs btn-info m-b-none" type="button" href="/console/jerror/profile/{{ $row->file_id }}">详情</a>
            <a class="btn btn-xs btn-danger m-b-none" type="button" href="/console/jerror/destroy/{{ $row->file_id }}">删除</a>
        </td>
    </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>''])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows])
@stop