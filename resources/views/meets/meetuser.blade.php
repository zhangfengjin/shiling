@extends('layouts.app_table')@section('tablecontent')
    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">会议名称</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="meet_name" type="text" class="form-control" disabled
                           required data-parsley-maxlength="50">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">参会人</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="user_name" type="text" class="form-control" disabled
                           required data-parsley-maxlength="100">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">状态</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control" id="status">
                        <option value="0">已报名</option>
                        <option value="1">已付款</option>
                        {{--<option value="2">退款中</option>
                        <option value="3">已退款</option>--}}
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div id="detail_batch" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <select class="form-control" id="pay_status">
                        <option value="0">已报名</option>
                        <option value="1">已付款</option>
                        {{--<option value="2">退款中</option>
                        <option value="3">已退款</option>--}}
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="panel panel-default collapse-link">
                    <div class="panel-heading collapse-title">
                        查询条件
                    </div>
                    <div class="panel-body fdeatil detail_content">
                        <form class="form-horizontal form-label-left">
                            <div class="form-group">
                                <ul class="col-md-12 col-sm-12 col-xs-12">
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">省</label>
                                        <div class="col-md-7 col-sm-8 col-xs-12">
                                            <select id="search_province" class="form-control" required>
                                                <option value=""></option>
                                                @foreach($provinces as $province)
                                                    <option value="{{$province->province_code}}">{{$province->province_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">市</label>
                                        <div class="col-md-7 col-sm-8 col-xs-12">
                                            <select id="search_city" class="form-control" required>
                                                <option value=""></option>
                                                @foreach($cities as $city)
                                                    <option parent="{{$city->province_code}}"
                                                            value="{{$city->city_code}}">{{$city->city_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">县区</label>
                                        <div class="col-md-7 col-sm-8 col-xs-12">
                                            <select id="search_area" class="form-control" required>
                                                <option value=""></option>
                                                @foreach($areas as $area)
                                                    <option parent="{{$area->city_code}}"
                                                            value="{{$area->id}}">{{$area->area_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">会议名称</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_meet_name"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">状态</label>
                                        <div class="col-md-7 col-sm-8 col-xs-12">
                                            <select class="form-control" id="search_status">
                                                <option value=""></option>
                                                <option value="0">已报名</option>
                                                <option value="1">已付款</option>
                                                <option value="2">退款中</option>
                                                <option value="3">已退款</option>
                                                <option value="4">已签到</option>
                                            </select>
                                        </div>

                                    </li>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
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
                </div>

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

    <div id="detail_mould"></div>
    <script type="application/javascript">
        var meetUrl = "/meetuser";
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
                        $("#search_province").val('');
                        $("#search_city").val('');
                        $("#search_area").val('');
                        $("#search_meet_name").val('');
                    });
                },
                init: function () {
                    me._initOwn();
                    me._ddl();
                    me._createList();
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
                        "sTitle": "会议",
                        "data": "meet_name"
                    }, {
                        "sTitle": "姓名",
                        "data": "name"
                    }, {
                        "sTitle": "手机号",
                        "data": "phone"
                    }, {
                        "sTitle": "邮箱",
                        "data": "email"
                    }, {
                        "sTitle": "状态",
                        "data": "status",
                        "columnType": "custom"
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
                            "batch_status": {
                                "info": "批量修改状态", "func": me._batchStatus
                            },
                            "export": {
                                "info": "导出EXCEL", "func": function (ids) {
                                    if (initMeetId != undefined &&
                                        initMeetId != "undefined" &&
                                        initMeetId != "" && !searchInfo) {
                                        searchInfo = {
                                            "searchs": {
                                                "meetId": initMeetId
                                            }
                                        }
                                    }
                                    location.href = CommonUtil.getRootPath() + meetUrl + "/export?type=excel&searchs=" + JSON.stringify(searchInfo ? searchInfo["searchs"] : "");
                                }
                            },
                            "export2": {
                                "info": "导出WORD", "func": function (ids) {
                                    if (initMeetId != undefined &&
                                        initMeetId != "undefined" &&
                                        initMeetId != "" && !searchInfo) {
                                        searchInfo = {
                                            "searchs": {
                                                "meetId": initMeetId
                                            }
                                        }
                                    }
                                    location.href = CommonUtil.getRootPath() + meetUrl + "/export?type=word&searchs=" + JSON.stringify(searchInfo ? searchInfo["searchs"] : "");
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
                _searchList: function () {
                    searchInfo = {
                        "searchs": {
                            "area_id": $("#search_area").val(),
                            "meet_name": $("#search_meet_name").val(),
                            "status": $("#search_status").val()
                        }
                    };
                    if (initMeetId != undefined && initMeetId != "undefined" && initMeetId != "") {
                        searchInfo.searchs.meetId = initMeetId;
                    }
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _batchStatus: function (ids, full, obj) {
                    var batchEditStatus = function (requestData, successfn, usable) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": meetUrl + "/" + ids,
                            "type": "put",
                            "reqData": requestData,
                            "successfn": successfn,
                            "failfn": usable
                        });
                    };
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["200px", "200px"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "支付状态信息",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail_batch"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var requestData = {
                                    "status": $("#pay_status").val()
                                };
                                var parsl = $('#detail_batch').parsley();
                                parsl.validate();
                                if (true === parsl.isValid()) {
                                    batchEditStatus(requestData, function () {
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
                _edit: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        var fillData = function (data) {
                            $("#meet_name").val(data.meet_name);
                            $("#user_name").val(data.user_name);
                            $("#status").val(data.status);
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
                _daterangepicker: function () {
                    moment.locale("zh-cn");
                    var beginTime = $('#begin_time');
                    var date = moment().format('YYYY-MM-DD HH:mm');
                    var timeOptions = {
                        "singleDatePicker": true,
                        "timePicker": true,
                        "timePicker24Hour": true,
                        "locale": {
                            "format": 'YYYY-MM-DD HH:mm'
                        },
                        startDate: date
                    };
                    if (beginTime.attr("begin_time") == undefined || beginTime.attr("begin_time") == "") {
                        beginTime.attr("begin_time", date);
                    } else {
                        timeOptions.startDate = beginTime.attr("begin_time");
                    }
                    beginTime.daterangepicker(timeOptions, function (start, end, label) {
                        beginTime.attr("begin_time", start.format('YYYY-MM-DD HH:mm'));
                    });
                    var endTime = $('#end_time');
                    if (endTime.attr("begin_time") == undefined || endTime.attr("begin_time") == "") {
                        endTime.attr("begin_time", date);
                    } else {
                        timeOptions.startDate = endTime.attr("begin_time");
                    }
                    endTime.daterangepicker(timeOptions, function (start, end, label) {
                        endTime.attr("time_time", start.format('YYYY-MM-DD HH:mm'));
                    });
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
                    me._daterangepicker();

                },
                _openlayer: function (id, type, yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["70%", "200px"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "参会人员信息",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var requestData = {
                                    "status": $("#status").val()
                                };
                                var parsl = $('#detail').parsley();
                                parsl.validate();
                                if (true === parsl.isValid()) {
                                    yes(requestData, function () {
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

            };
        }();

    </script>

@endsection
