<div>

</div>

<script>
    function generate() {
        var type = '{{ $relType }}';
        if (type <= 0) {
            alert('无效的名称元素类型。');
            return;
        }

        $.ajax({
            url: "/console/generate-cache",
            data:{'type':type},
            type: "GET",
            dataType:'json',
            success:function(data){
                if (data) {
                    data.message ? alert(data.message) : alert('验证码发送成功，请联系系统管理，所要验证码。');
                } else {
                    stopLoading = true;
                    alert('未知错误。');
                }
            },
            error:function(error) {
                stopLoading = true;
                alert('验证码发送失败。');
            }
        });
    }
</script>