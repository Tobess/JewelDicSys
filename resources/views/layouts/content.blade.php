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
                @yield('content-view')
            </div>
        </div>
    </div>
</div>
<!-- / content -->
@stop