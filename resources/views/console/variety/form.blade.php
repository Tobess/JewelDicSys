@extends('layouts.blocks.modal')

@section('modalTitle', '样式信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group" id="varietyParentBox">
                    <label>父级</label>
                    <div >
                        <select id="varietyParent" class="form-control" name="parent"></select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>名称</label>
                    <input name="name" type="text" class="form-control" placeholder="请输入样式名称" require>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>编号</label>
                    <input name="code" type="text" class="form-control" placeholder="请输入样式编号" require>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>类型</label>
                    <select name="type" class="form-control m-b" require placeholder="请输入样式类型" id="varietyType">
                        <option value="1">物料</option>
                        <option value="2">半成品</option>
                        <option value="3">成品</option>
                    </select>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group" id="materialParentBox">
                    <label>拼音</label>
                    <div >
                        <input name="pinyin" type="text" class="form-control" placeholder="请输入拼音">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>简拼</label>
                    <input name="letter" type="text" class="form-control" placeholder="请输入简拼">
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-12 wrapper-xs">
                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" type="text" class="form-control" placeholder="请输入样式描述"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@stop