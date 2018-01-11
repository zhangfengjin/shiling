/**
 * Resize function without multiple trigger
 *
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function ($, sr) {
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced() {
            var obj = this, args = arguments;

            function delayed() {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    };

    // smartresize 
    jQuery.fn[sr] = function (fn) {
        return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
    };

})(jQuery, 'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

var setContentHeight = function () {
    // reset height
    $RIGHT_COL.css('min-height', $(window).height());

    var bodyHeight = $BODY.outerHeight(),
        footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
        leftColHeight = $LEFT_COL.eq(0).height() + $SIDEBAR_FOOTER.height(),
        contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

    // normalize content
    //contentHeight -= ($NAV_MENU.height() + footerHeight);
    //alert($NAV_MENU.height() + $FOOTER.height());
    //contentHeight += $NAV_MENU.height() + $FOOTER.height();
    $RIGHT_COL.css('min-height', contentHeight);
};

// Sidebar 左侧菜单栏
function init_sidebar() {
// TODO: This is some kind of easy fix, maybe we can improve this

    //点击菜单事件
    $SIDEBAR_MENU.find('a').on('click', function (ev) {
        var sm = $BODY.is(".nav-sm");
        var $li = $(this).parent();
        var firstUl = $('ul:first', $li);
        if ($li.is('.active') && !$li.attr("open_child")) {//隐藏下级菜单
            $li.removeClass('active active-sm');
            $('ul:first', $li).removeAttr("menu").slideUp(function () {
                setContentHeight();
            });
        } else {
            $li.removeAttr("open_child");
            // prevent closing menu if we are on child menu
            var winHeight = $LEFT_COL.eq(0).height();//窗口高度
            var ulHeight = firstUl.height();//ul高度
            var initHeight = firstUl.attr("initHeight");
            if (!initHeight) {
                firstUl.attr("initHeight", ulHeight);
            } else {
                ulHeight = parseInt(initHeight);
            }
            var clickPosition = $li.position().top;//点击菜单的位置(屏幕Y位置)
            var bottomHeight = winHeight - clickPosition;//下边距
            var beforeHeight = 0, parentMenu = firstUl.parents("[menu='parent']");
            if (!$li.parent().is('.child_menu')) {//点击一级菜单，隐藏其他的二级菜单
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').removeAttr("menu").hide();
                if (sm && firstUl.length) {//包含二级菜单时
                    firstUl.attr("menu", "parent");
                    if (bottomHeight < ulHeight) {
                        var top = bottomHeight - ulHeight;
                        firstUl.css({
                            "top": top
                        });
                    }
                    firstUl.css({
                        "max-height": ulHeight,
                        "overflow-y": "auto",
                        "overflow-x": "hidden"
                    });
                }
            } else {//点击二级菜单
                var parentActiveChilds = $li.parent();
                parentActiveChilds.find("li").removeClass("active active-sm");
                parentActiveChilds.find("li ul").hide();
            }
            $li.addClass('active');
            firstUl.slideDown(function () {
                setContentHeight();
            });
        }
    });
    var toggleMenu = function () {
        if ($BODY.hasClass('nav-md')) {
            if (!device.mobile()) {
                $.cookie('nav_sm', 1, {"path": "/"});
            }
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            $.removeCookie('nav_sm', {"path": "/"});
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }
        $BODY.toggleClass('nav-md nav-sm');
        setContentHeight();
        //一级菜单高度超过右侧内容区域时 取消固定侧边栏
        if ($BODY.is(".nav-sm") && device.mobile()) {
            var rightHeight = $RIGHT_COL.height();
            var leftHeight = $LEFT_COL.eq(1).height();
            if (leftHeight > rightHeight) {
                $RIGHT_COL.css({
                    "min-height": leftHeight
                });
                $LEFT_COL.eq(0).css("position", "absolute");
            }
        }
    };
// toggle small or large menu
    //切换侧边栏事件
    $MENU_TOGGLE.on('click', function () {
        $SIDEBAR_MENU.find("li").removeAttr("open_child");
        toggleMenu();
        activeMenu();
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    var activeMenu = function () {
        if ($.cookie("nav_sm")) {//当侧边栏收缩时 点击下级菜单后 不再显示下级菜单
            $SIDEBAR_MENU.find('a').filter(function () {
                return this.href == CURRENT_URL;
            }).parent('li').addClass('current-page').parents('ul').parent().addClass('active').attr("open_child", 1);
        } else {//原有逻辑
            $SIDEBAR_MENU.find('a').filter(function () {
                return this.href == CURRENT_URL;
            }).parent('li').addClass('current-page').parents('ul').slideDown(function () {
                setContentHeight();
            }).parent().addClass('active');
        }
    };
    activeMenu();

    // 重新计算内容
    /*$(window).smartresize(function () {
     setContentHeight();
     });*/

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel: {preventDefault: true}
        });
    }
};
// /Sidebar

// NProgress 页面进度加载
if (typeof NProgress != 'undefined') {
    $(document).ready(function () {
        NProgress.start();
    });

    $(window).load(function () {
        NProgress.done();
    });
}
//递归获取所有的节点id
function getNodeIdArr(node, current) {
    var ts = [];
    if (current) {
        ts.push(node.nodeId);
    }
    if (node.nodes) {
        for (var x in node.nodes) {
            ts.push(node.nodes[x].nodeId);
            if (node.nodes[x].nodes) {
                var getNodeDieDai = getNodeIdArr(node.nodes[x]);
                for (var j in getNodeDieDai) {
                    ts.push(getNodeDieDai[j]);
                }
            }
        }
    } else {
        ts.push(node.nodeId);
    }
    return ts;
}
//递归获取所有选中的节点id
function getAllChecked(node) {
    var ts = [];
    if (node.nodes) {
        for (var x in node.nodes) {
            if (node.nodes[x].state.checked) {
                ts.push(node.nodes[x].nodeId);
            }
            if (node.nodes[x].nodes) {
                var getNodeDieDai = getAllChecked(node.nodes[x]);
                for (var j in getNodeDieDai) {
                    ts.push(getNodeDieDai[j]);
                }
            }
        }
    } else {
        if (node.state.checked) {
            ts.push(node.nodeId);
        }
    }
    return ts;
}

$(document).ready(function () {
    //Tooltip 菜单左下角操作
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
    // Panel toolbox 面板右上角操作
    $('.collapse-title').on('click', function () {
        var $BOX_PANEL = $(this).closest('.panel'),
            //$ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.panel-body');

        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function () {
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200);
            $BOX_PANEL.css('height', 'auto');
        }

        //$ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.panel');//modify by fengjin1 2017-2-22 x_panel

        $BOX_PANEL.remove();
    });
    // iCheck radio或checkbox 所有页面都可使用
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
    //菜单栏
    init_sidebar();
});
	

