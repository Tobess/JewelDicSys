@extends('layouts.content')

@section('breadcrumb')
    <li><a class="text-muted">系统管理</a></li>
    <li><a class="text-muted">错误反馈</a></li>
    <li class="active"><a class="text-muted">{{ $row->file_id }}</a></li>
@stop

@section('content-view')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入等级名称">
        </div>
        <div class="form-group">
            <label>拼音</label>
            <input name="pinyin" type="text" class="form-control" placeholder="请输入拼音">
        </div>
        <div class="form-group">
            <label>简拼</label>
            <input name="letter" type="text" class="form-control" placeholder="请输入简拼">
        </div>
    </div>
</div>
@stop