var ConfSetting = function () {
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('confsetting_table',true,[true,true,true,true,true,false]);
        }
    };
}();
