@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">地区(省、市、区县、镇)信息</a></li>
@stop

@section('toolLeft')
    <button class="btn btn-sm btn-success m-b-xxs" type="button" onClick="save(0)">
        <i class="fa fa-plus"></i>
        新增
    </button>
    <select id="province" class="form-control inline w-auto" onchange="seaChange('province');" style="width: 112px;">
        <option value="0">全部省份</option>
        @foreach($provinces as $row)
            <option value="{{ $row->id }}" {{ $province == $row->id ? 'selected="selected"' : '' }}>{{ $row->name }}</option>
        @endforeach
    </select>
    @if($province > 0 && count($cities) > 0)
    <select id="city" class="form-control inline w-auto" onchange="seaChange('city');" style="width: 112px;">
        <option value="0">全部地市</option>
        @foreach($cities as $row)
            <option value="{{ $row->id }}" {{ $city == $row->id ? 'selected="selected"' : '' }}>{{ $row->name }}</option>
        @endforeach
    </select>
        @if($city > 0 && count($districts) > 0)
            <select id="district" class="form-control inline w-auto" onchange="seaChange('district');" style="width: 112px;">
                <option value="0">全部县市</option>
                @foreach($districts as $row)
                    <option value="{{ $row->id }}" {{ $district == $row->id ? 'selected="selected"' : '' }}>{{ $row->name }}</option>
                @endforeach
            </select>
        @endif
    @endif
@stop

@section('toolRight')
<div class="input-group">
    <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入地区查询" value="{{ isset($query) && $query ? $query : ''}}">
        <span class="input-group-btn">
          <button class="btn btn-sm btn-default" type="button" onclick="window.location.href='?{{ 'query='.$query.'&province='.$province.'&city='.$city.'&district='.$district }}&query='+$('#searchQueryBox').val();">搜!</button>
        </span>
</div>
@stop

@section('tableTitle')
    <th style="width:20px;">
        <label class="i-checks m-b-none">
            <input type="checkbox"><i></i>
        </label>
    </th>
    <th>名称</th>
    <th>简称</th>
    <th>经度</th>
    <th>纬度</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td id="areaName{{ $row->id }}">{{ $row->name }}</td>
        <td id="areaShortName{{ $row->id }}">{{ $row->short_name }}</td>
        <td id="areaLongitude{{ $row->id }}">{{ $row->longitude }}</td>
        <td id="areaLatitude{{ $row->id }}">{{ $row->latitude }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save('{{ $row->id }}', '{{ $row->parent_id }}')">编辑</button>
            @if($row->child <= 0)
            <a class="btn btn-xs btn-danger m-b-none" type="button" href="/console/areas/destroy/{{ $row->id }}">删除</a>
            @endif
        </td>
    </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>'&query='.$query.'&province='.$province.'&city='.$city.'&district='.$district])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows->appends(['query' => $query, 'province'=>$province, 'city'=>$city, 'district'=>$district])])
@stop

<!--编辑页面-->
@section('extend')
    @include('console.area.form')
@stop

@section('scripts')
<script>
    function save(id, parent) {
        var mWin = $("#modalWin");
        mWin.find('form').get(0).reset();
        mWin.find('form').attr('action', "/console/areas/" + (id > 0 ? ('update/' + id) : 'store'));
        if (id > 0) {
            mWin.find('[name="name"]').val($("#areaName"+id).text());
            mWin.find('[name="short_name"]').val($("#areaShortName"+id).text());
            mWin.find('[name="longitude"]').val($("#areaLongitude"+id).text());
            mWin.find('[name="latitude"]').val($("#areaLatitude"+id).text());
            mWin.find('[name="parent"]').val(parent);
        } else {
            mWin.find('[name="parent"]').val('{{ $parent or 0 }}');
        }
        mWin.find('[name="name"]').focus();

        mWin.modal();
    }

    function seaChange(type) {
        if (type == 'province') {
            window.location.href = '?province=' + $("#province").val();
        } else if (type == 'city') {
            window.location.href = '?province=' + $("#province").val() + '&city=' + $("#city").val();
        } else if (type == 'district') {
            window.location.href = '?province=' + $("#province").val() + '&city=' + $("#city").val() + '&district=' + $("#district").val();
        }
    }
</script>
@stop