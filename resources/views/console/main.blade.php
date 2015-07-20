@extends('layouts.master')

@section('title', '控制台')

@section('container')
<!--Main Container-->
<div class="app app-header-fixed app-aside-fixed">
    @include('layouts.blocks.header')

    @include('layouts.blocks.aside')

    @yield('content')

    @include('layouts.blocks.footer')
</div>

<!--Other Extend-->
@yield('extend')
@stop