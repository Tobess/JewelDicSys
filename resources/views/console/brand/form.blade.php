@extends('layouts.blocks.modal')

@section('modalTitle', '品牌信息')

@section('modalForm', ' action="/console/brands" method="post" enctype="multipart/form-data" ')

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
            <div class="form-group">
                <div>
                    <label>品牌LOGO：（图片尺寸300：132 ，图片大小小于100kb）</label>
                    <input type="file" name="picture" class="file" showRemove="false"/>
                </div>
            </div>
            <div class="form-group">
                <div id="old" style="display: none" class="file-preview-frame krajee-default  kv-preview-thumb">
                    <div class="kv-file-content">
                        <img src="{{ url('/console/brands/logo/default.png') }}"
                             class="file-preview-image kv-preview-data rotate-1" title="heihei.jpg" alt="heihei.jpg"
                             style="width:auto;height:100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop