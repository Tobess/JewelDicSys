@extends('layouts.blocks.modal')

@section('modalTitle', '寓意信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入寓意名称">
        </div>
    </div>
</div>
@stop