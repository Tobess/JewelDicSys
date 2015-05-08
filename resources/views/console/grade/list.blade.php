@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">宝石等级</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop

@section('tableTitle')
    <th style="width:20px;">
        <label class="i-checks m-b-none">
            <input type="checkbox"><i></i>
        </label>
    </th>
    <th>名称</th>
    <th>拼音</th>
    <th>简拼</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->pinyin }}</td>
        <td>{{ $row->letter }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button">编辑</button>
            <button class="btn btn-xs btn-danger m-b-none" type="button">删除</button>
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