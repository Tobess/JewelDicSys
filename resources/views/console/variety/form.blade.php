@extends('layouts.blocks.modal')

@section('modalTitle', '样式信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入样式名称" require>
        </div>
        <div class="form-group">
            <label>编号</label>
            <input name="code" type="text" class="form-control" placeholder="请输入样式编号" require>
        </div>
        <div class="form-group">
            <label>描述</label>
            <textarea name="description" type="text" class="form-control" placeholder="请输入样式描述"></textarea>
        </div>
    </div>
</div>
@stop