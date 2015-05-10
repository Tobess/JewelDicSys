@extends('layouts.blocks.modal')

@section('modalTitle', '等级信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入等级名称">
        </div>
    </div>
</div>
@stop