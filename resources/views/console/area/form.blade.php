@extends('layouts.blocks.modal')

@section('modalTitle', '颜色信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入地区名称">
            <input name="parent" type="hidden" value="0">
        </div>
        <div class="form-group">
            <label>简称</label>
            <input name="short_name" type="text" class="form-control" placeholder="请输入简称">
        </div>
        <div class="form-group">
            <label>经度</label>
            <input name="longitude" type="text" class="form-control" placeholder="请输入经度">
        </div>
        <div class="form-group">
            <label>纬度</label>
            <input name="latitude" type="text" class="form-control" placeholder="请输入纬度">
        </div>
    </div>
</div>
@stop