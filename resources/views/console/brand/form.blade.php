@extends('layouts.blocks.modal')

@section('modalTitle', '品牌信息')

@section('modalForm', ' action="/console/brands" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入品牌名称">
        </div>
    </div>
</div>
@stop