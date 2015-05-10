@extends('layouts.blocks.modal')

@section('modalTitle', '规则信息')

@section('modalForm', ' action="/console/colors" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>编号</label>
            <input name="code" type="text" class="form-control" placeholder="请输入规则编号">
        </div>
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入规则名称">
        </div>
    </div>
</div>
@stop