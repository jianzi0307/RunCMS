/**
 * Created by jianzi0307 on 15/10/28.
 */
var Logs = function () {
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('logs_table',false,[false,false,false,false,false,false,false,false,false]);
        }
    };
}();