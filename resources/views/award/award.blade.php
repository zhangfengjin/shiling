<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link href="{{url('/css/plugins/award/prize.css')}}" rel="stylesheet" type="text/css">
    <style>
        .iframe {
            position: fixed;
            top: 15%;
            width: 1400px;
            height: 700px;
            z-index: 9;
            border: 0;
        }

        .choujiang2 .mainwrap .leftpart h1 {
            font-size: 32px !important;
            color: #78f0ff !important;
            font-weight: normal !important;
            text-align: center !important;
            letter-spacing: 5px !important;
        }

        .user_list {
            padding: 100px;
            padding-left: 30px;
            position: absolute;
            z-index: 11;
            font-size: 22px;
            color: #78f0ff;
            font-weight: normal;
            text-align: center;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>

<div class="header">

    <p id="signPersonCount" style="position: fixed;top: 40px;right: 300px;font-size: 22px;color: #78f0ff;"></p>
</div>

<!--<div class="barrage"></div>-->

<div class="bgwrap">
    <video src="{{url('/images/award/bg.mp4')}}" autoplay="autoplay" loop="loop"
           style="background:#040811"></video>
    <div class="wrap">
        <div id="showGuestShowDiv" class="tk" style="display: none;z-index:0">
            <div class="tkbg">
                <div class="star star1"><img src="http://www.chuanying360.com/static/screen/img/star1.png"></div>
                <div class="star star2"><img src="http://www.chuanying360.com/static/screen/img/star2.png"></div>
                <div class="star star3"><img src="http://www.chuanying360.com/static/screen/img/star3.png"></div>
                <div class="star star4"><img src="http://www.chuanying360.com/static/screen/img/star4.png"></div>
                <div class="star star5"><img src="http://www.chuanying360.com/static/screen/img/star5.png"></div>
            </div>
        </div>
        <div class="iframe">
            <div class="mainbody choujiang2 clearfix">
                <img src="http://www.chuanying360.com/static/screen/img/loading.gif" class="mainbg">
                <div class="mainwrap pa">
                    <div class="leftpart fl">
                        {{--<h1 id="join_activity_count">参加抽奖人数：{{$muCount}}人</h1>--}}
                        <div class="picwrap clearfix">
                            <div class="giftpic pr fl">
                                <img id="awardImg" src="http://www.chuanying360.com/static/screen/img/giftpic.jpg"
                                     class="pic pr">
                                <img src="http://www.chuanying360.com/static/screen/img/light.png" class="round pa">
                            </div>
                            <div class="userpic pr fr">
                                <div id="user_list">
                                    <div id="user_0">
                                        <img src="http://wx.qlogo.cn/mmopen/anblvjPKYbMWUbm8Fh68dfZebPib2Ytx8ZRabKnKZFo1u9PBNsNHfGocLqxb6lnBKuHB1JgGltJLQLqBDl2Yaibe5kZvlYtZ0K/0"
                                             class="pic pr" id="awardUserImg">
                                    </div>
                                </div>
                                <img src="http://www.chuanying360.com/static/screen/img/light.png" class="round pa">
                            </div>
                        </div>
                        <div class="prizewrap clearfix">
                            <div class="prize item fl">
                                <select id="prize_name" class="form-control">
                                    <option value="">奖项</option>
                                    @foreach($prizes as $prize)
                                        <option value="{{$prize->id}}">{{$prize->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="gift item fl">
                                <select id="prize_remark" class="form-control" disabled>
                                    <option value="">奖品</option>
                                    @foreach($prizes as $prize)
                                        <option value="{{$prize->id}}">{{$prize->remark}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="number item fl">
                                <select id="prize_count" class="form-control" disabled>
                                    <option value="">名额</option>
                                    @foreach($prizes as $prize)
                                        <option value="{{$prize->id}}">{{$prize->prize_count}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a href="javascript:;" class="btn" id="start_award">开始抽奖</a>
                    </div>
                    <div class="rightpart fr pa">
                        <h1 id="award_in_list"></h1>
                        <div class="list pa">
                            <ul id="awardListUl">
                                <li id="19214">
                                    <img src="http://wx.qlogo.cn/mmopen/PiajxSqBRaEJksJBjvvqNicZwiaV03zI6ia8Tiar6YnY7z2xvFFGibgryqYlsuhFZ6cu0vichicoF4pbHa3H547JcjqXPQ/0">
                                    <p title="张三">张三</p>
                                    <a href="javascript:;" onclick="deleteAward('19214','370')"></a>
                                </li>
                                <li id="19208">
                                    <img src="http://wx.qlogo.cn/mmopen/PiajxSqBRaEKO5RgTNwE89rRhvzERwH983O4lQ0WicEqzH99351ibs1k2fyVE0BylZ4QsR4osknsYY7ZJ3mRFfTyw/0">
                                    <p title="张三">张三</p>
                                    <a href="javascript:;" onclick="deleteAward('19208','370')"></a>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="btn" onclick="clearUser();">清空名单</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{url('js/plugins/jquery/jquery.min.js')}}"></script>
<!-- layer 层-->
<script src="{{url('js/plugins/layer/layer.js')}}"></script>
<script src="{{url('js/common.js')}}"></script>
<script type="application/javascript">
    $(function () {
        var selectId = "";
        $("#prize_name").change(function () {
            selectId = $(this).val();
            $("#prize_remark").val(selectId);
            $("#prize_count").val(selectId);
            var prizeName = $(this).find("option:selected").text();
            $("#award_in_list").empty();
            $("#award_in_list").append("<em id='awardItemName'>" + prizeName + "</em>获奖人数：<em id='awardCountEm'>0</em>")
        });
        var request = CommonUtil.getRequest();
        initMeetId = request["meetId"];
        var userList = [];
        CommonUtil.requestService("/meetuser/list?meetId=" + initMeetId, "", true, "get",
            function (response) {
                //从后台成功获取数据 拼写到前台页面
                //弹出层模式
                if (response.code == 0) {
                    var data = response.data;
                    $("#signPersonCount").text("参会人数：" + data.count);
                    /*"<img src='' alt='" + this.phone + "' class='pic pr' id='awardUserImg'>"*/
                    var html = "<div id='all_user' style='display: none;'>";
                    $.each(data.data, function () {
                        /*var html = "<div id='user_" + this.user_id + "' class='userpic pr fr' style='display: none;'>" +
                         "<span class='user_list'>" + this.phone + "   （" + this.name + "）</span>" +
                         "<img src='/images/award/prize_user.jpg' alt='" + this.phone + "' class='pic pr' id='awardUserImg'>" +
                         "<img src='http://www.chuanying360.com/static/screen/img/light.png' class='round pa'>" +
                         "</div>";*/
                        html += "<div id='user_" + this.user_id + "' style='display: none;'><span class='user_list'>" + this.phone + "   （" + this.name + "）</span></div>";
                        userList.push(this.user_id);
                    });
                    html += "<img src='/images/award/prize_user.jpg' alt='" + this.phone + "' class='pic pr' id='awardUserImg'>" +
                        "</div>";
                    $("#user_list").append(html);
                }
            }, function () {
            });
        function createRandom(num, from, to) {
            var arr = [];
            var json = {};
            while (arr.length < num) {
                //产生单个随机数
                var ranNum = Math.ceil(Math.random() * (to - from)) + from;
                //通过判断json对象的索引值是否存在 来标记 是否重复
                if (!json[ranNum]) {
                    json[ranNum] = 1;
                    arr.push(ranNum);
                }

            }
            return arr;
        }

        var codeTimer;

        function startAward(size, random) {
            var i = 0;
            codeTimer = setInterval(function () {
                if (i < size) {
                    $("div[hand='current']").hide().removeAttr("hand");
                    $("#user_" + random[i]).show().attr("hand", 'current');
                } else {
                    clearInterval(codeTimer);
                    startAward(size, random);
                }
                i++;
            }, 100);
        }

        var prizeUsers = [];
        //获取中奖人员
        function getAwardPrizeUser() {
            var requestData = {
                "type": "meet",
                "prize_id": selectId,//奖项id
                "meet_id": initMeetId,//会议id
                "user_list": userList//会议签到人员id（截止到页面初始化时的签到人员，不包含抽奖开始后的签到人员）
            };
            CommonUtil.requestService("/award/prizeuser?", requestData, true, "post",
                function (response) {
                    //从后台成功获取数据 拼写到前台页面
                    //弹出层模式
                    if (response.code == 0) {
                        var award = response.data;
                        clearInterval(codeTimer);
                        $("div[hand='current']").hide().removeAttr("hand");
                        $("#user_" + award.user_id).show().attr("hand", 'current');
                        if ((idx = $.inArray(award.user_id, userList)) >= 0) {
                            userList.splice(idx, 1);
                            prizeUsers[selectId].push(award.user_id);
                        }
                    }
                }, function () {
                    clearInterval(codeTimer);
                });
        }
        function getAwardPrizeUserList() {
            var requestData = {
                "type": "meet",
                "prize_id": selectId,//奖项id
                "meet_id": initMeetId,//会议id
                "user_list": userList//会议签到人员id（截止到页面初始化时的签到人员，不包含抽奖开始后的签到人员）
            };
            CommonUtil.requestService("/award/prizeuser?", requestData, true, "post",
                function (response) {
                    //从后台成功获取数据 拼写到前台页面
                    //弹出层模式
                    if (response.code == 0) {
                        var awards = response.data;
                    }
                }, function () {
                    clearInterval(codeTimer);
                });
        }

        $("#start_award").on("click", function () {
            if ($(this).attr("stop") == 1) {
                //从中奖数组中提取中奖人员，显示到中奖区域
                getAwardPrizeUser();
                $("#start_award").text("开始抽奖");
                $("#start_award").removeAttr("stop");
            } else {
                var size = userList.length;
                alert(size);
                if (selectId != "" && size > 0) {
                    $("#start_award").text("停止");
                    $("#start_award").attr("stop", 1);
                    $("#user_0").hide();
                    $("#all_user").show();
                    var random = createRandom(size, 0, size);
                    startAward(size, random);
                } else {
                    alert("选择奖项");
                }
            }
        });

    });
</script>
</body>
</html>