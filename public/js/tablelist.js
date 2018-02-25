TableList = function (me) {
    return me = {
        datatable: function (params) {
            if (typeof ($.fn.DataTable) === 'undefined') {
                return;
            }
            var tableId, chk, url, aoColumns, toolbar, opt, fnRowCallback, order, iDisplayLength;
            if (params.tableId && params.url && params.aoColumns && params.aoColumns.length) {
                tableId = params.tableId;
                chk = params.chk;
                url = params.url;
                aoColumns = params.aoColumns;
                toolbar = params.toolbar;
                opt = params.opt;
                order = params.order;
                iDisplayLength = params.iDisplayLength ? params.iDisplayLength : 15;
                fnRowCallback = params.fnRowCallback;
                fnServerParams = params.fnServerParams;
                var mobile = false;
                if (device.mobile()) {//针对移动端
                    mobile = true;
                }
                var table = $('#' + tableId);
                table.fnServerParams = fnServerParams;
                table.fnRowCallback = fnRowCallback;
                table.attr("cfname", tableId);
                //add by fengjin1 2017-3-10 给table增加变化事件 实现列头随table自动变化 resize依赖jquery.ba-resize.js
                table.on("resize", function () {
                });
                /*$("body").on("resize", "table", function () {});*/
                var aoColumnDefs = [], primary = aoColumns[0]["data"];//若check列显示 第一列必须为唯一id
                if (chk && chk.display) {// check列
                    aoColumnDefs.push({ // 自定义列
                        "targets": [0], // 目标列位置，下标从0开始
                        "data": primary, // 数据列名
                        "sWidth": "40px",
                        "render": function (data, type, full) { // 返回自定义内容
                            return "<input type='checkbox' name='table_records' class='flat' ngr='" + data + "'>";
                        }
                    });
                    aoColumns[0]["sTitle"] = "<input type='checkbox' id='check-all' name='table_records' class='flat'>";
                    me._bindTableCheck(tableId);
                }
                if (opt) {
                    var width = 40 * opt.length;
                    aoColumnDefs.push({ // 自定义列 模拟操作列
                        "targets": [aoColumns.length], // 目标列位置，下标从0开始
                        "data": primary, // 数据列名
                        "sTitle": "操作",
                        "sWidth": width + "px",
                        "render": function (data, type, full, meta) { // 返回自定义内容
                            editid = "recordid" + data;
                            var optInfo = $("<a ngr='" + data + "'></a>");
                            //多操作可循环拼写
                            for (var op in opt) {
                                var optHtml = $("<font opt='" + op + "' class='cursor text-success' event=1>" +
                                    "<i class='fa fa-" + op + "' style='font-size: 13px;'>" + opt[op].info + "</i></font>");
                                var draw = opt[op].draw;
                                if (draw) {
                                    drawParams = draw(full);
                                    if (drawParams.disabled) {
                                        optHtml = optHtml.removeClass("cursor")
                                            .removeClass("text-success")
                                            .addClass("list-disabled").removeAttr("event");
                                    } else if (drawParams.hidden) {
                                        optHtml = "";
                                    }
                                }
                                optInfo = optInfo.append(optHtml);
                            }
                            return optInfo.prop("outerHTML");
                        }
                    });
                    me._optRecord(tableId, opt);
                }
                for (var $col in aoColumns) {
                    var columnType = aoColumns[$col]["columnType"];
                    var firstBind = true;
                    switch (columnType) {
                        case "custom":
                            var index = parseInt($col);
                            var colDataKey = aoColumns[$col]["data"];
                            aoColumnDefs.push({ // 自定义列
                                "targets": [index], // 目标列位置，下标从0开始
                                "data": colDataKey, // 数据列名
                                "sWidth": aoColumns[$col]["sWidth"],
                                "render": function (data, type, full, meta) { // 返回自定义内容
                                    var colIndex = meta.col;
                                    var cColDataKey = aoColumns[colIndex]["data"];
                                    var draw = aoColumns[colIndex]["draw"];
                                    var customFunc = aoColumns[colIndex]["func"];
                                    var recordId = full['' + primary + ''];
                                    if (draw) {
                                        var customObj = draw(full);
                                        if (customObj) {
                                            return customObj.html();
                                        }
                                        return;
                                    }
                                    if (cColDataKey == "status") {
                                        var className = "";
                                        switch (data) {
                                            case "启用":
                                            case "通过":
                                            case "在投放":
                                            case "正常":
                                            case "已报名":
                                            case "已签到":
                                            case "已付款":
                                            case "已发货":
                                                className = "status rsuccess";
                                                break;
                                            case "未通过":
                                            case "未支付":
                                                className = "status rfail";
                                                break;
                                            case "异常":
                                            case "已删除":
                                            case "离职":
                                            case "已停用":
                                            case "待发货":
                                                className = "status rerror";
                                                break;
                                            case "待审核":
                                            case "待投放":
                                            case "暂停":
                                            case "已取消":
                                            case "退款中":
                                            case "已退款":
                                                className = "status";
                                                break;
                                            default:
                                                break;
                                        }
                                        if (customFunc) {
                                            className += " cursor";
                                        }
                                        var status = "<div event='1' type='status' recordId='" + recordId + "' class='" + className + "'><p>" + data + "</p></div>";
                                        $("#" + tableId).off("click", "div[type='status']");
                                        $("#" + tableId).on("click", "div[type='status']", function () {
                                            if ($(this).attr("event") && customFunc) {
                                                recordId = $(this).attr("recordId");
                                                customFunc(recordId);
                                            }
                                        });
                                        return status;
                                    }
                                    var customDiv = cColDataKey + "_" + recordId;
                                    var parentContent = "<div custom='" + customDiv + "' class='display'></div>";
                                    var sTitle = aoColumns[colIndex]["sTitle"];
                                    if (firstBind) {
                                        $("#" + tableId).on("click", "a[costomType='custom']", function () {
                                            if ($(this).attr("event")) {
                                                recordId = $(this).attr("recordId");
                                                targetContent = $(this).attr("targetContent");
                                                var openContent = $("div[custom='" + targetContent + "']");
                                                if (customFunc) {
                                                    customFunc(recordId, openContent, $(this));
                                                } else {
                                                    var width = $(openContent).width();
                                                    layer.open({
                                                        type: 1,
                                                        title: sTitle,
                                                        area: [width + "px"], // 宽高
                                                        shadeClose: true,
                                                        scrollbar: false,
                                                        content: openContent
                                                    });
                                                }
                                            }
                                        });
                                        firstBind = false;
                                    }
                                    colData = "<a event='1' costomType='custom' recordId='" + recordId + "' targetContent='" + customDiv + "' class='list-col-btn'>" + data + "</a>";
                                    return colData + parentContent;
                                }
                            });
                            break;
                        case "img":
                            var index = parseInt($col);
                            aoColumnDefs.push({ // 自定义列
                                "targets": [index], // 目标列位置，下标从0开始
                                "data": aoColumns[$col]["data"], // 数据列名
                                "sWidth": aoColumns[$col]["sWidth"],
                                "render": function (data, type, full) { // 返回自定义内容
                                    return "<img width='50px' height='50px' alt='缩略图' src='" + data + "'>";
                                }
                            });
                            break;
                        case "photos":
                            var index = parseInt($col);
                            var idx = 0, photoIdx = 0;
                            var imgWidth = imgHeight = 120;
                            if (mobile) {//针对移动端
                                imgWidth = imgHeight = 60;
                            }
                            var photoTitle = aoColumns[$col]["info"];
                            aoColumnDefs.push({ // 自定义列
                                "targets": [index], // 目标列位置，下标从0开始
                                "data": aoColumns[$col]["data"], // 数据列名
                                "sWidth": aoColumns[$col]["sWidth"],
                                "render": function (data, type, full) { // 返回自定义内容
                                    var fileNames, photos = "<div class='panel-body display' id='photo" + photoIdx + "'>";
                                    var count = 0, len = data.length, imgClass = "col-md-4 col-sm-4 col-xs-4";
                                    if (len == 4) {
                                        imgClass = "col-md-6 col-sm-6 col-xs-6";
                                    }
                                    $.each(data, function () {
                                        photos += "<div name='photo_list' class='photo_list " + imgClass + "'><img img='photo' style='cursor: pointer;width:" + imgWidth + "px;height: " + imgHeight + "px;' layer-pid='"
                                            + idx + "' src='" + this.fileurl + "' layer-img='" + this.fileurl + "' alt='" + this.filename + "'></div>";
                                        idx++;
                                        count++;
                                    });
                                    var recordId = full['' + primary + ''];
                                    fileNames = "<a event='1' imgWidth='" + imgWidth + "' len='" + len + "' status='" + full.status + "' recordId='" + recordId + "' photoIdx='" + photoIdx + "' class='list-col-btn' photo='true'>"
                                        + photoTitle + "(" + count + ")</a>";
                                    photoIdx++;
                                    return fileNames + photos + "</div>";
                                }
                            });
                            var photoFunc = aoColumns[$col]["func"];
                            var successOpen = function (layero, aObj) {
                                var winHeight = $(window).height() + 20;
                                var layerHeight = layero.height();
                                if (winHeight < layerHeight) {
                                    var diff = layerHeight - winHeight;
                                    var photoList = layero.find("div[name='photo_list']");
                                    var photoListLen = photoList.length;
                                    if (photoListLen > 6) {
                                        var reduce = diff / photoListLen * 3, aImgWidth = 0;
                                        photoList.find("img").each(function () {
                                            var liHeight = $(this).height(), liWidth = $(this).width();
                                            $(this).height(liHeight - reduce);
                                            aImgWidth = liWidth - reduce;
                                            $(this).width(aImgWidth);
                                        });
                                        var imgContent = layero.find(".layui-layer-content");
                                        imgContent.height(imgContent.height() - diff);
                                        if (aObj) {
                                            aObj.attr("imgWidth", aImgWidth);
                                        }
                                        var width = 4 * aImgWidth;
                                        layero.width(width);
                                    }
                                }
                            };
                            var defaultFunc = function (id, obj, photoTitle, width, photoId, photoEvent, successOpen) {
                                layer.open({
                                    type: 1,
                                    title: photoTitle,
                                    shadeClose: true,
                                    scrollbar: false,
                                    area: [width + "px"], // 宽高
                                    content: $(photoId),
                                    success: function (layero) {
                                        successOpen(layero, obj);
                                    },
                                    end: function () {
                                        layer.closeAll('loading');
                                    }
                                });
                                photoEvent(obj, photoId);
                            };
                            var photoEvent = function (obj, photoId) {
                                imgdefereds = [], imgCompeles = 0;
                                $(photoId).find("img").each(function () {
                                    var dfd = $.Deferred();
                                    $(this).bind('load', function () {
                                        dfd.resolve();
                                    }).bind('error', function () {
                                        //图片加载错误
                                        dfd.resolve();
                                    });
                                    if (this.complete) {
                                        imgCompeles++;
                                        dfd.resolve();
                                    }
                                    imgdefereds.push(dfd);
                                });
                                var len = obj.attr("len");
                                var index = -1;
                                if (len != imgCompeles) {
                                    index = layer.load(0, {shade: false});
                                }
                                $.when.apply(null, imgdefereds).done(function () {
                                    if (index > 0) {
                                        layer.close(index);
                                    }
                                    var fileIdx = $(this).attr("idx");
                                    $(photoId).off("click", "img");
                                    layer.photos({
                                        photos: photoId,
                                        anim: 5,
                                        shade: 0.7,
                                        shadeClose: true
                                    });
                                    $("img[layer-pid='" + fileIdx + "']").click();
                                });
                            };
                            $("#" + tableId).on("click", "a[photo='true']", function () {
                                var aObj = $(this);
                                if (aObj.attr("event")) {
                                    var phoIdx = aObj.attr("photoIdx");
                                    var photoId = '#photo' + phoIdx;
                                    var aImgWidth = aObj.attr("imgWidth");
                                    var width = 4 * aImgWidth;
                                    var len = $(this).attr("len");
                                    if (len == 4 && !device.mobile()) {
                                        width = 2.5 * aImgWidth;
                                    }
                                    var recordId = $(this).attr("recordId");
                                    if (photoFunc) {
                                        photoFunc(recordId, aObj, photoTitle, width, photoId, photoEvent, successOpen);
                                    } else {//默认
                                        defaultFunc(recordId, aObj, photoTitle, width, photoId, photoEvent, successOpen);
                                    }
                                }
                            });
                            break;
                    }
                }
                var fixedHeader = {
                    "header": true,
                    "headerfloat": function (tableId) {
                        me._bindTableCheck(tableId);
                    },
                    "tableId": tableId
                }; //冻结表头
                var oPaginate = ["上一页", "下一页"];
                if (mobile) {//移动端 取消列表固定表头
                    fixedHeader = {
                        "header": false,
                        "headerfloat": function (tableId) {
                        },
                        "tableId": tableId
                    };
                    oPaginate = ["<<", ">>"];
                    $.extend($.fn.DataTable.ext.pager, {
                        numbers_length: 5
                    });
                }
                table.dataTable({
                    "oLanguage": {
                        /*"sUrl": CommonUtil.getRootPath() + "/js/build/font/datatable-zh-cn.txt"*/
                        "sSearch": "搜索:",
                        "sLengthMenu": "&nbsp;&nbsp;每页 _MENU_ 条记录",
                        "sZeroRecords": "没有数据",
                        "sInfo": "显示第 _START_ 条到第 _END_ 条记录,一共 _TOTAL_ 条记录",
                        "sInfoEmpty": "显示0条记录",
                        "oPaginate": {
                            "sPrevious": " " + oPaginate[0] + " ",
                            "sNext": " " + oPaginate[1] + " "
                        }
                    },
                    "order": order,
                    "bAutoWidth": false,// 自动计算列宽
                    "bProcessing": false,
                    "bServerSide": true, // 指定从服务器端获取数据
                    "sAjaxSource": url, // 获取数据的url
                    "fnServerData": me._fnServerData,
                    "fnServerParams": function (aoData) { //向服务端传递数据
                        if (table.fnSettings().aoSearch) {//查询
                            table.fnServerParams = table.fnSettings().aoSearch;
                        } else if (table.fnServerParams) {
                            table.fnSettings().aoSearch = table.fnServerParams;
                        }
                    },
                    'iDisplayLength': iDisplayLength,// 每页显示条数
                    "aLengthMenu": [iDisplayLength, iDisplayLength * 2, iDisplayLength * 3], //更改显示记录数选项
                    'bPaginate': true, // 是否分页
                    "bLengthChange": true,// 显示分页选项下拉框
                    "bFilter": false, // 过滤
                    "bSort": false,// 排序
                    "aoColumns": aoColumns,
                    "aoColumnDefs": aoColumnDefs,
                    "bDestroy": true,
                    "fnInitComplete": function (e) {// 第一次初始化完毕
                    },
                    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    },
                    fixedHeader: fixedHeader
                });
                if (toolbar) {
                    $(".table_toolbar").css("cssText", "overflow:hidden !important;");
                    $(".table_toolbar").children("div:first").append("<div class='btn-toolbar'></div>");
                    $.each(toolbar, function (key, obj) {
                        if (key == "custom") {
                            obj.func($(".btn-toolbar"));
                        }
                        else {
                            var tableParams = {"table": table, "primary": primary};
                            var barId = tableId + "_" + key;
                            $(".btn-toolbar").append("<button event='1' class='btn btn-default' id='" + barId + "'>" + obj.info + "</button>");
                            $("#" + barId).off("click");
                            switch (key) {
                                case "add":
                                    if (obj.func) {
                                        $("#" + barId).on("click", function () {
                                            if ($(this).attr("event")) {
                                                obj.func($(this));
                                            }
                                        });
                                    }
                                    break;
                                case "del":
                                    if (obj.func) {
                                        $("#" + barId).on("click", function () {
                                            if ($(this).attr("event")) {
                                                me._delRecord(tableId, obj.func, $(this));
                                            }
                                        });
                                    }
                                    break;
                                case "refresh":
                                    break;
                                default:
                                    if (obj.func) {
                                        $("#" + barId).on("click", tableParams, function (event) {
                                            if ($(this).attr("event")) {
                                                var ids = me._getTableRecordIds(tableId);
                                                var table = event.data.table;
                                                var primary = event.data.primary;
                                                var tableRow = table.fnGetData();
                                                var idArr = ids.split(",");
                                                var full = {};
                                                for (var idx in tableRow) {
                                                    if ((index = $.inArray(String(tableRow[idx][primary]), idArr)) >= 0) {
                                                        full[idArr[index]] = tableRow[idx];
                                                    }
                                                }
                                                obj.func(ids, full, $(this));
                                            }
                                        });
                                    }
                                    break;
                            }
                        }
                    });
                    // 默认显示刷新--注释 modify by fengjin1 2017-3-14
                    /*if (toolbar.refresh == undefined || toolbar.refresh) {
                     var refreshId = tableId + "_refresh";
                     $(".btn-toolbar").append("<button class='btn btn-default' id='" + refreshId + "'> 刷新</button>");
                     $("#" + refreshId).off("click");
                     $("#" + refreshId).on("click", function () {
                     me.refresh(tableId);
                     });
                     }*/
                }
                table.on('draw.dt', function () {
                    $(".bulk_action input[name='table_records']").iCheck({
                        checkboxClass: 'icheckbox_flat-green'
                    });
                    $(".bulk_action").off("ifChecked ifUnchecked", "input[type='checkbox']");
                    $(".bulk_action input[type='checkbox']").iCheck('uncheck');
                    $("#" + $(this).attr("id")).find("img").each(function () {
                        $(this).bind('load', function () {
                        }).bind('error', function () {
                            $(this).attr("load_error", 1);//图片加载错误
                        });
                    });
                });
            } else {
                console.log("传递的参数不符合要求");
            }
        },
        optTable: function (params) {
            var tableId = params.tableId, url = params.url, type = params.type,
                async = params.async, reqData = params.reqData,
                refresh = (params.refresh == undefined || params.refresh) ? true : false,
                successfn = params.successfn, failfn = params.failfn;
            //发送请求 实现编辑或删除等功能
            CommonUtil.requestService(url, reqData, async, type,
                function (ret) {
                    //if (response.success)
                    {// 操作成功
                        if (successfn)
                            successfn();//调用外部自定义方法
                        if (refresh) {
                            me.refresh(tableId);
                            var checklist = $("#" + tableId + " input[type='checkbox']");
                            checklist.iCheck("uncheck");
                        }
                    }
                }, failfn);
        },
        // 刷新当前页
        refresh: function (tableId) {
            $("#" + tableId).dataTable().fnDraw(false);
        }
        ,
        search: function (tableId, url, requestData) {
            var dataTable = $('#' + tableId).dataTable();
            dataTable.fnSettings().sAjaxSource = url;
            dataTable.fnSettings().aoSearch = requestData;
            dataTable.fnDraw();
        },
        _addAoData: function (requestData, aoData) {
            if (requestData) {
                for (var key in requestData) {
                    var val = requestData[key];
                    if (typeof (val) == "object")
                        val = JSON.stringify(val);
                    if (val != "{}") {
                        aoData.push({
                            "name": key,
                            "value": val
                        });
                    }
                }
            }
        },
        _fnServerData: function (sSource, aoData, fnCallback, oSettings) { // 从后台获取数据后处理数据绑定列表
            sSource = oSettings.sAjaxSource;
            var requestData = oSettings.aoSearch;
            me._addAoData(requestData, aoData);
            CommonUtil.requestService(sSource, aoData, true, "get", function (ret) {
                fnCallback(ret.data);
            });
        },
        _delRecord: function (tableId, delFunc, obj) {
            var ids = me._getTableRecordIds(tableId);
            if (ids) {
                layer.confirm('您确定要删除所选？', {
                    offset: "50px",
                    btn: ['确定', '取消']
                }, function () {
                    delFunc(ids, function (tableId) {
                        me.refresh(tableId);
                    });
                });
            }
        },
        _optRecord: function (tableId, opts) {
            for (var op in opts) {
                $("#" + tableId).off("click", "font[opt='" + op + "']");
                $("#" + tableId).on(
                    "click",
                    "font[opt='" + op + "']",
                    op,
                    function (event) {
                        if ($(this).attr("event")) {
                            var recordId = $(this).parent().attr("ngr");
                            opts[event.data].func(recordId, null, $(this));
                        }
                    });
            }
        },
        _bindTableCheck: function (tableId) {
            $("table[cfname='" + tableId + "']").off("ifChecked ifUnchecked", "#check-all");
            $("table[cfname='" + tableId + "']").on(
                "ifChecked ifUnchecked",
                "#check-all",
                function (event) {
                    var checklist = $("#" + tableId + " input[type='checkbox']");
                    switch (event.type) {
                        case "ifUnchecked":
                            checklist.iCheck('uncheck');
                            break;
                        case "ifChecked":
                            checklist.iCheck('check');
                            break;
                    }
                });
        },
        _getTableRecordIds: function (tableId) {
            var ids = "";
            $("#" + tableId + " tr td").has(".checked").find("input").each(function () {
                ids += $(this).attr('ngr') + ",";
            });
            return ids;
        },
        controllerDisabled: function (obj) {
            if (obj) {
                obj.removeAttr("event");
            }
        },
        controllerRemoveDisabled: function (obj) {
            if (obj) {
                obj.attr("event", 1);
            }
        }
    };
}();
