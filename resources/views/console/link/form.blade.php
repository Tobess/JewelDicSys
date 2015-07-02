@extends('layouts.blocks.modal')

@section('modalTitle', $tItem['name'].'［'.$pItem->name.'］链接信息')

@section('modalForm', ' action="/console/links" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>类型</label>
            <select name="rel_type_tar" class="form-control m-b" require placeholder="请选择关联数据类型">
                <option value="1">宝石分类</option>
                <option value="2">金属分类</option>
                <option value="3">样式分类</option>
                <option value="4">珠宝品牌</option>
                <option value="5">加工工艺</option>
                <option value="6">宝石颜色</option>
                <option value="7">宝石等级</option>
                <option value="8">珠宝款式</option>
                <option value="9">珠宝寓意</option>
            </select>
            <input name="rel_id_src" type="hidden" value="{{ $pItem->id }}">
            <input name="rel_type_src" type="hidden" value="{{ $tItem['id'] }}">
        </div>
        <div class="form-group">
            <label>类型</label>
            <input name="rel_id_tar_name" type="text" value="" class="form-control m-b" require placeholder="请输入关联数据名称">
        </div>
    </div>
</div>
@stop