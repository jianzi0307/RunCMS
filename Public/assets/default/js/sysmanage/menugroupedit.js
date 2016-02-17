var MenuGroupEdit = function () {
    var postUrl;
    var ajaxPost = function () {
        var targetUrl = this.postUrl;
        var menuid = $('#menuid').val();
        var group = $.trim($('#group').val());
        var sort = $.trim($('#sort').val());
        var icon = $.trim($('#icon').val());
        var hide = $('#hide').val();
        var data = {
            menuid:menuid,
            group:group,
            sort:sort,
            icon:icon,
            hide:hide
        };
        $.post(targetUrl,data,function(dat){
            if (dat) {
                var res = JSON.parse(dat);
                if (res.errno == 0) {
                    alert(res.errmsg);
                    window.location.href = document.referrer;
                } else {
                    alert(res.errmsg);
                }
            } else {
                console.log('error.')
            }
        });
    };
    //TODO:提交数据验证
    var postIsValid = function() {
        $('#menuid');
        $('#group');
        $('#sort');
        $('#icon');
        $('#hide')
        return true;
    };
    return {
        init: function (postUrl) {
            this.postUrl = postUrl;
            $('#btn_add_ok').on('click',function(){
                if (!postIsValid()) {
                    return;
                }
                ajaxPost();
            });
            $('#btn_add_cancel').on('click',function(){
                history.back(-1);
            });
        }
    };
}();