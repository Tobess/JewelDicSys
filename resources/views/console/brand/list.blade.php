@extends('layouts.list')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">珠宝品牌</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button" onclick="save(0)">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop
@section('styles')
    <style type="text/css">
        #pic {
            width: 370px;
            height: 200px;
        }
    </style>
@stop
@section('toolRight')
    <div class="input-group">
        <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入品牌查询"
               value="{{ isset($query) && $query ? $query : ''}}">
        <span class="input-group-btn">
          <button class="btn btn-sm btn-default" type="button"
                  onclick="window.location.href='?query='+$('#searchQueryBox').val();">搜!
          </button>
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
    <th>拼音</th>
    <th>简拼</th>
    <th>品牌logo</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
        <tr>
            <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
            <td id="brandName{{ $row->id }}">{{ $row->name }}</td>
            <td id="brandPinyin{{ $row->id }}">{{ $row->pinyin }}</td>
            <td id="brandLetter{{ $row->id }}">{{ $row->letter }}</td>
            <td><img src="{{ url('/logo/brand/' . $row->id) }}?t={{ time() }}" id="brandLogo{{ $row->id }}" width="40"
                     height="40" alt="品牌logo"></td>
            <td>
                <button class="btn btn-xs btn-info m-b-none" type="button" onClick="save({{ $row->id }})">编辑</button>
                <a class="btn btn-xs btn-danger m-b-none" type="button"
                   href="/console/brands/destroy/{{ $row->id }}">删除</a>
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
    @include('console.brand.form')
@stop

@section('scripts')
    <script>
        function save(id) {
            var mWin = $("#modalWin");
            mWin.find('form').get(0).reset();
            mWin.find('form').attr('action', "/console/brands/" + (id > 0 ? ('update/' + id) : 'store'));
            if (id > 0) {
                mWin.find('[name="name"]').val($("#brandName" + id).text());
                mWin.find('[name="pinyin"]').val($("#brandPinyin" + id).text());
                mWin.find('[name="letter"]').val($("#brandLetter" + id).text());
                $("#pic").attr('src', $("#brandLogo" + id).attr("src"));

            } else {
                $("#old").attr('style', 'display: none');
            }
            mWin.find('[name="name"]').focus();
            mWin.modal();
        }

        $(function () {
            //
            $("button[type='submit']").on('click', function () {
                var name = $("input[name='name']").val();
                if (name == '') {
                    alert('品牌名称不能为空');
                    return 0;
                }
            });

            $("#pic").click(function () {
                $("#upload").click(); //隐藏了input:file样式后，点击头像就可以本地上传
                $("#upload").on("change", function () {
                    var objUrl = getObjectURL(this.files[0]); //获取图片的路径，该路径不是图片在本地的路径
                    if (objUrl) {
                        $("#pic").attr("src", objUrl); //将图片路径存入src中，显示出图片
                    }
                });
            });

        });

        //建立一個可存取到該file的url
        function getObjectURL(file) {
            var url = null;
            if (window.createObjectURL != undefined) { // basic
                url = window.createObjectURL(file);
            } else if (window.URL != undefined) { // mozilla(firefox)
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL != undefined) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        }


    </script>
@stop