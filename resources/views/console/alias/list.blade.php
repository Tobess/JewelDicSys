@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a href="/console/{{ $tItem['table'] }}" class="text-muted">{{ $tItem['name'] }}</a></li>
    <li class="active"><a class="text-muted">[{{ $pItem->name }}]别名</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button" onclick="save(0)">
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
    <th>拼音</th>
    <th>简拼</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
    <tr>
        <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        <td id="aliasName{{ $row->id }}">{{ $row->name }}</td>
        <td>{{ $row->pinyin }}</td>
        <td>{{ $row->letter }}</td>
        <td>
            <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save({{ $row->id }})">编辑</button>
            <a class="btn btn-xs btn-danger m-b-none" type="button" href="/console/aliases/destroy/{{ $row->id }}">删除</a>
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
    @include('console.alias.form')
@stop

@section('scripts')
    <script>
        function save(id) {
            var mWin = $("#modalWin");
            mWin.find('form').get(0).reset();
            mWin.find('form').attr('action', "/console/aliases/" + (id > 0 ? ('update/' + id) : 'store'));
            if (id > 0) {
                mWin.find('[name="name"]').val($("#aliasName"+id).text());

            }
            mWin.find('[name="rel_type"]').val('{{ $tItem['id'] }}');
            mWin.find('[name="rel_id"]').val('{{ $pItem->id }}');
            mWin.find('[name="name"]').focus();

            mWin.modal();
        }
    </script>
@stop