var MenuGroup = function () {
    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            App.wrapTable('menugroup_table',false,[false,false,false,false,false,false]);
        }
    };
}();