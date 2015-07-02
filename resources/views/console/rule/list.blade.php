@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">系统管理</a></li>
    <li class="active"><a class="text-muted">名称规则</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button" onClick="save(0)">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop

@section('tableTitle')
    <th style="width:20px;">
        <label class="i-checks m-b-none">
            <input type="checkbox"><i></i>
        </label>
    </th>
    <th>名称</th>
    <th>配置</th>
    <th>构成元素</th>
    <th>拼音</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td id="ruleName{{ $row->id }}">{{ $row->name }}</td>
        <td id="ruleCfg{{ $row->id }}">{{ $row->configure }}</td>
        <td id="ruleEles{{ $row->id }}">{{ $row->elements }}</td>
        <td>{{ $row->pinyin }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save({{ $row->id }})">编辑</button>
            <a class="btn btn-xs btn-danger m-b-none" type="button" href="/console/rules/destroy/{{ $row->id }}">删除</a>
        </td>
    </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>''])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows])
@stop

<!--编辑页面-->
@section('extend')
@include('console.rule.form')
@stop

@section('scripts')
<script>
    function save(id) {
        var mWin = $("#modalWin");
        mWin.find('form').get(0).reset();
        mWin.find('form').attr('action', "/console/rules/" + (id > 0 ? ('update/' + id) : 'store'));
        if (id > 0) {
            mWin.find('[name="name"]').val($("#ruleName"+id).text());
            mWin.find('[name="configure"]').val($("#ruleCfg"+id).text());
            mWin.find('[name="elements"]').val($("#ruleEles"+id).text());
        }
        mWin.find('[name="name"]').focus();

        mWin.modal();
    }
</script>
@stop