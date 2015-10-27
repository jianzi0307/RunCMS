var Validate = function () {
    return {
        //检查结束日期是否大于开始日期
        checkEndTime: function (){
            var startTime = $.trim($("#startDate").val());
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));
            var endTime = $.trim($("#endDate").val());
            var end = new Date(endTime.replace("-", "/").replace("-", "/"));
            if(end < start){
                return false;
            }
            return true;
        },

        init: function () {
            //错误提示元素标签
            $.validator.setDefaults({
                errorElement: 'span'
            });

            $.extend($.validator.messages, {
                required: "必填",
                remote: "请修正此栏位",
                email: "请输入有效的电子邮件",
                url: "请输入有效的网址",
                date: "请输入有效的日期",
                dateISO: "请输入有效的日期 (YYYY-MM-DD)",
                number: "请输入正确的数字",
                digits: "只可输入数字",
                creditcard: "请输入有效的信用卡号码",
                equalTo: "你的输入不相同",
                extension: "请输入有效的后缀",
                maxlength: $.validator.format("最多 {0} 个字"),
                minlength: $.validator.format("最少 {0} 个字"),
                rangelength: $.validator.format("请输入长度为 {0} 至 {1} 之間的字串"),
                range: $.validator.format("请输入 {0} 至 {1} 之间的数值"),
                max: $.validator.format("请输入不大于 {0} 的数值"),
                min: $.validator.format("请输入不小于 {0} 的数值")
            });

            //检查结束日期和开始日期
            $.validator.addMethod('checkDate',function (value,element) {
                return this.optional(element) || Validate.checkEndTime();
            },"开始日期不能大于结束日期");

        }
    }
}();