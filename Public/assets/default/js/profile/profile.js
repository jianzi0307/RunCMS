var Profile = function () {
    return {
        init: function () {
            var oldpwd = $('#oldpwd');
            var pwd = $('#pwd');
            var repwd = $('#repwd');

            //规则提示汉化
            $.extend($.validator.messages, {
                minlength:"不少于6个字符",
                maxlength:"最多不超过12字符"
            });

            //对form进行验证
            $("#jsForm").validate({
                rules:{
                    oldpwd:{required:true},
                    pwd:{required:true,minlength:6,maxlength:12},
                    repwd:{required:true,equalTo:pwd}
                },
                submitHandler:function(form) {
                    var opwd = $.trim(oldpwd.val());
                    var npwd = $.trim(pwd.val());
                    var rpwd = $.trim(repwd.val());
                    var data = {
                        oldpwd:opwd,
                        pwd:npwd,
                        repwd:rpwd
                    };

                    $.post('/Admin/Profile/pwd',data,function(res){
                        res = JSON.parse(res);
                        if (res.errno == 0) {
                            alert(res.errmsg);
                            location.href='/Admin/Login';
                        } else {
                            alert(res.errmsg);
                        }
                    });
                }
            });
        }
    };
}();
