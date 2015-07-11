@extends('layouts.blocks.modal')

@section('modalTitle', $tItem['name'].'［'.$pItem->name.'］别名信息')

@section('modalForm', ' action="/console/aliases" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入别名">
            <input name="rel_id" type="hidden" value="0">
            <input name="rel_type" type="hidden" value="0">
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