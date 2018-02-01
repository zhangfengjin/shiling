@extends('layouts.app_table')@section('tablecontent')
    <script type="application/javascript">
        var route = CommonUtil.getRootPath() + "/meet/upload?action=uploadimage&token=" + $('meta[name="csrf-token"]').attr('content');
        var accept = {};
        var fileNumLimit = 7;
        var fileSizeLimit = 3 * 1024 * 1024;    // 3 M
        var fileSingleSizeLimit = 1024 * 1024;  // 1 M
    </script>
    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/webuploader.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/style.css')}}"/>
    <div class="row">
        <div id="wrapper">
            <div id="container">
                <!--头部，相册选择和格式选择-->

                <div id="uploader">
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div id="filePicker"></div>
                            {{--<p>或将照片拖到这里，单次最多可选300张</p>--}}
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
    <script type="text/javascript" src="{{url('/js/plugins/webuploader/webuploader.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/plugins/webuploader/upload.js')}}"></script>

    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">会议名称</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="meet_name" type="text" class="form-control" placeholder="会议名称">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">主讲人</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="keynote_speaker" type="text" class="form-control" placeholder="主讲人">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">人数限制</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="limit_count" type="text" class="form-control" placeholder="人数限制">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">会议开始时间</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="begin_time" type="text" class="form-control" placeholder="会议开始时间">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">会议结束时间</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="end_time" type="text" class="form-control" placeholder="会议结束时间">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">参会对象</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="to_object" type="text" class="form-control" placeholder="人数限制">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">省</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select id="province" class="form-control" required>
                        <option value=""></option>
                        @foreach($provinces as $province)
                            <option value="{{$province->province_code}}">{{$province->province_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">市</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select id="city" class="form-control" required>
                        <option value=""></option>
                        @foreach($cities as $city)
                            <option parent="{{$city->province_code}}"
                                    value="{{$city->city_code}}">{{$city->city_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">县区</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select id="area" class="form-control" required>
                        <option value=""></option>
                        @foreach($areas as $area)
                            <option parent="{{$area->city_code}}"
                                    value="{{$area->id}}">{{$area->area_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">详细地址</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <textarea id="addr" rows="3" class="form-control" placeholder="详细地址"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12"></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <textarea id="abstract" rows="3" class="form-control" placeholder="会议详情"></textarea>
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
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_meet_name"></li>
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
        var meetUrl = "/meet";
        $(function () {
            AccountUtil.init();
        });
        AccountUtil = function (me) {
            var listUrl = meetUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "meet_table";
            var searchInfo;
            return me = {
                _initOwn: function () {
                    $("#search").on("click", function () {
                        me._searchList();
                    });
                    $("#reset").on("click", function () {
                        $("#search_phone").val('');
                        $("#search_email").val('');
                        $("#search_meet_name").val('');
                        $("#search_unum").val('');
                        $("#search_status").val('');
                    });
                },
                init: function () {
                    me._initOwn();
                    me._createList();
                },
                _import: function (content) {

                },
                _createList: function () {
                    var aoColumns = [{
                        "sTitle": "",
                        "data": "id"
                    }, {
                        "sTitle": "会议名称",
                        "data": "name"
                    }, {
                        "sTitle": "主讲人",
                        "data": "keynote_speaker"
                    }, {
                        "sTitle": "开始时间",
                        "data": "begin_time"
                    }, {
                        "sTitle": "结束时间",
                        "data": "end_time"
                    }, {
                        "sTitle": "限制人数",
                        "data": "limit_count"
                    }, {
                        "sTitle": "学校",
                        "data": "schoolName"
                    }, {
                        "sTitle": "职称",
                        "data": "userTitleName"
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
                        "toolbar": {
                            "add": {
                                "info": "新建", "func": me._add
                            }
                        },
                        "opt": {
                            "check-square-o": {
                                "display": 1,
                                "info": "取消会议",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status != "已取消") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._cancel
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
                            "phone": $("#search_phone").val(),
                            "email": $("#search_email").val(),
                            "meetName": $("#search_meet_name").val(),
                            "unum": $("#search_unum").val(),
                            "status": $("#search_status").val()
                        }
                    };
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _cancel: function (ids, fn) {
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
                _add: function () {
                    me._openlayer(0, 1, function (requestData, successfn, usable) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": schoolUrl,
                            "type": "post",
                            "reqData": requestData,
                            "successfn": successfn,
                            "failfn": usable
                        });
                    });
                },
                _edit: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        var fillData = function (data) {
                            $("#umeet_name").val(data.name);
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
                            requestData.meetName = $("#meet_name").val();
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
                },
                _openlayer: function (id, type, yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["80%", "60%"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "会议信息",
                        scrollbar: false,
                        area: area, // 宽高
                        content: $("#detail"),
                        btn: ['保存', '取消'],
                        yes: function (index, layero) {
                            btns = layero.children(".layui-layer-btn").children("a");
                            try {
                                btns.css("pointer-events", "none");
                                var requestData = {
                                    "meetName": $("#meet_name").val(),
                                    "keynote_speaker": $("#keynote_speaker").val(),
                                    "limit_count": $("#limit_count").val(),
                                    "begin_time": $("#begin_time").val(),
                                    "end_time": $("#end_time").val(),
                                    "to_object": $("#to_object").val(),
                                    "area_id": $("#area").val(),
                                    "addr": $("#addr").val(),
                                    "abstract": $("#abstract").val()
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
