@extends('layouts.app_table')
@section('tablecontent')
    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">姓名</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="user_name" type="text" class="form-control" placeholder="姓名"
                           required data-parsley-maxlength="60"
                           data-parsley-maxlength-message="最长不允许超过60">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">手机号</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="phone" type="text" class="form-control" placeholder="手机号"
                           required data-parsley-required-message="手机号不允许为空"
                           pattern="^1\d{10}" disabled>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">邮箱</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="email" type="email" class="form-control" placeholder="邮箱"
                           required disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">继教号</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="unum" type="text" class="form-control" placeholder="继教号">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">年龄</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="age" type="text" class="form-control" placeholder="年龄">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">工龄</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="seniority" type="text" class="form-control" placeholder="工龄">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">地址</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input id="address" type="text" class="form-control" placeholder="地址">
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
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">手机号</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_phone"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">邮箱</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_email"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">姓名</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_school_name"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">继教号</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_unum"></li>
                                    <li class="col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">状态</label>
                                        <select class="col-md-7 col-sm-8 col-xs-12" id="search_status">
                                            <option value=""></option>
                                            <option value="1">启用</option>
                                            <option value="2">待审核</option>
                                            <option value="3">已停用</option>
                                        </select></li>
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
                    <table id="account_table" class="table table-striped table-bordered bulk_action">
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
    <div id="detail_mould"></div>
    <script type="application/javascript">
        var schoolUrl = "/school";
        $(function () {
            AccountUtil.init();
        });
        AccountUtil = function (me) {
            var listUrl = schoolUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "account_table";
            var searchInfo;
            return me = {
                _initOwn: function () {
                    $("#search").on("click", function () {
                        me._searchList();
                    });
                    $("#reset").on("click", function () {
                        $("#search_phone").val('');
                        $("#search_email").val('');
                        $("#search_school_name").val('');
                        $("#search_unum").val('');
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
                        "action='/school/import?action=uploadfile' " +
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
                                    CommonUtil.redirect("/school");
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
                        "sTitle": "学校",
                        "data": "school_name"
                    }, {
                        "sTitle": "省",
                        "data": "province_name"
                    }, {
                        "sTitle": "市",
                        "data": "city_name"
                    }, {
                        "sTitle": "县区",
                        "data": "area_name"
                    }];
                    var oSetting = {
                        "tableId": tableId,
                        "url": listUrl,
                        "chk": {
                            "display": false
                        },
                        "aoColumns": aoColumns,
                        "order": [[0, "desc"]],
                        "toolbar": {
                            "custom": {
                                "info": "导入", "func": function (content) {
                                    me._import(content);
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
                            "phone": $("#search_phone").val(),
                            "email": $("#search_email").val(),
                            "schoolName": $("#search_school_name").val(),
                            "unum": $("#search_unum").val(),
                            "status": $("#search_status").val()
                        }
                    };
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _edit: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        var fillData = function (data) {
                            $("#school_name").val(data.name);
                            $("#phone").val(data.phone);
                            $("#email").val(data.email);
                            $("#unum").val(data.unum);
                            $("#age").val(data.age);
                            $("#seniority").val(data.seniority);
                            $("#role").val(data.role_id);
                            $("#sex").val(data.sex);
                            $("#user_title").val(data.user_title_id);
                            $("#school").val(data.school_id);
                            //$("#school option[value='" + data.school_id + "']").attr('selected', true);
                            $.each(data.courses, function () {
                                $("#course option[value='" + this.course_id + "']").attr('selected', true);
                            });
                            $.each(data.grades, function () {
                                $("#grade option[value='" + this.grade_id + "']").attr('selected', true);
                            });
                            $("select").trigger("chosen:updated");
                            $("#address").val(data.address);
                        };
                        var updateData = function (requestData, successfn, usable) {
                            requestData.schoolName = $("#school_name").val();
                            requestData.phone = $("#phone").val();
                            requestData.email = $("#email").val();
                            requestData.unum = $("#unum").val();
                            requestData.age = $("#age").val();
                            requestData.seniority = $("#seniority").val();
                            requestData.roles = $("#role").val();
                            requestData.sex = $("#sex").val();
                            requestData.userTitle = $("#user_title").val();
                            requestData.school = $("#school").val();
                            requestData.courses = $("#course").val();
                            requestData.grades = $("#grade").val();
                            requestData.address = $("#address").val();
                            TableList.optTable({
                                "tableId": tableId,
                                "url": schoolUrl + "/" + ids,
                                "type": "put",
                                "reqData": requestData,
                                "successfn": successfn,
                                "failfn": usable
                            });
                        };
                        CommonUtil.requestService(schoolUrl + "/" + ids, "", true, "get",
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
                _resetHtml: function () {
                    $(".parsley-error").removeClass("parsley-error");
                    $("ul.parsley-errors-list").remove();
                    $("#detail").remove();
                    $("#detail_mould").append(lay);
                    $("#user_id").val('');

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
                        "width": $("#user_name").width() + "%",
                        "height": 30
                    });
                },
                _openlayer: function (id, type, yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["50%", "60%"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "用户信息",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var requestData = {};
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
