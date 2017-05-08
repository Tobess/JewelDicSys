@extends('layouts.blocks.modal')

@section('modalTitle', '标准' . $modName)

@section('modalForm', 'console')

@section('modalBody')
    <div class="modal-body wrapper-lg">
        <div class="row">
            <div class="form-group">
                <label>名称</label>
                <input name="name" id="name" type="text" class="form-control" placeholder="请输入{{ $modName }}名称">
                <input type="hidden" id="origin" name="ids" value="">
            </div>
            <div class="form-group">
                <label>拼音</label>
                <input name="pinyin" id="pinyin" type="text" class="form-control" placeholder="请输入拼音">
            </div>
            <div class="form-group">
                <label>简拼</label>
                <input name="letter" type="text" id="letter" class="form-control" placeholder="请输入简拼">
            </div>
            <div class="form-group">
                <label>材质:</label>
                <div>
                    <div class="input-group input-append ">
                        <input type="hidden" name="material" id="material" value=""/>
                        <input type="text" name="material-show" id="caiLiao" disabled class="form-control"
                               placeholder="请选择材质">
                        <span class="input-group-addon" id="btn-form"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modalFooter')
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <button type="button" class="btn btn-primary" id="submitBtn">提交</button>
@stop