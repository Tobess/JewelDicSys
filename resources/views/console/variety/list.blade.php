@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">样式分类</a></li>
@stop

@section('toolLeft')
    <button class="btn btn-sm btn-success" type="button" onClick="save(0, 1)">
        <i class="fa fa-plus"></i>
        新增大类
    </button>
    <button class="btn btn-sm btn-success" type="button" onClick="save(0, 2)">
        <i class="fa fa-plus"></i>
        新增中类
    </button>
    <button class="btn btn-sm btn-success" type="button" onClick="save(0, 3)">
        <i class="fa fa-plus"></i>
        新增小类
    </button>
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
    <th style="width:159px;"></th>
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
            <a href="/console/aliases?type=3&parent={{ $row->id }}" class="btn btn-xs btn-info m-b-none" type="button">别名</a>
            <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save({{ $row->id }}, 0)">编辑</button>
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
    function save(id, type) {
        var mWin = $("#modalWin");
        mWin.find('form').get(0).reset();
        mWin.find('form').attr('action', "/console/varieties/" + (id > 0 ? ('update/' + id) : 'store'));
        if (id > 0) {
            mWin.find('[name="code"]').val($("#varietyCode"+id).text());
            mWin.find('[name="name"]').val($("#varietyName"+id).text());
            mWin.find('[name="descrition"]').val($("#varietyDesc"+id).text());
        }
        mWin.find('[name="name"]').focus();

        var pChooser = mWin.find('select[name="parent"]');
        pChooser.children().remove();
        $.ajax({
            url: "/console/varieties/parent-list/" + type,
            data:{'variety':id},
            type: "GET",
            dataType:'json',
            success:function(data){
                for (var i = 0; i < data.length; i++) {
                    if (data.hasOwnProperty(i)) {
                        var pItem = data[i];
                        if (pItem.children && pItem.children.length > 0) {
                            pChooser.append('<optgroup label="'+pItem.name+'">');
                            for (var s = 0; s < pItem.children.length; s++) {
                                if (pItem.children.hasOwnProperty(i)) {
                                    var sItem = pItem.children[s];
                                    sItem && pChooser.append('<option value='+sItem.id+'>&nbsp;&nbsp;&nbsp;&nbsp;'+sItem.name+'</option>');
                                }
                            }
                            mWin.find('select[name="parent"]').append('</optgroup>');
                        } else {
                            pItem && pChooser.append('<option value='+pItem.id+'>'+pItem.name+'</option>');
                        }
                    }
                }
                mWin.modal();

                if (id > 0) {
                    $.ajax({
                        url: "/console/varieties/profile/" + id,
                        type: "GET",
                        dataType:'json',
                        success:function(data){
                            for (var key in data) {
                                if (data.hasOwnProperty(key)) {
                                    var value = data[key];
                                    mWin.find('[name="'+key+'"]').val(value);
                                }
                            }
                        },
                        error:function(error) {
                            alert('获取材质信息失败。');
                        }
                    });
                }
            },
            error:function(error) {
            }
        });
    }
</script>
@stop