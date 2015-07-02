@extends('layouts.blocks.modal')

@section('modalTitle', '规则信息')

@section('modalForm', ' action="/console/colors" method="POST"')

@section('modalBody')
<div class="modal-body wrapper-lg">
    <div class="row">
        <div class="form-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" placeholder="请输入规则名称">
        </div>
        <div class="form-group">
            <label>配置</label>
            <input name="configure" type="text" class="form-control" placeholder="请输入规则配置">
        </div>
        <div class="form-group">
            <label>构成元素</label>
            <input name="elements" type="text" class="form-control" placeholder="请输入规则包含元素">
        </div>
        <div class="form-group">
            <span class="label bg-primary">1宝石</span>
            <span class="label bg-primary">2金属</span>
            <span class="label bg-primary">3样式</span>
            <span class="label bg-primary">4品牌</span>
            <span class="label bg-primary">5工艺</span>
            <span class="label bg-primary">6颜色</span>
            <span class="label bg-primary">7等级</span>
            <span class="label bg-primary">8款式</span>
            <span class="label bg-primary">9寓意</span>
        </div>
        <div class="alert alert-info">
            <span class="label bg-danger">注意：</span>
            <span>各种组成项目ID用+连接，如果构成项是固定字符直接填入字符用+连接，构成元素请按规则中元素出现的先后顺序用逗号隔开</span>
            <br>
            <br>
            <span class="label bg-primary">例如：</span><span> 1+2+(+6+) => 翡翠戒指(A货)</span>
        </div>
    </div>
</div>
@stop