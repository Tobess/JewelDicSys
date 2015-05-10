@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">样式分类</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button" onClick="save(0)">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop

@section('toolRight')
    <div class="input-group">
        <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入材质编号、名称" {{ isset($query) && $query ? 'value="'.$query.'"' : ''}}>
        <span class="input-group-btn">
          <button class="btn btn-sm btn-default" type="button" onclick="window.location.href='?query='+$('#searchQueryBox').val();">搜!</button>
        </span>
    </div>
@stop

@section('tableTitle')
    <th style="width:20px;">
        <label class="i-checks m-b-none">
            <input type="checkbox"><i></i>
        </label>
    </th>
    <th>编号</th>
    <th>名称</th>
    <th>拼音</th>
    <th>简拼</th>
    <th>描述</th>
    <th style="width:30px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td id="varietyCode{{ $row->id }}">{{ $row->code }}</td>
        <td id="varietyName{{ $row->id }}">{{ $row->name }}</td>
        <td>{{ $row->pinyin }}</td>
        <td>{{ $row->letter }}</td>
        <td id="varietyDesc{{ $row->id }}">{{ $row->description }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save({{ $row->id }})">编辑</button>
            <a class="btn btn-xs btn-danger m-b-none" type="button" href="/console/varieties/destroy/{{ $row->id }}">删除</a>
        </td>
    </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>'&query='.$query])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows->appends(['query' => $query])])
@stop

<!--编辑页面-->
@section('extend')
@include('console.variety.form')
@stop

@section('scripts')
<script>
    function save(id) {
        var mWin = $("#modalWin");
        mWin.find('form').get(0).reset();
        mWin.find('form').attr('action', "/console/varieties/" + (id > 0 ? ('update/' + id) : 'store'));
        if (id > 0) {
            mWin.find('[name="code"]').val($("#varietyCode"+id).text());
            mWin.find('[name="name"]').val($("#varietyName"+id).text());
            mWin.find('[name="descrition"]').val($("#varietyDesc"+id).text());
        }
        mWin.find('[name="name"]').focus();

        mWin.modal();
    }
</script>
@stop