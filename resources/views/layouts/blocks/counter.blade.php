@if ($paginator->total() > 0)
<small class="text-muted inline m-t-sm m-b-sm">
    当前正显示第{{ $paginator->perPage()*($paginator->currentPage() - 1) + 1 }}-{{ $paginator->perPage()*($paginator->currentPage() - 1) + $paginator->count() }}条数据，本页{{ $paginator->count() }}条，共{{$paginator->total()}}条
</small>
@endif