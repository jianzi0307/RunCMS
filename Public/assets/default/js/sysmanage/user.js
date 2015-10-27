var User = function () {
    return {
        init: function (postUrl) {
            var postUrl = postUrl;
            var uname = $('#uname');
            var userpwd = $('#userpwd');
            var userrepwd = $('#userrepwd');
            var group = $('#group');
            var factoryname = $('#factoryname');
            var factoryaddress = $('#factoryaddress');
            var factoryscale = $('#factoryscale');
            var maintechnology = $('#maintechnology');
            var personliable = $('#personliable');
            var dutyname = $('#dutyname');
            var personphone = $('#personphone');
            var regname = $('#regname');
            var regdutyname = $('#regdutyname');
            var regpersonphone = $('#regpersonphone');
            var blocked = $('#blocked');

            //错误提示元素标签
            $.validator.setDefaults({
                errorElement: 'span'
            });

            //规则提示汉化
            $.extend($.validator.messages, {
                required: '必填',
                equalTo: "请再次输入相同的值",
                minlength:"不少于6个字符",
                maxlength:"最多不超过12字符",
                rangelength:"请输入4到12位的字符串"
            });

            //对form进行验证
            $("#addJS").validate({
                rules:{
                    uname:{required:true,rangelength:[4,12]},
                    userpwd:{required:true,minlength:6,maxlength:12},
                    userrepwd:{required:true,equalTo:userpwd},
                    factoryname:{required:true},
                    factoryaddress:{required:true},
                    factoryscale:{required:true},
                    maintechnology:{required:true},
                    personliable:{required:true},
                    dutyname:{required:true},
                    personphone:{required:true,mobile:true},
                    regname:{required:true},
                    regdutyname:{required:true},
                    regpersonphone:{required:true},
                    blocked:{required:true},
                    group:{required:true}
                },
                submitHandler:function(form) {
                    var u = $.trim(uname.val());
                    var npwd = $.trim(userpwd.val());
                    var rpwd = $.trim(userrepwd.val());
                    var g = $.trim(group.val());
                    var fn = $.trim(factoryname.val());
                    var fa = $.trim(factoryaddress.val());
                    var fs = $.trim(factoryscale.val());
                    var ml = $.trim(maintechnology.val());
                    var pl = $.trim(personliable.val());
                    var dn = $.trim(dutyname.val());
                    var pp = $.trim(personphone.val());
                    var rn = $.trim(regname.val());
                    var rdn = $.trim(regdutyname.val());
                    var rpp = $.trim(regpersonphone.val());
                    var bd = $.trim(blocked.val());
                    var data = {
                        uname:u,
                        passwd:npwd,
                        expirtime:'',
                        repwd:rpwd,
                        group:g,
                        factoryname:fn,
                        factoryaddress:fa,
                        factoryscale:fs,
                        maintechnology:ml,
                        personliable:pl,
                        dutyname:dn,
                        personphone:pp,
                        regname:rn,
                        regdutyname:rdn,
                        regpersonphone:rpp,
                        blocked:bd
                    };

                    $.post(postUrl,data,function(res){
                        res = JSON.parse(res);
                        if (res.errno == 0) {
                            alert(res.errmsg);
                            //history.back(-1);
                            location.href = document.referrer;
                        } else {
                            alert(res.errmsg);
                        }
                    });
                }
            });

            //对form进行验证
            $("#editJS").validate({
                rules:{
                    uname:{required:true,rangelength:[4,12]},
                    userpwd:{minlength:6,maxlength:12},
                    userrepwd:{equalTo:userpwd},
                    factoryname:{required:true},
                    factoryaddress:{required:true},
                    factoryscale:{required:true},
                    maintechnology:{required:true},
                    personliable:{required:true},
                    dutyname:{required:true},
                    personphone:{required:true,mobile:true},
                    regname:{required:true},
                    regdutyname:{required:true},
                    regpersonphone:{required:true},
                    blocked:{required:true},
                    group:{required:true}
                },
                submitHandler:function(form) {
                    var u = $.trim(uname.val());
                    var npwd = $.trim(userpwd.val());
                    var rpwd = $.trim(userrepwd.val());
                    var g = $.trim(group.val());
                    var fn = $.trim(factoryname.val());
                    var fa = $.trim(factoryaddress.val());
                    var fs = $.trim(factoryscale.val());
                    var ml = $.trim(maintechnology.val());
                    var pl = $.trim(personliable.val());
                    var dn = $.trim(dutyname.val());
                    var pp = $.trim(personphone.val());
                    var rn = $.trim(regname.val());
                    var rdn = $.trim(regdutyname.val());
                    var rpp = $.trim(regpersonphone.val());
                    var bd = $.trim(blocked.val());
                    var data = {
                        uname:u,
                        passwd:npwd,
                        expirtime:'',
                        repwd:rpwd,
                        group:g,
                        factoryname:fn,
                        factoryaddress:fa,
                        factoryscale:fs,
                        maintechnology:ml,
                        personliable:pl,
                        dutyname:dn,
                        personphone:pp,
                        regname:rn,
                        regdutyname:rdn,
                        regpersonphone:rpp,
                        blocked:bd
                    };

                    $.post(postUrl,data,function(res){
                        res = JSON.parse(res);
                        if (res.errno == 0) {
                            alert(res.errmsg);
                            //history.back(-1);
                            location.href = document.referrer;
                        } else {
                            alert(res.errmsg);
                        }
                    });
                }
            });

            //自定义手机号验证
            $.validator.addMethod('mobile',function(value,element,params){
                var mobile = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
                return mobile.test(value);
            },'请填写正确的手机号');
        }
    };
}();
