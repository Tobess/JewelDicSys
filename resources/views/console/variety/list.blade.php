@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">样式分类</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop

@section('toolRight')
    <div class="input-group">
        <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入材质编号、名称" {{ isset($query) && $query ? 'value="'.$query.'"' : ''}}>
        <span class="input-group-btn">
          <button class="btn btn-sm btn-default" type="button" onclick="window.location.href='?query='+$('#searchQueryBox').val();">搜!</button>
        </span>
    </div>
@stop

@section('tableTitle')
    <th>编号</th>
    <th>名称</th>
    <th>拼音</th>
    <th>描述</th>
    <th style="width:30px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td>{{ $row->code }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->pinyin }}</td>
        <td>{{ $row->description }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button">编辑</button>
            <button class="btn btn-xs btn-danger m-b-none" type="button">删除</button>
        </td>
    </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>'&query='.$query])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows->appends(['query' => $query])])
@stop