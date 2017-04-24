<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title')-金企联盟词库系统</title>
    <meta name="description" content="黄金珠宝行业，珠宝产品名称词库系统。" />
    <meta name="keywords" content="黄金, 珠宝, 珠宝词库, 珠宝名称, 珠宝行业标准, 金算大师, 金企联盟, 智爱科技" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ elixir("css/all.css") }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('styles')
</head>
<body>

@yield('container')

<!-- Scripts -->
<script src="{{ asset('build/js/jquery.min.js') }}"></script>
<script src="{{ asset('build/js/bootstrap.js') }}"></script>
<script src="{{ elixir("js/all.js") }}"></script>
<link href="{{ asset('build/js/treeselect/treeselect.css') }}?t={{ time() }}" rel="stylesheet" type="text/css" media="screen">
<script type="text/javascript" src="{{ asset('build/js/treeselect/treeselect.js') }}?t={{ time() }}"></script>

@yield('scripts')

    </body>
</html>