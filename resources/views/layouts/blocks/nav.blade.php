<?php
    $navTree = [
        '行业标准',
        ['path'=>'/console/materials', 'title'=>'材质分类', 'icon'=>'fa fa-bars'],
        ['path'=>'/console/varieties', 'title'=>'样式分类', 'icon'=>'fa fa-sitemap'],
        ['path'=>'/console/brands', 'title'=>'珠宝品牌', 'icon'=>'fa fa-btc'],
        ['path'=>'/console/crafts', 'title'=>'加工工艺', 'icon'=>'icon icon-calculator'],
        ['path'=>'/console/colors', 'title'=>'宝石颜色', 'icon'=>'glyphicon glyphicon-adjust'],
        ['path'=>'/console/grades', 'title'=>'宝石等级', 'icon'=>'glyphicon glyphicon-signal'],
        ['path'=>'/console/styles', 'title'=>'珠宝款式', 'icon'=>'icon icon-fire'],
        ['path'=>'/console/morals', 'title'=>'珠宝寓意', 'icon'=>'glyphicon glyphicon-bookmark'],
        ['path'=>'/console/areas', 'title'=>'地区信息', 'icon'=>'fa fa-location-arrow'],
        ['path'=>'/console/scolor','title'=>'标准颜色','icon'=>'fa fa-tint'],
        ['path'=>'/console/scertificate','title'=>'标准证书','icon'=>'glyphicon glyphicon-certificate'],
        ['path'=>'/console/sclarity','title'=>'标准净度','icon'=>'glyphicon glyphicon-search'],
        ['path'=>'/console/scut','title'=>'标准切工','icon'=>'glyphicon glyphicon-thumbs-up'],
        ['path'=>'/console/sgrade','title'=>'标准等级','icon'=>'fa fa-sort-numeric-desc'],
        ['path'=>'/console/sshape','title'=>'标准形状','icon'=>'glyphicon glyphicon-th'],
        '系统管理',
        ['path'=>'/console/users', 'title'=>'账户管理', 'icon'=>'icon icon-users'],
        ['path'=>'/console/rules', 'title'=>'名称规则', 'icon'=>'icon icon-link'],
        ['path'=>'/console/jerror', 'title'=>'错误反馈', 'icon'=>'fa fa-exclamation-triangle'],
    ];
    $selectedNav = '/'.Route::getCurrentRoute()->getPath();
?>
<!-- list -->
<!-- nav -->
<nav ui-nav class="navi clearfix">
    <ul class="nav">
        <li {{ $selectedNav == '/console' ? 'class=active' : '' }}>
            <a href="/console">
                <i class="fa fa-dashboard text-info-lter"></i>
                <span>综合概况</span>
            </a>
        </li>
        @foreach ($navTree as $nav)
            @if (is_string($nav))
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span>{{ $nav }}</span>
                </li>
            @else
                <li {{ $selectedNav == $nav['path'] ? 'class=active' : '' }}>
                    <a href="{{ $nav['path'] }}">
                        <i class="{{ $nav['icon'] }} text-info-lter"></i>
                        <span class="font-bold">{{ $nav['title'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
<!-- nav -->
<!-- / list -->