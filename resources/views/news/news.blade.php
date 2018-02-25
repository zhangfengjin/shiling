@extends('layouts.app_table')@section('tablecontent')
    <script type="application/javascript">
        var route = CommonUtil.getRootPath() + "/meet/upload?action=uploadimage&token=" + $('meta[name="csrf-token"]').attr('content');
        var accept = {};
        var fileNumLimit = 1;
        var fileSizeLimit = 1024 * 1024;    // 3 M
        var fileSingleSizeLimit = 1024 * 1024;  // 1 M
        var uploadUids = [];
    </script>
    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/webuploader.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/style.css')}}"/>

    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">标题</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input id="news_title" type="text" class="form-control" placeholder="标题"
                           required data-parsley-maxlength="200">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">URL</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input id="news_url" type="text" class="form-control" placeholder="URL">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">内容</label>
                <div class="col-md-11 col-sm-11 col-xs-12">
                    <textarea id="news_content" rows="3" class="form-control" placeholder="内容"></textarea>
                </div>
            </div>
            <div id="uploaded_img" class="form-group display">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">已上传图片</label>
                <div class="col-md-11 col-sm-11 col-xs-12">
                    <ul id="uploaded_img_list">
                    </ul>
                </div>
            </div>
            <div id="uploaded_btn" class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">新闻图片</label>
                <div class="col-md-11 col-sm-11 col-xs-12">
                    <div class="row">
                        <div id="wrapper">
                            <div id="container">
                                <div id="uploader">
                                    <div class="queueList">
                                        <div id="dndArea" class="placeholder">
                                            <div id="filePicker"></div>
                                        </div>
                                    </div>
                                    <div class="statusBar" style="display:none;">
                                        <div class="progress">
                                            <span class="text">0%</span>
                                            <span class="percentage"></span>
                                        </div>
                                        <div class="info"></div>
                                        <div class="btns">
                                            <div id="filePicker2"></div>
                                            <div class="uploadBtn">开始上传</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                {{--<div class="panel panel-default collapse-link">
                    <div class="panel-heading collapse-title">
                        查询条件
                    </div>
                    <div class="panel-body fdeatil detail_content">
                        <form class="form-horizontal form-label-left">
                            <div class="form-group">
                                <ul class="col-md-12 col-sm-12 col-xs-12">
                                    <li class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">订单号</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_order_code"></li>
                                    <li class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-2 col-sm-4 col-xs-12">下单时间</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12 input-prepend input-group">
                                            <span class="add-on input-group-addon">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                            <input type="text" name="search_place_time"
                                                   id="search_place_time"
                                                   class="pull-right form-control"/>
                                        </div>
                                    </li>
                                    <li class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">订单状态</label>
                                        <select class="col-md-7 col-sm-8 col-xs-12" id="search_status">
                                            <option value=""></option>
                                            <option value="0">未支付</option>
                                            <option value="1">待发货</option>
                                            <option value="2">已发货</option>
                                            --}}{{--<option value="3">已签收</option>--}}{{--
                                            <option value="4">已取消</option>
                                        </select></li>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <button id="search" type="button" class="btn btn-round btn-default search_btn">
                                            查询
                                        </button>
                                        <button id="reset" type="button" class="btn btn-round btn-default search_btn">重置
                                        </button>
                                    </div>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>--}}

                <div class="x_content">
                    <table id="meet_table" class="table table-striped table-bordered bulk_action">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- bootstrap-daterangepicker -->
    <link href="{{url('css/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <script src="{{url('js/plugins/moment/moment-with-locales.min.js')}}"></script> {{--日期处理 带有国际化语言的--}}
    <script src="{{url('js/plugins/daterangepicker/daterangepicker.js')}}"></script> {{--日期区间--}}

    <script type="text/javascript" src="{{url('/js/plugins/webuploader/webuploader.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/plugins/webuploader/upload.js')}}"></script>

    <div id="detail_mould"></div>
    <script type="application/javascript">
        var meetUrl = "/news";
        $(function () {
            AccountUtil.init();
        });
        AccountUtil = function (me) {
            var listUrl = meetUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "meet_table";
            var searchInfo;
            var provinces = [];  //定义数组
            var initMeetId = "";
            return me = {
                _initOwn: function () {
                    $("#search").on("click", function () {
                        me._searchList();
                    });
                    $("#reset").on("click", function () {
                        $("#search_order_code").val('');
                        $("#search_place_time").val('');
                        $("#search_place_time").attr("startTime", '');
                        $("#search_place_time").attr("endTime", '');
                        $("#search_status").val('');
                    });
                    //me._daterangepicker();
                },
                init: function () {
                    me._initOwn();
                    me._createList();
                },
                _daterangepicker: function () {
                    moment.locale("zh-cn");
                    var placeTimeObj = $('#search_place_time');
                    placeTimeObj.daterangepicker({
                        timePicker: false,
                        timePickerIncrement: 30,
                        timePicker24Hour: true,
                        locale: {
                            format: 'YYYY/MM/DD h:mm A'
                        }
                    }, function (start, end, label) {
                        placeTimeObj.attr("startTime", start.format('YYYY-MM-DD HH:mm:ss'));
                        placeTimeObj.attr("endTime", end.format('YYYY-MM-DD HH:mm:ss'));
                    });
                    placeTimeObj.val("");
                    placeTimeObj.on("hide.daterangepicker", function (event, picker) {
                        placeTimeObj.attr("startTime", picker.startDate.format('YYYY-MM-DD HH:mm'));
                        placeTimeObj.attr("endTime", picker.endDate.format('YYYY-MM-DD HH:mm'));
                    });
                    placeTimeObj.on("cancel.daterangepicker", function (event, picker) {
                        picker.element.val('');
                        placeTimeObj.attr("startTime", 0);
                        placeTimeObj.attr("endTime", 0);
                    });
                },
                _ddl: function () {
                    $("#search_province option").each(function () {  //遍历所有option
                        var province = $(this).val();
                        if (province != "") {
                            var cities = [];
                            $("#search_city option[parent='" + province + "']").each(function () {  //遍历所有option
                                var city = $(this).val();
                                if (city != "") {
                                    var cityName = $(this).text();
                                    var areas = [];
                                    $("#search_area option[parent='" + city + "']").each(function () {  //遍历所有option
                                        var area = $(this).val();
                                        if (area != "") {
                                            var areaName = $(this).text();
                                            areas.push({
                                                "name": area,
                                                "value": areaName
                                            });
                                        }
                                    });
                                    city2 = [];
                                    city2.name = city;
                                    city2.value = {
                                        "name": cityName,
                                        "value": areas
                                    };
                                    cities.push(city2);
                                }
                            });
                            provinces.push({
                                "name": province,
                                "value": cities
                            });
                        }
                    });

                    $("#search_province").change(function () {
                        var province = $(this).val();
                        for (var idx in provinces) {
                            var pro = provinces[idx];
                            if (pro.name == province) {
                                $("#search_city").empty();
                                $("#search_area").empty();
                                var cities = pro.value;
                                for (var idx2 in cities) {
                                    var cityValue = cities[idx2].name;
                                    $("#search_city").append("<option parent='" + province + "' value='" + cityValue + "'>" + cities[idx2].value.name + "</option>");
                                    var areas = cities[idx2].value.value;
                                    for (var idx3 in areas) {
                                        $("#search_area").append("<option parent='" + cityValue + "' value='" + areas[idx3].name + "'>" + areas[idx3].value + "</option>");
                                    }
                                }
                            }
                        }
                    });
                    $("#search_city").change(function () {
                        $("#area").empty();
                        var cityValue = $(this).val();
                        var province = $("#search_city option[value='" + cityValue + "']").attr('parent');
                        for (var idx in provinces) {
                            var pro = provinces[idx];
                            if (pro.name == province) {
                                var cities = pro.value;
                                for (var idx2 in cities) {
                                    if (cityValue == cities[idx2].name) {
                                        var areas = cities[idx2].value.value;
                                        for (var idx3 in areas) {
                                            $("#search_area").append("<option parent='" + cityValue + "' value='" + areas[idx3].name + "'>" + areas[idx3].value + "</option>");
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                _createList: function () {
                    var request = CommonUtil.getRequest();
                    initMeetId = request["meetId"];
                    var initSearchs = {};
                    if (initMeetId != undefined && initMeetId != "undefined" && initMeetId != "") {
                        initSearchs = {
                            "meetId": initMeetId
                        };
                    }
                    var aoColumns = [{
                        "sTitle": "",
                        "data": "id"
                    }, {
                        "sTitle": "标题",
                        "data": "title"
                    }, {
                        "sTitle": "内容",
                        "data": "content"
                    }, {
                        "sTitle": "url",
                        "data": "url"
                    }, {
                        "sTitle": "创建人",
                        "data": "user_name"
                    }];
                    var oSetting = {
                        "tableId": tableId,
                        "url": listUrl,
                        "chk": {
                            "display": true
                        },
                        "aoColumns": aoColumns,
                        "order": [[0, "desc"]],
                        "fnServerParams": {
                            "searchs": initSearchs
                        },
                        "toolbar": {
                            "add": {
                                "info": "新建", "func": me._add
                            },
                            "del": {
                                "info": "删除", "func": me._del
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
                _searchList: function () {
                    searchInfo = {
                        "searchs": {
                            "order_code": $("#search_order_code").val(),
                            "start_time": $("#search_place_time").attr("startTime"),
                            "end_time": $("#search_place_time").attr("endTime"),
                            "status": $("#search_status").val()
                        }
                    };
                    if (initMeetId != undefined && initMeetId != "undefined" && initMeetId != "") {
                        searchInfo.searchs.meetId = initMeetId;
                    }
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _add: function () {
                    me._openlayer(0, 1, function (requestData, successfn, usable) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": meetUrl,
                            "type": "post",
                            "reqData": requestData,
                            "successfn": successfn,
                            "failfn": usable
                        });
                    });
                },
                _del: function (ids, fn) {
                    if (ids) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": meetUrl + "/" + ids,
                            "type": "DELETE",
                            "async": true,
                            "successfn": function () {
                                parent.layer.msg('删除成功', {
                                    icon: 1,
                                    time: 800,
                                    offset: "50px"
                                });
                            },
                            "failfn": function () {
                                parent.layer.msg('删除失败', {
                                    icon: 1,
                                    time: 800,
                                    offset: "50px"
                                });
                            }
                        });
                    }
                },
                _edit: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        var fillData = function (data) {
                            var atts = data.atts;
                            if (atts.length > 0) {
                                $("#uploaded_img").removeClass("display");
                                $.each(atts, function () {
                                    $("#uploaded_img_list").append("<li style='float: left;' id='li_" + this.id + "'><div><span id='del_img_" + this.id + "' attId=" + this.id + " class='cursor'>删除</span></div><img src='" + this.url + "' style='width:100px;height:100px;' /></li>");
                                    uploadUids.push(this.id);
                                });
                                $("span[id^='del_img_']").on('click', function () {
                                    var attId = parseInt($(this).attr("attId"));
                                    var idx = $.inArray(attId, uploadUids);
                                    if (idx >= 0) {
                                        uploadUids.splice(idx, 1);
                                    }
                                    $("#li_" + attId).remove();
                                });
                            }
                            $("#news_title").val(data.title);
                            $("#news_url").val(data.url);
                            $("#news_content").val(data.content);
                        };
                        var updateData = function (requestData, successfn, usable) {
                            TableList.optTable({
                                "tableId": tableId,
                                "url": meetUrl + "/" + ids,
                                "type": "put",
                                "reqData": requestData,
                                "successfn": successfn,
                                "failfn": usable
                            });
                        };
                        CommonUtil.requestService(meetUrl + "/" + ids, "", true, "get",
                            function (response) {
                                //从后台成功获取数据 拼写到前台页面
                                //弹出层模式
                                if (response.code == 0) {
                                    me._openlayer(ids, 2, updateData);//打开表单；保存时回调updateData 将数据传输到后台
                                    fillData(response.data);//根据从后台获取的数据填充到表单上
                                }
                                TableList.controllerRemoveDisabled(obj);
                            }, function () {
                                TableList.controllerRemoveDisabled(obj);
                            });
                    }
                },
                _notify: function (ids, full, obj) {
                    var notifyRequest = function (requestData, successfn, usable) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": meetUrl + "/notify/" + ids,
                            "type": "PUT",
                            "async": true,
                            "reqData": requestData,
                            "successfn": function () {
                                parent.layer.msg('通知成功', {
                                    icon: 1,
                                    time: 800,
                                    offset: "50px"
                                });
                                successfn();
                            },
                            "failfn": function () {
                                parent.layer.msg('通知失败', {
                                    icon: 1,
                                    time: 800,
                                    offset: "50px"
                                });
                                usable();
                            }
                        });
                    };
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    $("#notify_content").val('');
                    layer.open({
                        type: 1,
                        title: "通知参会人员",
                        scrollbar: false,
                        area: ["300px", "220px"], // 宽高
                        content: $("#detail_notify"),
                        btn: ['通知', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var parsl = $('#detail_notify').parsley();
                                parsl.validate();
                                if (true === parsl.isValid()) {
                                    var requestData = {
                                        "send_sms": $("#send_sms")[0].checked ? 1 : 0,
                                        "send_email": $("#send_email")[0].checked ? 1 : 0,
                                        "notify_content": $("#notify_content").val()
                                    };
                                    notifyRequest(requestData, function () {
                                        layer.close(index);
                                    }, usable);
                                } else {
                                    usable();
                                }
                            } catch (e) {
                                usable();
                            }
                        }, success: function () {
                        }
                    });
                },
                _cancel: function (ids, fn) {
                    if (ids) {
                        var cancelRequest = function (requestData, successfn, usable) {
                            requestData.reason = $("#reason").val();
                            TableList.optTable({
                                "tableId": tableId,
                                "url": meetUrl + "/cancel/" + ids,
                                "type": "DELETE",
                                "async": true,
                                "successfn": function () {
                                    parent.layer.msg('取消成功', {
                                        icon: 1,
                                        time: 800,
                                        offset: "50px"
                                    });
                                },
                                "failfn": function () {
                                    parent.layer.msg('取消失败', {
                                        icon: 1,
                                        time: 800,
                                        offset: "50px"
                                    });
                                }
                            });
                        };
                        var usable = function () {
                            btns.css("pointer-events", "");
                        };
                        $("#reason").val('');
                        layer.open({
                            type: 1,
                            title: "会议取消原因",
                            scrollbar: false,
                            area: ["300px", "200px"], // 宽高
                            content: $("#detail_reason"),
                            btn: ['保存', '取消'],
                            yes: function (index, layero) {
                                btns = layero.children(".layui-layer-btn").children("a");
                                try {
                                    btns.css("pointer-events", "none");
                                    var parsl = $('#detail_reason').parsley();
                                    parsl.validate();
                                    if (true === parsl.isValid()) {
                                        cancelRequest({}, function () {
                                            layer.close(index);
                                        }, usable);
                                    } else {
                                        usable();
                                    }
                                } catch (e) {
                                    usable();
                                }
                            }, success: function () {
                            }
                        });

                    }
                },
                _refund: function (ids, full, obj) {
                    if (ids) {
                        var userTableId = "user_table";
                        var userUrl = "/meetuser/list";
                        var meetUserList = function (ids) {
                            var aoColumns = [{
                                "sTitle": "",
                                "data": "id"
                            }, {
                                "sTitle": "姓名",
                                "data": "name"
                            }, {
                                "sTitle": "手机号",
                                "data": "phone"
                            }, {
                                "sTitle": "邮箱",
                                "data": "email"
                            }];
                            var oSetting = {
                                "tableId": userTableId,
                                "url": userUrl,
                                "chk": {
                                    "display": true
                                },
                                "aoColumns": aoColumns,
                                "order": [[0, "desc"]],
                                "toolbar": {
                                    "refund": {
                                        "info": "退款", "func": function (ids, fn) {
                                            _refundMoney(ids);
                                        }
                                    }
                                }
                            };
                            TableList.datatable(oSetting);
                        };
                        var openMeetUser = function (ids) {
                            //var areaHeight = $(window).height() - 40;
                            layer.open({
                                type: 1,
                                title: "退款人员",
                                scrollbar: false,
                                shadeClose: true,
                                area: ["50%", "500px"], // 宽高
                                content: $("#detail_refund"),
                                yes: function (index, layero) {
                                },
                                success: function () {
                                    meetUserList(ids);
                                }
                            });
                        };
                        openMeetUser(ids);
                        var _refundMoney = function (ids) {
                            if (ids) {
                                TableList.optTable({
                                    "tableId": userTableId,
                                    "url": meetUrl + "/refund/" + ids,
                                    "type": "post",
                                    "async": true,
                                    "successfn": function () {
                                        parent.layer.msg('取消成功', {
                                            icon: 1,
                                            time: 800,
                                            offset: "50px"
                                        });
                                    },
                                    "failfn": function () {
                                        parent.layer.msg('取消失败', {
                                            icon: 1,
                                            time: 800,
                                            offset: "50px"
                                        });
                                    }
                                });
                            }
                        }
                    }
                },
                _resetHtml: function () {
                    $(".parsley-error").removeClass("parsley-error");
                    $("ul.parsley-errors-list").remove();
                    $("#detail").remove();
                    $("#detail_mould").append(lay);
                    var config = {
                        ".chosen-select": {},
                        ".chosen-select-deselect": {
                            allow_single_deselect: true
                        },
                        ".chosen-select-no-single": {
                            disable_search_threshold: 10
                        },
                        ".chosen-select-no-results": {
                            no_results_text: "Oops, nothing found!"
                        },
                        ".chosen-select-width": {
                            width: "95%"
                        }
                    };
                    for (var selector in config) {
                        $(selector).chosen(config[selector])
                    }
                    $(".chosen-container").css({
                        "width": $("#meet_name").width() + "%",
                        "height": 30
                    });
                    uploadUids = [];

                },
                _openlayer: function (id, type, yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["70%", "90%"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "订单信息",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var requestData = {
                                    "title": $("#news_title").val(),
                                    "url": $("#news_url").val(),
                                    "content": $("#news_content").val(),
                                    "atts": uploadUids
                                };
                                if (uploadUids.length > 1) {
                                    alert("新闻图片个数超过限制");
                                    usable();
                                } else {
                                    var parsl = $('#detail').parsley();
                                    parsl.validate();
                                    if (true === parsl.isValid()) {
                                        yes(requestData, function () {
                                            layer.close(index);
                                        }, usable);
                                    } else {
                                        usable();
                                    }
                                }
                            } catch (e) {
                                usable();
                            }
                        }, success: function () {
                            $.getScript("/js/plugins/webuploader/upload.js");
                        }
                    });
                }

            };
        }();

    </script>

@endsection
