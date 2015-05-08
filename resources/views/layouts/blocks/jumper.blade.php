@if ($paginator->total() > 0)
<select id="pageChooserBox" class="input-sm form-control w-sm inline v-middle">
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
    <option value="{{ $i }}" {{ $paginator->currentPage() == $i ? 'selected="selected"' : '' }}>第{{ $i }}页</option>
    @endfor
</select>
<button class="btn btn-sm btn-default" onclick="window.location.href='?page='+$('#pageChooserBox').val()+'{{ isset($queries) ? $queries : '' }}';">跳转</button>
@endif