var ConfSettingEdit = function () {
    var postUrl;
    var ajaxPost = function () {
        var targetUrl = this.postUrl;
        var flag = $('#flag').val();
        var title = $('#title').val();
        var sort = $('#sort').val();
        var conftype = $('#conftype').val();
        var confgroup = $('#confgroup').val();
        var confvalue = $('#confvalue').val();
        var confitem = $('#confitem').val();
        var confdesc = $('#confdesc').val();
        var data = {
            name:flag,
            type:conftype,
            title:title,
            group:confgroup,
            extra:confitem,
            remark:confdesc,
            value:confvalue,
            sort:sort
        };
        $.post(targetUrl,data,function(dat){
            if (dat) {
                var res = JSON.parse(dat);
                if (res.errno == 0) {
                    alert(res.errmsg);
                    window.location.href='/Admin/System/Confsetting/Index';
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
        $('#flag');
        $('#title');
        $('#sort');
        $('#conftype');
        $('#confgroup');
        $('#confvalue');
        $('#confitem');
        $('#confdesc');
        return true;
    };
    return {
        init: function (postUrl) {
            this.postUrl = postUrl;
            $('#btn_configsetting_add_ok').on('click',function(){
                if (!postIsValid()) {
                    return;
                }
                ajaxPost();
            });
            $('#btn_configsetting_add_cancel').on('click',function(){
                history.back(-1);
            });
        }
    };
}();
