@extends('layouts.master')

@section('title', '首页')

@section('container')
<div class="app">
    <div class="wrapper-md w-auto-xs">
        <form action="#" class="m-b-md">
            <div class="input-group">
                <input type="text" class="form-control input-lg" placeholder="请输入拼音搜索" id="queryBox">
                <span class="input-group-btn">
                    <a href="/auth/login" class="btn btn-lg btn-primary" type="button">登录</a>
                </span>
            </div>
        </form>
        <p class="m-b-md" id="countBox"></p>
        <tabset class="tab-container">
            <tab>
                <ul class="list-group no-borders m-b-none" id="resultBox">

                </ul>
            </tab>
        </tabset>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(function(){
            $("#queryBox").keyup(function(){
                var val = $(this).val();
                if (val.length >= 3) {
                    $.ajax({
                        url: "/search",
                        data: {query:val},
                        type: "GET",
                        dataType:'json',
                        success:function(data){
                            var len;
                            if (data && (len = data.length) > 0) {
                                $("#countBox").html('系统已经为您找到<strong>' + len + '</strong>条记录');
                                var $results = [];
                                for (var i = 0; i < len; i++) {
                                    if (data.hasOwnProperty(i)) {
                                        var item = data[i];
                                        $results.push('');
                                    }
                                }
                                $("#resultBox").html($results.join());
                            } else {
                                clear();
                            }
                        },
                        error:function(error) {
                            clear();
                        }
                    });
                } else {
                    clear();
                }
            });
        });

        function clear() {
            $("#resultBox").children().remove();
            $("#countBox").text('');
        }
    </script>
@stop
