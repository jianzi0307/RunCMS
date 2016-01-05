/*=============================*
	代码生成器前端相关逻辑
 *=============================*/

/**
 * 生成模块提交
 * @return 
 */
var GenModule = function() {
	//var loading = $('.inbox-loading');
	
	/**
	 * 提交post请求
	 */
	var postAjax = function() {
		//loading.show();
		var url = "/admin/genModule";
		var param = $('#moduleName').val();
		$.post(url,{'module':param},function(data){
			//loading.hide();
			var res = JSON.parse(data);
			showAlert(res.errno,res.errmsg);
		});
	}

	/**
	 * 服务器响应提示
	 * @param  string err 错误码
	 * @param  string msg 提示信息
	 * @return 
	 */
	var showAlert = function( err,msg ) {
		var showAlert = $('#showAlert');
		if( err > 0 ) {
			showAlert.html("<div class=\"alert alert-error\"><button class=\"close\" data-dismiss=\"alert\"></button><strong>Error!</strong> "+ msg +"</div>");
		}
		if( err == 0 ) {
			showAlert.html("<div class=\"alert alert-success\"><button class=\"close\" data-dismiss=\"alert\"></button><strong>Success!</strong> "+ msg +"</div>");
		}
	}

	return {
		init: function() {
			$('#submitBtn').click(function(){
				postAjax();
			});
		}
	};
}();

/**
 * 生成模型
 */
var GenModel = function() {

	/**
	 * 选择多个表的情况，禁用关联
	 * @param  boolean valid 是否可用
	 * @return 
	 */
	var validForm = function( valid ) {
		if( valid ) {
			$('#subtable').removeAttr('disabled');
			$('#relationname').removeAttr('disabled');
			$('#relationmodel').removeAttr("disabled");
		} else {
			$('#subtable').attr('disabled',"disabled");
			$('#subtable').val('');
			$('#relationname').attr('disabled',"disabled");
			$('#relationname').val('');
			$('#relationmodel').attr("disabled","disabled"); 
			$('#relationmodel').val('');
		}
		validM2M($('#relationmodel').val() == 'MANY_TO_MANY');
	}

	/**
	 * 检查多对多关联
	 * @param  boolean valid 是否可用
	 * @return 
	 */
	var validM2M = function( valid ) {
		if( !valid ) {
			$('#relationtable').attr('disabled','disabled');
			$('#relationtable').val('');
		} else {
			$('#relationtable').removeAttr('disabled');
		}
	}

	/**
	 * 提交检查
	 * @param  boolean valid 是否可用
	 * @return 
	 */
	var clickStepCheck= function(valid) {
		var tval = $('#relationmodel').val();
		var flag = true;
		if( tval ) {
			if( !$('#subtable').val() ) {
				//
				flag = false;
			}
			if( tval == 'MANY_TO_MANY' && !$('#relationtable').val() ) {
				flag = false;
			}
		}
		return flag;
	}

	/**
	 * 预览代码
	 * @return 
	 */
	var createPreviewCode = function(){

	}

	/**
	 * 追加关联代码段，并预览
	 * @return 
	 */
	var assignRelationCode = function() {

	}

	/**
	 * 提交服务器生成模型文件
	 * @return 
	 */
	var postToCreateModelFile = function() {

	}

	return {
		init: function() {

			validM2M(false);

			var selecttable = $('#selecttable');
			selecttable.change(function(){
				var sval = selecttable.val();
				if(!sval) {
					validForm(true);
				} else {
					validForm(!(sval.length > 1 ));
				}
			});

			var relationType = $('#relationmodel');
			relationType.change(function(){
				var tval = relationType.val();
				if( tval != 'MANY_TO_MANY'){
					validM2M(false);
				} else {
					validM2M(true);
				}
			});

			$('#btn1').click(function(){
				if(clickStepCheck()) {

				}
			});

			$('#btn2').click(function(){
				if(clickStepCheck()){

				}
			});

			$('#btn3').click(function(){
				if(clickStepCheck()){

				}
			});
		}
	};
}();