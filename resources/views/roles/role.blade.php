@extends('layouts.app_table')
@section('tablecontent')
    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input id="role_name" type="text" class="form-control" placeholder="" required>
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
                                        <label class="control-label col-md-5 col-sm-4 col-xs-12">角色</label>
                                        <input class="col-md-7 col-sm-8 col-xs-12" id="search_role_name"></li>
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
                    <table id="role_table" class="table table-striped table-bordered bulk_action">
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
        var roleUrl = "/role";
        $(function () {
            AccountUtil.init();
        });
        AccountUtil = function (me) {
            var listUrl = roleUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "role_table";
            var searchInfo;
            var provinces = [];  //定义数组
            return me = {
                _initOwn: function () {
                    $("#search").on("click", function () {
                        me._searchList();
                    });
                    $("#reset").on("click", function () {
                        $("#search_role_name").val('');
                    });
                },
                init: function () {
                    me._initOwn();
                    me._ddl();
                    me._createList();
                },
                _ddl: function () {
                    $("#province option").each(function () {  //遍历所有option
                        var province = $(this).val();
                        if (province != "") {
                            var cities = [];
                            $("#city option[parent='" + province + "']").each(function () {  //遍历所有option
                                var city = $(this).val();
                                if (city != "") {
                                    var cityName = $(this).text();
                                    var areas = [];
                                    $("#area option[parent='" + city + "']").each(function () {  //遍历所有option
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
                },
                _import: function (content) {

                },
                _createList: function () {
                    var aoColumns = [{
                        "sTitle": "",
                        "data": "id"
                    }, {
                        "sTitle": "角色",
                        "data": "name"
                    }, {
                        "sTitle": "创建人",
                        "data": "creator"
                    }, {
                        "sTitle": "创建时间",
                        "data": "created_at"
                    }];
                    var oSetting = {
                        "tableId": tableId,
                        "url": listUrl,
                        "chk": {
                            "display": false
                        },
                        "aoColumns": aoColumns,
                        "order": [[0, "desc"]]
                    };
                    TableList.datatable(oSetting);
                },
                _searchList: function () {
                    searchInfo = {
                        "searchs": {
                            "roleName": $("#search_role_name").val()
                        }
                    };
                    TableList.search(tableId, listUrl, searchInfo);
                },
                _add: function () {
                    me._openlayer(0, 1, function (requestData, successfn, usable) {
                        TableList.optTable({
                            "tableId": tableId,
                            "url": roleUrl,
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
                            $("#province").val(data.province_code);
                            $('#province').trigger('change');
                            $("#city").val(data.city_code);
                            $('#city').trigger('change');
                            $("#area").val(data.area_id);
                            $("#role_name").val(data.name);
                        };
                        var updateData = function (requestData, successfn, usable) {
                            TableList.optTable({
                                "tableId": tableId,
                                "url": roleUrl + "/" + ids,
                                "type": "put",
                                "reqData": requestData,
                                "successfn": successfn,
                                "failfn": usable
                            });
                        };
                        CommonUtil.requestService(roleUrl + "/" + ids, "", true, "get",
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
                        "width": $("#user_name").width() + "%",
                        "height": 30
                    });
                    $("#province").change(function () {
                        var province = $(this).val();
                        for (var idx in provinces) {
                            var pro = provinces[idx];
                            if (pro.name == province) {
                                $("#city").empty();
                                $("#area").empty();
                                var cities = pro.value;
                                for (var idx2 in cities) {
                                    var cityValue = cities[idx2].name;
                                    $("#city").append("<option parent='" + province + "' value='" + cityValue + "'>" + cities[idx2].value.name + "</option>");
                                    var areas = cities[idx2].value.value;
                                    for (var idx3 in areas) {
                                        $("#area").append("<option parent='" + cityValue + "' value='" + areas[idx3].name + "'>" + areas[idx3].value + "</option>");
                                    }
                                }
                            }
                        }
                    });
                    $("#city").change(function () {
                        $("#area").empty();
                        var cityValue = $(this).val();
                        var province = $("#city option[value='" + cityValue + "']").attr('parent');
                        for (var idx in provinces) {
                            var pro = provinces[idx];
                            if (pro.name == province) {
                                var cities = pro.value;
                                for (var idx2 in cities) {
                                    if (cityValue == cities[idx2].name) {
                                        var areas = cities[idx2].value.value;
                                        for (var idx3 in areas) {
                                            $("#area").append("<option parent='" + cityValue + "' value='" + areas[idx3].name + "'>" + areas[idx3].value + "</option>");
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                _openlayer: function (id, type, yes) {
                    me._resetHtml();
                    var usable = function () {
                        btns.css("pointer-events", "");
                    };
                    var area = ["400px", "400px"];
                    if (device.mobile()) {
                        area = ["80%", "70%"];
                    }
                    layer.open({
                        type: 1,
                        title: "学校信息",
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
                                    requestData.roleName = $("#role_name").val();
                                    requestData.area_id = $("#area").val();
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
