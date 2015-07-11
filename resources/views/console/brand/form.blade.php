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