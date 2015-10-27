var MenuGroup = function () {
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('menugroup_table',true,[true,false,true,true,true,false]);
        }
    };
}();