!function () {
    layer.config({extend: ["extend/layer.ext.js", "skin/moon/style.css"], skin: "layer-ext-moon"});
    var e = {htdy: $("html, body")};
    e.demo1 = $("#demo1"), $("#chutiyan>a").on("click", function () {
        var t = $(this), a = t.index(), r = e.demo1.children("p").eq(a), n = r.position().top;
        switch (e.demo1.animate({scrollTop: e.demo1.scrollTop() + n}, 0), a) {
            case 0:
                var o = -1;
                !function s() {
                    var e = layer.alert("点击确认更换图标", {
                        icon: o,
                        shadeClose: !0,
                        title: -1 === o ? "初体验" : "icon：" + o
                    }, s);
                    8 === ++o && layer.close(e)
                }();
                break;
            case 1:
                var o = 0;
                !function p() {
                    layer.alert("点击确认更换图标", {
                        icon: o,
                        shadeClose: !0,
                        skin: "layer-ext-moon",
                        shift: 5,
                        title: -1 === o ? "第三方扩展皮肤" : "icon：" + o
                    }, p);
                    9 === ++o && layer.confirm("怎么样，是否很喜欢该皮肤，去下载？", {skin: "layer-ext-moon"}, function (e, t) {
                        t.find(".layui-layer-btn0").attr({
                            href: "http://layer.layui.com/skin.html",
                            target: "_blank"
                        }), layer.close(e)
                    })
                }();
                break;
            case 6:
                layer.open({
                    type: 1,
                    area: ["420px", "240px"],
                    skin: "layui-layer-rim",
                    content: '<div style="padding:20px;">即直接给content传入html字符<br>当内容宽高超过定义宽高，会自动出现滚动条。<br><br><br><br><br><br><br><br><br><br><br>很高兴在下面遇见你</div>'
                });
                break;
            case 7:
                layer.open({
                    type: 1,
                    skin: "layui-layer-demo",
                    closeBtn: !1,
                    area: "350px",
                    shift: 2,
                    shadeClose: !0,
                    content: '<div style="padding:20px;">即传入skin:"样式名"，然后你就可以为所欲为了。<br>你怎么样给她整容都行<br><br><br>我是华丽的酱油==。</div>'
                });
                break;
            case 8:
                layer.tips("Hi，我是tips", this);
                break;
            case 11:
                var i = layer.load(0, {shade: !1});
                setTimeout(function () {
                    layer.close(i)
                }, 5e3);
                break;
            case 12:
                var l = layer.load(1, {shade: [.1, "#fff"]});
                setTimeout(function () {
                    layer.close(l)
                }, 3e3);
                break;
            case 13:
                layer.tips("我是另外一个tips，只不过我长得跟之前那位稍有些不一样。", this, {tips: [1, "#3595CC"], time: 4e3});
                break;
            case 14:
                layer.prompt({title: "输入任何口令，并确认", formType: 1}, function (e) {
                    layer.prompt({title: "随便写点啥，并确认", formType: 2}, function (t) {
                        layer.msg("演示完毕！您的口令：" + e + "<br>您最后写下了：" + t)
                    })
                });
                break;
            case 15:
                layer.tab({
                    area: ["600px", "300px"],
                    tab: [{
                        title: "无题",
                        content: '<div style="padding:20px; line-height:30px; text-align:center">欢迎体验layer.tab<br>此时此刻不禁让人吟诗一首：<br>一入前端深似海<br>从此妹纸是浮云<br>以下省略七个字<br>。。。。。。。<br>——贤心</div>'
                    }, {title: "TAB2", content: '<div style="padding:20px;">TAB2该说些啥</div>'}, {
                        title: "TAB3",
                        content: '<div style="padding:20px;">有一种坚持叫：layer</div>'
                    }]
                });
                break;
            case 16:
                e.photoJSON ? layer.photos({photos: e.photoJSON}) : $.getJSON("js/demo/photos.json?v=", function (t) {
                        e.photoJSON = t, layer.photos({photos: t})
                    });
                break;
            default:
                new Function(r.text())()
        }
    })
}();