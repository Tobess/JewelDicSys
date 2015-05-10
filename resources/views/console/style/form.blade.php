@extends('layouts.blocks.modal')

@section('modalTitle', '款式信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>款号</label>
            <input name="code" type="text" class="form-control" placeholder="请输入款式编号">
        </div>
        <div class="form-group">
            <label>款名</label>
            <input name="name" type="text" class="form-control" placeholder="请输入款式名称">
        </div>
    </div>
</div>
@stop