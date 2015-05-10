<!-- Modal -->
<div class="modal fade @yield('modalStyle')" @yield('modalAttr') id="{{ isset($modalId) ? $modalId : 'modalWin' }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="POST" @yield('modalForm')>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    @section('modalHeader')
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalLabel">@yield('modalTitle')</h4>
                    @show
                </div>
                @yield('modalBody')
                <div class="modal-footer">
                    @section('modalFooter')
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                    @show
                </div>
            </form>
        </div>
    </div>
</div>