@extends('layouts.app_table')@section('tablecontent')
    <script type="text/javascript">
        var uploadAction = CommonUtil.getRootPath() + "/goods/upload?action=uploadimage&token=" + $('meta[name="csrf-token"]').attr('content');
        var route = CommonUtil.getRootPath() + "/meet/upload?action=uploadimage&token=" + $('meta[name="csrf-token"]').attr('content');
        var accept = {};
        var fileNumLimit = 7;
        var fileSizeLimit = 3 * 1024 * 1024;    // 3 M
        var fileSingleSizeLimit = 1024 * 1024;  // 1 M
    </script>
    <link href="{{url('/js/plugins/umeditor/themes/default/css/umeditor.css')}}" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="{{url('/js/plugins/umeditor/third-party/template.min.js')}}"></script>
    <script type="text/javascript" charset="utf-8" src="{{url('/js/plugins/umeditor/umeditor.config.js')}}"></script>
    <script type="text/javascript" charset="utf-8" src="{{url('/js/plugins/umeditor/umeditor.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/plugins/umeditor/lang/zh-cn/zh-cn.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/webuploader.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('/css/plugins/webuploader/style.css')}}"/>



    <script type="text/javascript">
        //实例化编辑器
        var um = UM.getEditor('goods_detail');
        um.addListener('blur', function () {
            $('#focush2').html('编辑器失去焦点了')
        });
        um.addListener('focus', function () {
            $('#focush2').html('')
        });
        //按钮的操作
        function insertHtml() {
            var value = prompt('插入html代码', '');
            um.execCommand('insertHtml', value)
        }
        function isFocus() {
            alert(um.isFocus())
        }
        function doBlur() {
            um.blur()
        }
        function createEditor() {
            enableBtn();
            um = UM.getEditor('goods_detail');
        }
        function getAllHtml() {
            alert(UM.getEditor('goods_detail').getAllHtml())
        }
        function getContent() {
            var arr = [];
            arr.push("使用editor.getContent()方法可以获得编辑器的内容");
            arr.push("内容为：");
            arr.push(UM.getEditor('goods_detail').getContent());
            alert(arr.join("\n"));
        }
        function getPlainTxt() {
            var arr = [];
            arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
            arr.push("内容为：");
            arr.push(UM.getEditor('goods_detail').getPlainTxt());
            alert(arr.join('\n'))
        }
        function setContent(isAppendTo) {
            var arr = [];
            arr.push("使用editor.setContent('欢迎使用umeditor')方法可以设置编辑器的内容");
            UM.getEditor('goods_detail').setContent('欢迎使用umeditor', isAppendTo);
            alert(arr.join("\n"));
        }
        function setDisabled() {
            UM.getEditor('goods_detail').setDisabled('fullscreen');
            disableBtn("enable");
        }

        function setEnabled() {
            UM.getEditor('goods_detail').setEnabled();
            enableBtn();
        }

        function getText() {
            //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
            var range = UM.getEditor('goods_detail').selection.getRange();
            range.select();
            var txt = UM.getEditor('goods_detail').selection.getText();
            alert(txt)
        }

        function getContentTxt() {
            var arr = [];
            arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
            arr.push("编辑器的纯文本内容为：");
            arr.push(UM.getEditor('goods_detail').getContentTxt());
            alert(arr.join("\n"));
        }
        function hasContent() {
            var arr = [];
            arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
            arr.push("判断结果为：");
            arr.push(UM.getEditor('goods_detail').hasContents());
            alert(arr.join("\n"));
        }
        function setFocus() {
            UM.getEditor('goods_detail').focus();
        }
        function deleteEditor() {
            disableBtn();
            UM.getEditor('goods_detail').destroy();
        }
        function disableBtn(str) {
            var div = document.getElementById('btns');
            var btns = domUtils.getElementsByTagName(div, "button");
            for (var i = 0, btn; btn = btns[i++];) {
                if (btn.id == str) {
                    domUtils.removeAttributes(btn, ["disabled"]);
                } else {
                    btn.setAttribute("disabled", "true");
                }
            }
        }
        function enableBtn() {
            var div = document.getElementById('btns');
            var btns = domUtils.getElementsByTagName(div, "button");
            for (var i = 0, btn; btn = btns[i++];) {
                domUtils.removeAttributes(btn, ["disabled"]);
            }
        }
    </script>

    <div id="detail" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品名称</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="goods_name" type="text" class="form-control" placeholder="商品名称"
                           required data-parsley-maxlength="200">
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品类型</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select id="goods_type_id">
                        <option value="0"></option>
                        @foreach($goodsTypes as $goodsType )
                            <option value="{{$goodsType->id}}">{{$goodsType->value}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品数量</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input id="goods_count" type="text" class="form-control" placeholder="商品数量"
                           required data-parsley-type="number">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品价格(元)</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input id="price" type="text" class="form-control" placeholder="商品价格(元)"
                           required pattern="^\d+(.\d+)?$">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品介绍</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <textarea id="abstract" rows="3" class="form-control" placeholder="商品介绍"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">商品介绍</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
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
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">会议详情</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    {{--<script type="text/plain" id="goods_detail" style="width:1000px;height:240px;">
                    </script>--}}
                </div>
            </div>
        </form>
    </div>

    <div id="detail_reason" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label class="col-md-12 col-sm-12 col-xs-12">
                        <textarea id="reason" rows="3" class="form-control" placeholder="取消原因"
                                  required data-parsley-maxlength="20"></textarea>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <div id="detail_notify" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 checkbox">
                    <div class="checkbox">
                        <label class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" class="flat"
                                   name="send_sms" id="send_sms" value="1"
                                   checked> 短信通知&nbsp;&nbsp;
                        </label>
                        <label class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" class="flat"
                                   name="send_email" id="send_email"
                                   value="2" checked> 邮件通知&nbsp;&nbsp;
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label class="col-md-12 col-sm-12 col-xs-12">
                        <textarea id="notify_content" rows="3" class="form-control" placeholder="通知内容"
                                  required data-parsley-maxlength="20"></textarea>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <div id="detail_refund" class="x_content detail_content">
        <div class="x_panel">
            <div class="x_content">
                <table id="user_table" class="table table-striped table-bordered bulk_action">
                </table>
            </div>
        </div>
    </div>

    <div id="detail_meet_prize" class="x_content detail_content">
        <div class="x_panel">
            <div class="x_content">
                <table id="meet_prize_table" class="table table-striped table-bordered bulk_action">
                </table>
            </div>
        </div>
    </div>

    <div id="detail_prize" class="x_content detail_content" data-parsley-validate>
        <form class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">奖项</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input id="prize_name" type="text" class="form-control" placeholder="奖项"
                           required data-parsley-maxlength="30">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">奖品</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input id="remark" type="text" class="form-control" placeholder="奖品"
                           required data-parsley-maxlength="50">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">奖品个数</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input id="prize_count" type="text" class="form-control" placeholder="奖品个数"
                           required data-parsley-type="number">
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

    <script type="text/javascript" src="{{url('/js/plugins/webuploader/webuploader.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/plugins/webuploader/upload.js')}}"></script>

    <div id="detail_mould"></div>
    <script type="application/javascript">
        var meetUrl = "/goods";
        $(function () {
            AccountUtil.init();
        });
        AccountUtil = function (me) {
            var listUrl = meetUrl + "/list";
            var lay = $("#detail").prop("outerHTML");
            var tableId = "meet_table";
            var searchInfo;
            var provinces = [];  //定义数组
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
                        "sTitle": "商品名称",
                        "data": "name"
                    }, {
                        "sTitle": "商品数量",
                        "data": "goods_count"
                    }, {
                        "sTitle": "价格",
                        "data": "price"
                    }, {
                        "sTitle": "商品介绍",
                        "data": "abstract"
                    }, {
                        "sTitle": "类别",
                        "data": "goods_type"
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
                        "toolbar": {
                            "add": {
                                "info": "新建", "func": me._add
                            }
                        },
                        "opt": {
                            "edit": {
                                "display": 1,
                                "info": "编辑",
                                "func": me._edit
                            },
                            "check-square-o": {
                                "display": 1,
                                "info": "取消会议",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status == "已取消") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._cancel
                            },
                            "money": {
                                "display": 1,
                                "info": "退款",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status != "已取消") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._refund
                            },
                            "comment": {
                                "display": 1,
                                "info": "通知",
                                "func": me._notify
                            },
                            "file": {
                                "display": 1,
                                "info": "查看参会人员",
                                "func": function (ids, full, obj) {
                                    CommonUtil.redirect('/meetuser?meetId=' + ids);
                                }
                            },
                            "suitcase": {
                                "display": 1,
                                "info": "查看中奖人员",
                                "func": function (ids, full, obj) {
                                    CommonUtil.redirect('/muprize?meetId=' + ids);
                                }
                            },
                            "heart": {
                                "display": 1,
                                "info": "抽奖设置",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status == "已取消") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": me._meetPrize
                            },
                            "gift": {
                                "display": 1,
                                "info": "去抽奖",
                                "draw": function (full) {
                                    var params = {};
                                    if (full.status == "已取消") {
                                        params.disabled = true;
                                    }
                                    return params;
                                },
                                "func": function (ids, full, obj) {
                                    CommonUtil.redirect('/meetuser?meetId=' + ids);
                                }
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
                _edit: function (ids, full, obj) {
                    if (ids) {
                        TableList.controllerDisabled(obj);
                        var fillData = function (data) {
                            $("#meet_name").val(data.meet_name);
                            $("#keynote_speaker").val(data.keynote_speaker);
                            $("#limit_count").val(data.limit_count);
                            $("#begin_time").attr("begin_time", data.begin_time);
                            $('#begin_time').val(data.begin_time);
                            $("#end_time").attr("end_time", data.end_time);
                            $('#end_time').val(data.end_time);
                            $("#to_object").val(data.to_object);
                            $("#in_price").val(data.in_price);
                            $("#addr").val(data.addr);
                            $("#abstract").val(data.abstract);
                            $("#province").val(data.province_code);
                            $('#province').trigger('change');
                            $("#city").val(data.city_code);
                            $('#city').trigger('change');
                            $("#area").val(data.area_id);
                            $('#area').trigger('change');
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
                        area: ["300px", "250px"], // 宽高
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
                        var userUrl = "/meetuser/list?meetId=" + ids + "&status=1";
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
                _meetPrize: function (ids, full, obj) {
                    if (ids) {
                        var userTableId = "meet_prize_table";
                        var userUrl = "/mprize";
                        var meetUserList = function (ids) {
                            var aoColumns = [{
                                "sTitle": "",
                                "data": "id"
                            }, {
                                "sTitle": "奖项",
                                "data": "name"
                            }, {
                                "sTitle": "奖品",
                                "data": "remark"
                            }, {
                                "sTitle": "奖品数量",
                                "data": "prize_count"
                            }];
                            var oSetting = {
                                "tableId": userTableId,
                                "url": userUrl + "/list?meetId=" + ids,
                                "chk": {
                                    "display": true
                                },
                                "aoColumns": aoColumns,
                                "order": [[0, "desc"]],
                                "toolbar": {
                                    "add": {
                                        "info": "添加", "func": function (ids, fn) {
                                            _addPrize(ids);
                                        }
                                    }
                                },
                                "opt": {
                                    "edit": {
                                        "display": 1,
                                        "info": "编辑",
                                        "func": function (ids, full, obj) {
                                            _editPrize(ids, full, obj);
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
                                title: "会议奖品",
                                scrollbar: false,
                                shadeClose: true,
                                area: ["50%", "500px"], // 宽高
                                content: $("#detail_meet_prize"),
                                yes: function (index, layero) {
                                },
                                success: function () {
                                    meetUserList(ids);
                                }
                            });
                        };
                        openMeetUser(ids);
                        var openPrize = function (id, type, yes) {
                            $("#prize_name").val("");
                            $("#remark").val("");
                            $("#prize_count").val("");
                            var usable = function () {
                                btns.css("pointer-events", "");
                            };
                            var area = ["400px", "280px"];
                            if (device.mobile()) {
                                area = ["80%", "70%"];
                            }
                            layer.open({
                                type: 1,
                                title: "奖品信息",
                                scrollbar: false,
                                area: area, // 宽高
                                content: $("#detail_prize"),
                                btn: ['保存', '取消'],
                                yes: function (index, layero) {
                                    btns = layero.children(".layui-layer-btn").children("a");
                                    try {
                                        btns.css("pointer-events", "none");
                                        var requestData = {
                                            "prize_name": $("#prize_name").val(),
                                            "remark": $("#remark").val(),
                                            "prize_count": $("#prize_count").val(),
                                            "meet_id": ids
                                        };
                                        var parsl = $('#detail_prize').parsley();
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
                        };
                        var _addPrize = function () {
                            openPrize(ids, 1, function (requestData, successfn, usable) {
                                TableList.optTable({
                                    "tableId": userTableId,
                                    "url": userUrl,
                                    "type": "post",
                                    "reqData": requestData,
                                    "successfn": successfn,
                                    "failfn": usable
                                });
                            });
                        };
                        var _editPrize = function (ids, full, obj) {
                            if (ids) {
                                TableList.controllerDisabled(obj);
                                var fillData = function (data) {
                                    $("#prize_name").val(data.name);
                                    $("#remark").val(data.remark);
                                    $("#prize_count").val(data.prize_count);
                                };
                                var updateData = function (requestData, successfn, usable) {
                                    TableList.optTable({
                                        "tableId": userTableId,
                                        "url": userUrl + "/" + ids,
                                        "type": "put",
                                        "reqData": requestData,
                                        "successfn": successfn,
                                        "failfn": usable
                                    });
                                };
                                CommonUtil.requestService(userUrl + "/" + ids, "", true, "get",
                                    function (response) {
                                        if (response.code == 0) {
                                            openPrize(ids, 2, updateData);//打开表单；保存时回调updateData 将数据传输到后台
                                            fillData(response.data);//根据从后台获取的数据填充到表单上
                                        }
                                        TableList.controllerRemoveDisabled(obj);
                                    }, function () {
                                        TableList.controllerRemoveDisabled(obj);
                                    });
                            }
                        };
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
                                    "begin_time": $("#begin_time").attr("begin_time"),
                                    "end_time": $("#end_time").attr("end_time"),
                                    "to_object": $("#to_object").val(),
                                    "in_price": $("#in_price").val(),
                                    "area_id": $("#area").val(),
                                    "addr": $("#addr").val(),
                                    "abstract": $("#abstract").val(),
                                    "keynote_speaker_id": 0
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
