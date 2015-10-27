var FormFileUpload = function () {
    return {
        init: function () {
            var uploader = $('#fileupload');
            uploader.fileupload({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                dataType: 'json',
                url: '/admin/sewageplant/upload',
                acceptFileTypes: /(\.|\/)(xls|csv|xlsx)$/i,
                maxNumberOfFiles : 1,
                maxFileSize: 5000000 //5M
            });
            uploader.bind('fileuploaddone', function(e,data){
                window.location.href="/admin/sewageplant/calcresult";
                //uploader.fileupload('enable');
                //uploader.find("input:file").removeAttr('disabled');
                //uploader.find(".fileinput-button").removeClass('disabled');//removeAttr('disabled');
            });
            uploader.find("input:file").removeAttr('disabled');
            // 服务器状态检查
            if ($.support.cors) {
                $.ajax({
                    url: '/admin/sewageplant/upload',
                    type: 'HEAD'
                }).fail(function () {
                    $('<span class="alert alert-error"/>')
                        .text('Upload server currently unavailable - ' +
                        new Date())
                        .appendTo('#fileupload');
                });
            }
            //App.initUniform('.fileupload-toggle-checkbox');
        }
    };
}();