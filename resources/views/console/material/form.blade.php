@extends('layouts.blocks.modal')

@section('modalTitle', '材质信息')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group" id="materialParentBox">
                    <label>父级</label>
                    <div >
                        <select id="materialParent" class="form-control" name="parent"></select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>名称</label>
                    <input name="name" type="text" class="form-control" placeholder="请输入材质名称" require>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>编号</label>
                    <input name="code" type="text" class="form-control" placeholder="请输入材质编号" require>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>类型</label>
                    <select name="type" class="form-control m-b" require placeholder="请输入材质类型" id="materialType">
                        <option value="1">贵金属</option>
                        <option value="2">天然宝石</option>
                        <option value="3">天然玉石</option>
                        <option value="4">天然有机宝石</option>
                        <option value="5">合成宝石</option>
                        <option value="6">人造宝石</option>
                        <option value="7">拼合宝石</option>
                        <option value="8">再造宝石</option>
                        <option value="8">其他</option>
                    </select>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-12 wrapper-xs">
                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" type="text" class="form-control" placeholder="请输入材质描述"></textarea>
                </div>
            </div>
        </div>
        <div id="materialMineralBox">
            <div class="col-sm-12 wrapper-xs">
                <div class="form-group">
                    <label>矿物</label>
                    <input name="mineral" type="text" class="form-control" placeholder="请输入材质矿物">
                </div>
            </div>
        </div>
    </div>
    <div id="metalBox" class="row hide">
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>成色</label>
                    <input name="condition" type="text" class="form-control" placeholder="请输入贵金属成色" require>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>化学名称</label>
                    <input name="chemistry" type="text" class="form-control" placeholder="请输入贵金属化学名称" require>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>中文名</label>
                    <input name="chinese" type="text" class="form-control" placeholder="请输入贵金属中文名" require>
                </div>
            </div>
            <div class="col-sm-6 wrapper-xs">
                <div class="form-group">
                    <label>英文名</label>
                    <input name="english" type="text" class="form-control" placeholder="请输入贵金属英文名" require>
                </div>
            </div>
        </div>
        <div>
            <div class="col-sm-12 wrapper-xs">
                <div class="form-group">
                    <label>类型</label>
                    <select name="metal" class="form-control m-b" require placeholder="请输入贵金属类型">
                        <option value="1">黄金</option>
                        <option value="3">银</option>
                        <option value="5">铂金</option>
                        <option value="7">钯金</option>
                        <option value="9">铑</option>
                        <option value="99">其他</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@stop