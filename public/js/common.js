CommonUtil = function (util) {
    return util = {
        getRootPath: function () {
            var strFullPath = window.document.location.href;
            var strPath = window.document.location.pathname;
            var pos = strFullPath.indexOf(strPath);
            var prePath = strFullPath.substring(0, pos);
            return prePath;
            /*
             * var postPath = strPath.substring(0,
             * strPath.substr(1).indexOf('/') + 1); return (prePath + postPath);
             */
        },
        getRequest: function () {// 获取Url参数 返回参数数组 调用方式：var Request = new
            // Object();Request
            // =GetRequest();Request['参数1'];
            var url = location.search; // 获取url中"?"符后的字串
            var request = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for (var i = 0; i < strs.length; i++) {
                    request[strs[i].split("=")[0]] = decodeURI(strs[i]
                        .split("=")[1]);
                }
            }
            return request;
        },
        // 在当前窗体跳转
        redirect: function (route) {
            window.location.href = util.getRootPath() + (route == undefined ? "" : route);
        },
        openWin: function (url) {
            var link = $("<a href='" + url + "' target='_blank'></a>").get(0);
            var e = document.createEvent('MouseEvents');
            e.initEvent('click', true, true);
            link.dispatchEvent(e);
        },
        tailorImg: function (maxWidth, maxHeight, width, height) {
            var hRatio;
            var wRatio;
            var Ratio = 1;
            wRatio = maxWidth / width;
            hRatio = maxHeight / height;
            if (maxWidth == 0 && maxHeight == 0) {
                Ratio = 1;
            } else if (maxWidth == 0) {// if (hRatio < 1) Ratio =
                hRatio;
            } else if (maxHeight == 0) {
                if (wRatio < 1)
                    Ratio = wRatio;
            } else if (wRatio < 1 || hRatio < 1) {
                Ratio = (wRatio <= hRatio ? wRatio : hRatio);
            }
            if (Ratio < 1) {
                width = width * Ratio;
                height = height * Ratio;
            }
            var imgObj = [];
            imgObj["height"] = height;
            imgObj["width"] = width;
            return imgObj;
        },
        // json字符串转为json对象
        parseToJson: function (str) {
            try {
                if (str == "")
                    return {
                        "success": 1
                    };
                var obj = eval('(' + str + ')');
                return obj;
            } catch (e) {
                return {
                    "success": 0,
                    "error": '获取数据错误--接口返回数据格式可能错误'
                };
            }
        },
        // ajax通用请求
        requestService: function (url, data, async, type, successfn, failfn) {
            if (url != null && url != "" && typeof (url) != "undefined") {
                url = util.getRootPath() + url;
                data = (data == null || typeof (data) == "undefined") ? ""
                    : data;
                async = (async == null || typeof (async) == "undefined") ? "true"
                    : async;// 默认true
                type = (type == null || typeof (type) == "undefined") ? "get"
                    : type;// 默认get
                var headers = {};
                if (type != "get") {
                    headers["X-CSRF-TOKEN"] = $('meta[name="csrf-token"]').attr('content');
                }
                headers["x-requested-with"] = "XMLHttpRequest";
                $.ajax({
                    type: type,
                    async: async,
                    data: data,
                    url: url,
                    dataType: "json",
                    headers: headers,
                    crossDomain: true,
                    success: function (ret) {
                        console.log(ret);
                        if (ret.code === 0)//请求成功
                            successfn(ret);
                        else {
                            try {
                                if (ret.code == 400) { //400表单校验错误
                                    $error = "<div class='alert alert-error'>" +
                                        "<h4><strong>校验错误!</strong></h4>" +
                                        "<ul class='outside'>";
                                    if (typeof ret.data == "object") {
                                        $.each(ret.data, function (index, content) {
                                            $error += "<li><p>" + content + "</p></li>";
                                        });
                                    } else {
                                        $error += "<li><p>" + ret.data + "</p></li>";
                                    }
                                    $error += "</ul></div>";
                                    layer.open({
                                        type: 1,
                                        title: false,
                                        shadeClose: true,
                                        content: $error
                                    });
                                }
                                else {
                                    layer.msg(ret.msg, {
                                        offset: "50px"
                                    });
                                }
                            }
                            finally {
                                if (failfn)
                                    failfn();
                            }
                        }
                    },
                    error: function (ex) {
                        $error = "<div class='alert alert-error'>" +
                            "<h4><strong>可能引起错误原因：</strong></h4>" +
                            "<ul class='outside'>";
                        $error += "<li><p>请求接口不存在</p></li>" +
                            "<li><p>token校验不匹配</p></li>" +
                            "<li><p>功能接口错误</p></li>" +
                            "<li><p>事件错误</p></li>";
                        $error += "</ul></div>";
                        layer.open({
                            type: 1,
                            title: "异常错误",
                            shadeClose: true,
                            content: $error
                        });
                        if (failfn)
                            failfn();
                    }
                });

            }
        }
    };
}();
