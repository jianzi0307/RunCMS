var MenuSettingEdit = function () {
    var postUrl;
    var ajaxPost = function () {
        var targetUrl = this.postUrl;
        var title = $('#title').val();
        var sort = $('#sort').val();
        var url = $('#url').val();
        var pid = $('#pid').val();
        var groupid = $('#groupid').val();
        var hide = $('#hide').val();
        var is_dev = $('#is_dev').val();
        var tip = $('#tip').val();
        var icon = $('#icon').val();
        var data = {
            title:title,
            url:url,
            title:title,
            pid:pid,
            groupid:groupid,
            hide:hide,
            is_dev:is_dev,
            sort:sort,
            tip:tip,
            icon:icon
        };
        $.post(targetUrl,data,function(dat){
            if (dat) {
                var res = JSON.parse(dat);
                if (res.errno == 0) {
                    alert(res.errmsg);
                    location.href = document.referrer;
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
        $('#title');
        $('#sort');
        $('#url');
        $('#pid');
        $('#groupid');
        $('#hide');
        $('#is_dev');
        $('#tip');
        $('#icon')
        return true;
    };
    return {
        init: function (postUrl) {
            this.postUrl = postUrl;
            $('#btn_menusetting_add_ok').on('click',function(){
                if (!postIsValid()) {
                    return;
                }
                ajaxPost();
            });
            $('#btn_menusetting_add_cancel').on('click',function(){
                history.back(-1);
            });
        }
    };
}();
