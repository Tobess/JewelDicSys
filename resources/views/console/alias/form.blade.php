@extends('layouts.blocks.modal')

@section('modalTitle', $tItem['name'].'［'.$pItem->name.'］别名信息')

@section('modalForm', ' action="/console/aliases" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入品牌名称">
            <input name="rel_id" type="hidden" value="0">
            <input name="rel_type" type="hidden" value="0">
        </div>
    </div>
</div>
@stop