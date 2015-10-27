var MenuSetting = function () {
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('menusetting_table',true,[true,false,true,true,true,true,true,true,true,false]);
        }
    };
}();