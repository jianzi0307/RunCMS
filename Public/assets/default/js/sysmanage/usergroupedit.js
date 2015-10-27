var UserGroupEdit = function () {
    var postUrl;
    var ajaxPost = function () {
        var targetUrl = this.postUrl;
        var title = $('#title').val();
        var description = $('#description').val();
        var status = $('#status').val();
        var data = {
            title:title,
            description:description,
            status:status
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
        $('#description');
        $('#status')
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