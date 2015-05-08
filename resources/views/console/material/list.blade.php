@extends('layouts.content')

@section('content')
<!-- content -->
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
        <ul class="breadcrumb m-b-none">
            <li><a class="text-muted">会员中心</a></li>
            <li class="active"><a class="text-muted">会员列表</a></li>
        </ul>
        <div class="padder">
            <div class="panel panel-default">
                <div class="row wrapper">
                    <div class="col-sm-5 m-b-xs">
                        <select id="searchTypeBox" class="input-sm form-control w-sm inline v-middle">
                            <option value="0">全部客户</option>
                            <option value="1" {{ isset($cType) && $cType == 1 ? 'selected="selected"' : ''}}>今日来客</option>
                            <option value="2" {{ isset($cType) && $cType == 2 ? 'selected="selected"' : ''}}>即将到期</option>
                            <option value="3" {{ isset($cType) && $cType == 3 ? 'selected="selected"' : ''}}>已经到期</option>
                        </select>
                        <button class="btn btn-sm btn-default" onclick="window.location.href='?type='+$('#searchTypeBox').val();">筛选</button>
                    </div>
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input id="searchQueryBox" type="text" class="input-sm form-control" placeholder="请输入客户手机号、企业号" {{ isset($query) ? 'value="'.$query.'"' : ''}}>
                <span class="input-group-btn">
                  <button class="btn btn-sm btn-default" type="button" onclick="window.location.href='?query='+$('#searchQueryBox').val();">搜!</button>
                </span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            <th style="width:20px;">
                                <label class="i-checks m-b-none">
                                    <input type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>手机号</th>
                            <th>企业号</th>
                            <th>企业名称</th>
                            <th>注册时间</th>
                            <th style="width:30px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client)
                        <tr>
                            <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                            <td>{{ $client->mobile }}</td>
                            <td>{{ $client->domain }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->created_at }}</td>
                            <td>
                                <a href class="active" ui-toggle-class><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 hidden-xs">
                            <select id="pageChooserBox" class="input-sm form-control w-sm inline v-middle">
                                @for ($i = 1; $i <= $clients->getLastPage(); $i++)
                                <option value="{{ $i }}" {{ $clients->getCurrentPage() == $i ? 'selected="selected"' : '' }}>第{{ $i }}页</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-default" onclick="window.location.href='?query='+$('#searchQueryBox').val()+'&type='+$('#searchTypeBox').val()+'&page='+$('#pageChooserBox').val();">跳转</button>
                        </div>
                        <div class="col-sm-4 text-center">
                            <small class="text-muted inline m-t-sm m-b-sm">当前正显示第{{$clients->getFrom()}}-{{$clients->getTo()}}条数据，本页{{$clients->count()}}条，共{{$clients->getTotal()}}条</small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            @include('layouts.blocks.pager', ['paginator' => $clients->appends(['query' => $query, 'type' => $type])])
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
<!-- / content -->
@stop