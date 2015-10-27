/**
 * 登录模块
 */
var Login = function () {
    //请求
	var ajaxPost = function () {
    	var username = $('#username').val();
    	var password = $('#password').val();
        var captcha = $('#captcha').val();
    	$.post('/admin/login/loginAuth',{"username":username,"password":password,"captcha":captcha},function(data){
    		if( data ) {
    			var res = JSON.parse(data)
    			if( !res ) {
    				return;
    			}
    			if( res.errno == 0 ) {
    				window.location.href = "/admin";
    			} else {
                    showAlert(res.errmsg);
    			}
    		} else {
    			return;
    		}
    	});
    };

    //刷新验证码
    var changeCaptcha = function( data ) {
        //var captcha_url = '/admin/login/showCaptcha/?rand=' + Math.random();
        $('#captcha-pic').attr('src',data);
    };

    var flushCaptcha = function() {
        $('.captcha-layer').show();
        var captcha_url = '/admin/login/showCaptcha/?rand=' + Math.random();
        $.get(captcha_url,function( data ){
            $('.captcha-layer').hide();
            if (data) {
                changeCaptcha(data);
            } else {
                return;
            }
        })
    }

    //显示警告窗
    var showAlert = function(errmsg) {
        $('.alert_error_area').html('');
        $('.alert_error_area').html('<div class="alert alert-error alert-dismissible hide" role="alert">'
        +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
        +'</button>'
        +'<span></span>'
        +'</div>');
        $('.alert-error span').html(errmsg);
        $('.alert-error').show();
    };

    return {
        //main function to initiate the module
        init: function () {
           $('.login-form').validate({
	            errorElement: 'label', //default input error message container
	            errorClass: 'help-inline', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                username: {
	                    required: true
	                },
	                password: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                }
	            },
	            messages: {
	                username: {
	                    required: "用户名不能为空."
	                },
	                password: {
	                    required: "密码不能为空."
	                }
	            },
	            invalidHandler: function (event, validator) { //display error alert on form submit
                    showAlert('用户名和密码不能为空!');
	            },
	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.control-group').addClass('error'); // set error class to the control group
	            },
	            success: function (label) {
	                label.closest('.control-group').removeClass('error');
	                label.remove();
	            },
	            errorPlacement: function (error, element) {
	                error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
	            },
	            submitHandler: function (form) {
	            	ajaxPost();
	            }
	        });
	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    ajaxPost();
	                }
	                return false;
	            }
	        });
            $("#change-captcha").on('click',function(){
                flushCaptcha();
            });
            flushCaptcha();
        }
    };
}();