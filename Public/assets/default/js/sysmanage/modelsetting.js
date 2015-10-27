var ModelSetting = function () {
    //添加组提交验证
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('modelsetting_table', true, [false,true,true,true,true,false]);
        }
    };
}();
