@extends('console.main')

@section('content')
<!-- content -->
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
        <ul class="breadcrumb m-b-none">
            @yield('breadcrumb')
        </ul>
        <div class="padder">
            <div class="panel panel-default">
                <div class="row wrapper">
                    <div class="col-sm-2 m-b-xs">
                        @yield('toolLeft')
                    </div>
                    <div class="col-sm-4">
                        @yield('toolCenter')
                    </div>
                    <div class="col-sm-3">
                        @yield('toolRight-one')
                    </div>
                    <div class="col-sm-3">
                        @yield('toolRight-two')
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            @yield('tableTitle')
                        </tr>
                        </thead>
                        <tbody>
                        @yield('tableRows')
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 hidden-xs">
                            @yield('footerLeft')
                        </div>
                        <div class="col-sm-4 text-center">
                            @include('layouts.blocks.counter', ['paginator' => $rows])
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            @yield('footerRight')
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
<!-- / content -->
@stop