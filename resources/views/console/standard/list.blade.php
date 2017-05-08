@extends('layouts.slist')

@section('breadcrumb')
    <li><a class="text-muted">行业标准</a></li>
    <li class="active"><a class="text-muted">标准{{ $modName }}</a></li>
@stop

@section('toolLeft')
    <span class="input-group-btn">
      <button class="btn btn-sm btn-success" type="button" onclick="save(0)">
          <i class="fa fa-plus"></i>
          新增
      </button>
    </span>
@stop

@section('toolRight-one')
    <div class="form-horizontal" role="form">
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label text-muted">材质:</label>
            <div class="col-sm-7">
                <div class="input-group dropdown">
                    <input type="hidden" id="materialID" name="mid" value="{{ $mid }}">
                    <input type="text" class="form-control" id="tree" placeholder="请选择材质" value="{{ $mTitle }}">
                    <span class="input-group-addon" id="btn"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
            <div class="col-sm-3">
                <button class="btn btn-sm btn-default text-center" id="search">搜索!</button>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">测试标题</h4>
                    </div>
                    <div class="modal-body myModal">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary sub">提交更改</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
    </div>
@stop
@section('toolRight-two')
    <div class="input-group">
        <input id="searchQueryBox" type="text" name="query" value="{{$query}}" class="input-sm form-control"
               placeholder="请输入{{ $modName }}名称查询">
        <span class="input-group-btn">
    <button class="btn btn-sm btn-default" type="button"
            onclick="window.location.href='?mod={{ $mod }}&query='+$('#searchQueryBox').val();">搜!
    </button>
    </span>
    </div>
@stop
@section('tableTitle')
    <th>名称</th>
    <th>拼音</th>
    <th>简拼</th>
    <th>材质</th>
    <th style="width:106px;"></th>
@stop

@section('tableRows')
    @foreach ($rows as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->pinyin }}</td>
            <td>{{ $row->letter }}</td>
            <td>{{$row->m_titles}}</td>
            <td>
                <button class="btn btn-xs btn-info m-b-none" type="button"
                        onClick="save('{{ $row->name }}', '{{ $row->m_ids }}','{{$row->m_titles}}','{{$row->pinyin}}','{{$row->letter}}')">
                    编辑
                </button>
            </td>
        </tr>
    @endforeach
@stop

@section('footerLeft')
    @include('layouts.blocks.jumper', ['paginator' => $rows, 'queries'=>'&query='.$query.'&mod='.$mod])
@stop

@section('footerRight')
    @include('layouts.blocks.pager', ['paginator' =>$rows->appends(['query' => $query, 'mod' => $mod])])
@stop

<!--编辑页面-->
@section('extend')
    @include('console.standard.form')
@stop

@section('scripts')
    <script>
        function save(name, m_ids, m_titles, pinyin, letter) {
            var mWin = $("#modalWin");
            mWin.find('form').get(0).reset();

            if (name && name.length > 0) {
                mWin.find('[name="name"]').val(name);
                mWin.find('[name="origin"]').val(name);
                mWin.find('[name="pinyin"]').val(pinyin);
                mWin.find('[name="letter"]').val(letter);
                mWin.find('[name="material"]').val(m_ids);
                mWin.find('[name="material-show"]').val(m_titles);
            } else {
                mWin.find('[name="name"]').focus();
            }

            mWin.modal();
        }
        $(function () {
            $("#submitBtn").click(function (e) {
                var name = $("#name").val();
                var materials = $("#material").val();
                var pinyin = $("#pinyin").val();
                var letter = $("#letter").val();
                var origin = $("#origin").val();
                if (!name || name.length <= 0) {
                    alert('分类名称或材质不能为空！');
                    return;
                }
                $.ajax({
                    url: "/console/standard/store?mod={{ $mod }}",
                    type: "GET",
                    dataType: 'json',
                    data: {'name': name, 'materials': materials, 'pinyin': pinyin, 'letter': letter, 'origin': origin},
                    success: function () {
                        window.location.reload();
                    },
                });
            });

            $.ajax({
                url: "/console/materials/material-json",
                type: "GET",
                dataType: 'json',
                success: function (data) {

                    /**
                     * 列表
                     * */
                    var clsTreeEle = $("#tree").treeSelectChoose({
                        width: 200,
                        parseListDataFn: function () {
                            return {
                                header: '测试数据',
                                data: data
                            }
                        }, onItemClicked: function (item) {
                            $("#tree").val(item.title);
                            $("#materialID").val(item.id);
                        }, parentCanSelect: false
                    });
                    /**
                     */
                    $("#btn").click(function (e) {
                        clsTreeEle.toggle();
                    });


                    /**
                     * form表单
                     */
                    var clsTreeEle_form = $("#caiLiao").treeSelectChoose({
                        width: 200,
                        parseListDataFn: function () {
                            return {
                                header: '选择材质',
                                data: data
                            }
                        }, itemsSelected: function (item) {
                            var materialName = "";
                            var materialID = "";
                            var i = 0;
                            var count = item.length;
                            for (i; i < count; i++) {
                                if (i == item.length - 1) {
                                    materialName += item[i].title;
                                    materialID += item[i].id;
                                } else {
                                    materialName += item[i].title + ",";
                                    materialID += item[i].id + ",";
                                }
                            }
                            $("#caiLiao").val(materialName);
                            $("input[name='material']").val(materialID);
                        }, parentCanSelect: false, resourceDir: '/js/treeselect/', multiple: true
                    });
                    /**
                     */
                    $("#btn-form").click(function (e) {
                        clsTreeEle_form.toggle(function () {
                            if (clsTreeEle_form.getTree()) {
                                clsTreeEle_form.getTree().setMultipleSelected($('#material').val().split(','));
                            }
                        });
                    });


                }
            });

            /**
             * 材质搜索
             * materialID 材质ID
             * materialName 材质名称
             */
            $("#search").on("click", function () {
                var materialID = $("#materialID").val();
                var materialName = $("#tree").val();
                if (materialName == '') {
                    materialID = '';
                }
                window.location.href = '?mod={{$mod}}' + '&query=' + $("#searchQueryBox").val() + "&mid=" + materialID;
                {{--window.location.href = "?mod={{$mod}}"+"&query="+$("#searchQueryBox").val()+"&mid="+materialID;--}}
            });

        });

    </script>
@stop