@extends('layouts.blocks.modal')

@section('modalTitle', '标准形状信息')

@section('modalForm', 'console')

@section('modalBody')
    <div class="modal-body wrapper-lg">
        <div class="row">
            <div class="form-group">
                <label>名称</label>
                <input name="name" type="text" class="form-control" placeholder="请输入形状名称">
            </div>
            <div class="form-group">
                <label>拼音</label>
                <input name="pinyin" type="text" class="form-control" placeholder="请输入拼音">
            </div>
            <div class="form-group">
                <label>材质:</label>
                <input type="hidden" type="text" name="material" value=""/>
                <div>
                    <div class="input-group dropdown">
                        <input type="text" name="material-show" id="caiLiao" disabled class="form-control">
                        <span class="input-group-addon" id="btn-form"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="material_id" class="form-control" value=""/>
            <div class="form-group">
                <label>简拼</label>
                <input name="letter" type="text" class="form-control" placeholder="请输入简拼">
            </div>
        </div>
    </div>
@stop