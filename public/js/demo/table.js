$(function () {
    TableUtil.init();
});
TableUtil = function (me) {
    var tableUrl = "/demo/table/list?dspname=fengjin1";
    return me = {
        _initOwn: function () {
            $("#search").on("click", function () {
                var dspname = $("#search1").val();
                TableList.search("demotable", "/demo/table/list?dspname=" + dspname, {
                    "searchs": {
                        "dspname": dspname,
                        "catetorys": {"c1": "name1", "c2": "name2"}
                    }
                });
            });
        },
        init: function () {
            me._initOwn();
            me._createList();
        },
        _createList: function () {
            var aoColumns = [{
                "sTitle": "",
                "data": "dspid"
            }, {
                "sTitle": "dsp名称",
                "data": "dspname"
            }, {
                "sTitle": "时间戳",
                "data": "tm"
            }];
            var oSetting = {
                "tableId": "demotable",
                "chk": {
                    "display": true
                },
                "url": tableUrl,
                "aoColumns": aoColumns,
                "toolbar": {
                    "add": {
                        "info": "添加", "func": function () {
                            alert(1);
                        }
                    }
                },
                "opt": {
                    "edit": {
                        "display": 1,
                        "info": "编辑",
                        "func": me._edit
                    }
                }
            };
            TableList.datatable(oSetting);
        },
        _del: function (ids) {
            TableList.optTable({
                "tableId": "demotable",
                "url": tableUrl + "?ids=" + ids
            });
        },
        _edit: function (ids) {
            CommonUtil.requestService(tableUrl, "", false, "get",
                function (response) {
                    //从后台成功获取数据 拼写到前台页面
                    //弹出层模式
                    layer.open({
                        type: 1,
                        title: "基本信息",
                        skin: '', // 加上边框
                        area: ["800px", "400px"], // 宽高
                        offset: ['50px'],
                        content: $("#detail"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            //url 为操作URL 此处模拟
                            TableList.optTable({
                                "tableId": "demotable",
                                "url": tableUrl,
                                "callbackfn": function () {
                                    layer.close(index);
                                }
                            });
                        }
                    });
                });
        }
    };
}();
