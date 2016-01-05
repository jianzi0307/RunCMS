var TableManaged = function () {
    //添加组提交验证
    var addUserGroupValid = function() {
        return true;
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            // 用户组管理
            $('#user_groups_table').dataTable({
                "aoColumns": [
                    { "bSortable": false },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": false },
                    { "bSortable": true },
                    { "bSortable": false }
                ],
                "aLengthMenu": [
                    [10, 15, 20, -1],
                    [10, 15, 20, "全部显示"]
                ],
                // set the initial value
                "iDisplayLength": 10,
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ 条/页",
                    "oPaginate": {
                        "sPrevious": "上一页",
                        "sNext": "下一页"
                    }
                },
                "aoColumnDefs": [{
                    'bSortable': false,
                    'aTargets': [0]
                }
                ]
            });

            jQuery('#user_groups_table .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            //添加组
            $('#btn_add_user_group').click(function(){
                if( addUserGroupValid() ) {
                    var groupName = $('#groupName').val();
                    var groupDesc = $('#groupDesc').val();
                    var groupStatus = $('#groupActive').is(':checked') ? 1 : 0;

                    $.post('/admin/usergroup/add',
                        {"groupName":groupName,"groupStatus":groupStatus,"groupDescription":groupDesc},
                        function(data) {
                            console.log(data);
                        }
                    );
                }
            });

            //添加组
            $('#btn_cancel_user_group').click(function(){
                history.back(-1);
            });

            //Modal层显示触发
            $('#modal-from-dom').on('show', function() {
                var id = $(this).data('id'), confirmBtn = $(this).find('.blue');
                // alert(id);
            });

            $('.btn_confirm_delete').bind('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                alert(id);
                $('#modal-from-dom').data('id', id).modal('show');
            });

            $('body').on('hidden', '.modal', function () {
                $(this).removeData('modal');
            });

            jQuery('#user_groups_table_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
            jQuery('#user_groups_table_wrapper .dataTables_length select').addClass("m-wrap small"); // modify table per page dropdown
            //jQuery('#user_groups_table_wrapper .dataTables_length select').select2(); // initialzie select2 dropdown


            // 用户管理
            $('#users_table').dataTable({
                "aoColumns": [
                    { "bSortable": false },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": false }
                ],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "全部显示"]
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ 条/页",
                    "oPaginate": {
                        "sPrevious": "上一页",
                        "sNext": "下一页"
                    }
                },
                "aoColumnDefs": [{
                    'bSortable': false,
                    'aTargets': [0]
                }
                ]
            });

            jQuery('#users_table .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#users_table_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
            jQuery('#users_table_wrapper .dataTables_length select').addClass("m-wrap small"); // modify table per page dropdown
            //jQuery('#users_table_wrapper .dataTables_length select').select2(); // initialzie select2 dropdown

            // 权限管理
            $('#privileges_table').dataTable({
                "aoColumns": [
                    { "bSortable": false },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": false }
                ],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "全部显示"]
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ 条/页",
                    "oPaginate": {
                        "sPrevious": "上一页",
                        "sNext": "下一页"
                    }
                },
                "aoColumnDefs": [{
                    'bSortable': false,
                    'aTargets': [0]
                }
                ]
            });

            jQuery('#privileges_table .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#privileges_table_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
            jQuery('#privileges_table_wrapper .dataTables_length select').addClass("m-wrap small"); // modify table per page dropdown
            //jQuery('#privileges_table_wrapper .dataTables_length select').select2(); // initialzie select2 dropdown

            // 日志管理
            $('#logs_table').dataTable({
                "aoColumns": [
                    { "bSortable": false },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": true },
                    { "bSortable": false },
                ],
                "aLengthMenu": [
                    [15, 30, 50,100, -1],
                    [15, 30, 50,100, "全部显示"]
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ 条/页",
                    "oPaginate": {
                        "sPrevious": "上一页",
                        "sNext": "下一页"
                    }
                },
                "aoColumnDefs": [{
                    'bSortable': false,
                    'aTargets': [0]
                }
                ]
            });

            jQuery('#logs_table .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#logs_table_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
            jQuery('#logs_table_wrapper .dataTables_length select').addClass("m-wrap small"); // modify table per page dropdown
            //jQuery('#logs_table_wrapper .dataTables_length select').select2(); // initialzie select2 dropdown

        }

    };

}();