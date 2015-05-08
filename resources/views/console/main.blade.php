@extends('layouts.master')

@section('title', '控制台')

@section('container')
<div class="app app-header-fixed  ">
    @include('layouts.blocks.header')

    @include('layouts.blocks.aside')

    @yield('content')

    @include('layouts.blocks.footer')
</div>
@stop