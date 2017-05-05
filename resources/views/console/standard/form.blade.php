@extends('layouts.blocks.modal')

@section('modalTitle', '标准' . $modName)

@section('modalForm', 'console')

@section('modalBody')
    <div class="modal-body wrapper-lg">
        <div class="row">
            <div class="form-group">
                <label>名称</label>
                <input name="name" type="text" class="form-control" placeholder="请输入{{ $modName }}名称">
            </div>
            <div class="form-group">
                <label>材质:</label>
                <input type="hidden" type="text" name="m_titles" value=""/>
                <input type="hidden" name="m_ids" class="form-control" value=""/>
                <div>
                    <div class="input-group dropdown" data-toggle="collapse" href="#collapseTwo">
                        <input type="text" name="material-show" id="caiLiao" class="form-control" disabled
                               placeholder="请选择材料" href="#collapseTwo">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" data-spy="scroll"
                         data-target="#material-form">
                        <div class="panel-body" id="material-form">

                        </div>
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