<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
    </style>
</head>
<body>

<div class="header">

    <p id="signPersonCount" style="position: fixed;top: 40px;right: 300px;font-size: 22px;color: #78f0ff;">参会人数：2273</p>
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
                        <h1 id="join_activity_count">参加抽奖人数：2252人</h1>
                        <div class="picwrap clearfix">
                            <div class="giftpic pr fl">
                                <img id="awardImg" src="http://www.chuanying360.com/static/screen/img/giftpic.jpg"
                                     class="pic pr">
                                <img src="http://www.chuanying360.com/static/screen/img/light.png" class="round pa">
                            </div>
                            <div class="userpic pr fr">
                                <img src="http://wx.qlogo.cn/mmopen/anblvjPKYbMWUbm8Fh68dfZebPib2Ytx8ZRabKnKZFo1u9PBNsNHfGocLqxb6lnBKuHB1JgGltJLQLqBDl2Yaibe5kZvlYtZ0K/0"
                                     class="pic pr" id="awardUserImg">
                                <img src="http://www.chuanying360.com/static/screen/img/light.png" class="round pa">
                            </div>
                        </div>
                        <div class="prizewrap clearfix">
                            <div class="prize item fl">
                                <p><span>一等奖</span><i></i></p>
                                <div class="itemselect">
                                    <a href="javascript:;" data-id="33">一等奖</a>
                                    <a href="javascript:;" data-id="34">二等奖</a>
                                    <a href="javascript:;" data-id="35">三等奖</a>
                                    <a href="javascript:;" data-id="36">幸运奖</a>
                                    <a href="javascript:;" data-id="67">鼓励奖</a>
                                    <a href="javascript:;" data-id="378">惊喜大奖</a>
                                </div>
                            </div>
                            <div class="gift item fl">
                                <p><span id="giftSpan" class="giftmun" title="唇膏">唇膏</span><i></i></p>
                                <div id="giftDiv" class="itemselect giftmun">
                                </div>
                            </div>
                            <div class="number item fl">
                                <p><span id="numberSpan" class="peomun">名额</span><i></i></p>
                                <div id="numberDiv" class="itemselect peomun">
                                </div>
                            </div>
                        </div>
                        <a href="javascript:;" class="btn" id="start_award">开始抽奖</a>
                    </div>
                    <div class="rightpart fr pa">
                        <h1><em id="awardItemName">一等奖</em>获奖人数：<em id="awardCountEm">10</em></h1>
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
<script type="application/javascript">
    $(function () {
        $("#start_award").on("click", function () {

        });
    });
</script>
</body>
</html>