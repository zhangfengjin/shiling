@extends('layouts.app_table')
@section('tablecontent')
    <div id="detail" class="x_content detail_content">
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">微博uid</label>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="user_id" type="text" class="form-control" placeholder="使用官方微博uid"
                           required data-parsley-required-message="微博uid不允许为空"
                           data-parsley-type="number">
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
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">DSP ID</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_dsp_id"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">广告主ID</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_client_id"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">投放账号ID</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_uid"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">DSP名称</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_dsp_name"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">广告主名称</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_client_name"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">审核人</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_audit_name" type="text">
                                    </li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">审核状态</label>
                                        <select class="col-md-7 col-sm-8 col-xs-12" id="search_status">
                                            <option value=""></option>

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
                </div>

                <div class="x_content">
                    <table id="put_in_account_table" class="table table-striped table-bordered bulk_action">
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
    <style>
        .uploadImg {
            margin-right: 5px;
            border-image: none;
            overflow: hidden;
            cursor: pointer;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            margin-left: 5px;
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }

        #uploadcover {
            position: absolute;
            margin-top: -45px;
            width: 54px;
            overflow: hidden;
        }
    </style>
    <script type="application/javascript">
        var userUrl = "/user";
        $(function () {
            PutInAccountUtil.init();
        });
        PutInAccountUtil = function (me) {
            var listUrl = userUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "put_in_account_table";
            var searchInfo;
            return me = {
                _initOwn: function () {
                    $("#search").on("click", function () {
                        me._searchList();
                    });
                    $("#reset").on("click", function () {
                        $("#search_dsp_id").val('');
                        $("#search_dsp_name").val('');
                        $("#search_client_id").val('');
                        $("#search_client_name").val('');
                        $("#search_uid").val('');
                        $("#search_audit_name").val('');
                        $("#search_status").val('');
                    });
                },
                init: function () {
                    me._initOwn();
                    me._createList();
                },
                _import: function (content) {
                    $(content).append("<div style='float:left;'>" +
                        "<form id='uploadimg_form' " +
                        "action='/user/import?action=uploadfile' " +
                        "enctype='multipart/form-data' method='POST' " +
                        "target='refresh_iframe'>" +
                        "<button id='uploadBtn' class='uploadImg' style='z-index: -1; cursor: pointer;' event='1' class='btn btn-default' >导入</button>" +
                        "<input style='FILTER: alpha(opacity=0); opacity: 0; -moz-opacity: 0; -khtml-opacity: 0;' " +
                        "id='uploadcover' name='file' class='uploadImg' type='file' accept='.xlsx,.xls'>" +
                        "<input name='_token' type='hidden' value=''>" +
                        "</form>" +
                        "<iframe id='refresh_iframe' name='refresh_iframe'style='display: none;'>上传成功" +
                        "</iframe>" +
                        "</div>");
                    $("input[name='_token']").val($('meta[name="csrf-token"]').attr('content'));
                    $("#uploadcover").change(function () {// 上传资料封面
                        $('#uploadimg_form').submit();
                        $("#uploadimg_form input").attr("disabled", "disabled");
                    });
                    $("#refresh_iframe").load(
                        function () {
                            var data = $(
                                window.frames['refresh_iframe'].document.body)
                                .html();
                            if (data != null && data != "") {
                                data = CommonUtil.parseToJson(data);
                                if (data.code == 0) {
                                    alert("导入成功");
                                    CommonUtil.redirect("/user");
                                } else if (data.code != 601) {
                                    var message = data.message;
                                    $.each(data.data, function () {
                                        message += "\r\n";
                                        message += this;
                                    });
                                    alert(message);
                                }
                                $("#uploadimg_form input").removeAttr("disabled");
                            }
                        });
                },
                _createList: function () {
                    var aoColumns = [{
                        "sTitle": "",
                        "data": "id"
                    }, {
                        "sTitle": "姓名",
                        "data": "name"
                    }];
                    var oSetting = {
                        "tableId": tableId,
                        "url": listUrl,
                        "chk": {
                            "display": true
                        },
                        "aoColumns": aoColumns,
                        "order": [[0, "desc"]],
                        "toolbar": {
                            "del": {
                                "info": "删除", "func": me._del
                            },
                            "pass": {
                                "info": "批量通过", "func": me._egis
                            },
                            "refuse": {
                                "info": "批量驳回", "func": me._refuse
                            },
                            "export": {
                                "info": "导出", "func": function (ids) {
                                    location.href = CommonUtil.getRootPath() + userUrl + "/export?searchs=" + JSON.stringify(searchInfo ? searchInfo["searchs"] : "");
                                }
                            },
                            "custom": {
                                "info": "导入", "func": function (content) {
                                    me._import(content);
                                }
                            }
                        },
                        "opt": {
                            "check-square-o": {
                                "display": 1,
                                "info": "通过",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status == "通过") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._egis
                            },
                            "remove": {
                                "display": 1,
                                "info": "驳回",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status == "未通过") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._refuse
                            },
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
                            "dsp_id": $("#search_dsp_id").val(),
                            "dsp_name": $("#search_dsp_name").val(),
                            "client_id": $("#search_client_id").val(),
                            "client_name": $("#search_client_name").val(),
                            "uid": $("#search_uid").val(),
                            "audit_name": $("#search_audit_name").val(),
                            "status": $("#search_status").val()
                        }
                    };
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _judgeStatus: function (full, status) {
                    var mulStatus = false;
                    if (full) {
                        for (var row in full) {
                            if (status == full[row].status) {
                                mulStatus = true;
                                break;
                            }
                        }
                    }
                    return mulStatus;
                },
                _del: function (ids, fn) {
                    if (ids) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": userUrl + "/" + ids,
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
                _egis: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        if (!me._judgeStatus(full, "通过")) {
                            TableList.optTable({
                                "tableId": tableId,
                                "url": userUrl + "/egis/" + ids,
                                "type": "put",
                                "async": true,
                                "successfn": function () {
                                    TableList.controllerRemoveDisabled(obj);
                                },
                                "failfn": function () {
                                    TableList.controllerRemoveDisabled(obj);
                                }
                            });
                        } else {
                            TableList.controllerRemoveDisabled(obj);
                            layer.msg("选中的记录有审核通过的客户");
                        }
                    }
                },
                _refuse: function (ids, full, obj) {
                    if (ids) {
                        if (!me._judgeStatus(full, "未通过")) {
                            var refuseRequest = function (requestData, successfn, usable) {
                                var reason = [];
                                reason.push($("#other").val());
                                requestData.reason = reason;
                                TableList.optTable({
                                    "tableId": tableId,
                                    "url": userUrl + "/refuse/" + ids,
                                    "type": "put",
                                    "async": true,
                                    "reqData": requestData,
                                    "successfn": successfn,
                                    "failfn": usable
                                });
                            };
                            me._openlayer(refuseRequest);
                        } else {
                            layer.msg("选中的记录有审核未通过的客户");
                        }
                    }
                },
                _edit: function (ids, full, obj) {
                    if (ids) {
                        if (!me._judgeStatus(full, "未通过")) {
                            var refuseRequest = function (requestData, successfn, usable) {
                                var reason = [];
                                reason.push($("#other").val());
                                requestData.reason = reason;
                                TableList.optTable({
                                    "tableId": tableId,
                                    "url": userUrl + "/" + ids,
                                    "type": "put",
                                    "async": true,
                                    "reqData": requestData,
                                    "successfn": successfn,
                                    "failfn": usable
                                });
                            };
                            me._openlayer(refuseRequest);
                        } else {
                            layer.msg("选中的记录有审核未通过的客户");
                        }
                    }
                },
                _resetHtml: function () {
                    $("input[name='reason']").not(":first").each(function () {
                        $(this).iCheck("uncheck");
                    })
                    $("#other").val('');
                },
                _openlayer: function (yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var height = $("#detail").height() + 120;
                    var area = ["50%", "45%"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "客户驳回",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail"),
                        btn: ['确定', '关闭'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");//按钮不可用
                                yes({}, function () {
                                    layer.closeAll();
                                }, usable);
                            } catch (e) {
                                usable();
                            }
                        }
                    });
                }

            };
        }();

    </script>
@endsection
