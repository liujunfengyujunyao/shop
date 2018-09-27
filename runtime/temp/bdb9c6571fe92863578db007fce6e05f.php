<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:37:"./template/phone/new/index\index.html";i:1538025501;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="__STATIC__/css/new/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="__STATIC__/css/new/common.css">
    <link rel="stylesheet" href="__STATIC__/css/new/index1.css">
    <title>设备管理系统</title>
</head>
<body>
    <header id="head">
        <div class="titlebar">
            <p class="title">今日总收益（元）</p>
            <p class="title_num">393.00</p>
            <span class="fa fa-question-circle"></span>
        </div>

        <ul class="foobar">
            <li class="foo_item"><span>在线支付</span><span>96.00元</span></li>
            <li class="foo_item"><span>广告收益</span><span>19.00元</span></li>
            <li class="foo_item"><span>现金兑币</span><span>278.00元</span></li>
        </ul>

        <div class="lastbar">
            <div href="#"><span>礼品消耗</span><span>4个，40.00元</span></div>
            <div href="#"><span>线下投币</span><span>204个</span></div>
        </div>
    </header>
    <section id="body">
        <div class="module">
            <div class="long">
                <a href="#">
                <div class="icon fa fa-tachometer">
                    <!-- <span class=""></span> -->
                </div>
                <div class="text">
                    <p class="p1">设备管理</p>
                    <p class="p2">共93台，在线53台</p>
                </div>
                </a>


            </div>
            <ul class="short">
                <li>
                    <a href="#">
                    <div class="icon fa fa-recycle" style="color:#4864ed;"></div>
                    <div class="text">
                        <div class="p1" >绑定设备</div>
                        <div class="p2">扫描设备二维码</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo U('Phone/Machine/index'); ?>">
                    <div class="icon fa fa-money" style="color:#fe7b13;"></div>
                    <div class="text">
                        <div class="p1">设备列表</div>
                        <div class="p2">编辑设备位置名称</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                    <div class="icon fa fa-line-chart" style="color:#ff524c;"></div>
                    <div class="text">
                        <div class="p1">收益统计</div>
                        <div class="p2">实时收益流水</div>
                    </div>
                    </a>
                </li>
             <!--    <li>
                    <a href="#">
                    <div class="icon fa fa-gift" style="color:#3d7ce8;"></div>
                    <div class="text">
                        <div class="p1">礼品统计</div>
                        <div class="p2">消耗统计及管理</div>
                    </div>
                    </a>
                </li> -->
            </ul>
        </div>

        <div class="module">
            <div class="long">
                <a href="#">
                <div class="icon fa fa-tachometer">
                    
                </div>
                <div class="text">
                    <p class="p1">礼品管理</p>
                    <p class="p2"></p>
                </div>
                </a>


            </div>
            <ul class="short">
                <li>
                    <a href="#">
                    <div class="icon fa fa-recycle" style="color:#4864ed;"></div>
                    <div class="text">
                        <div class="p1" >配置礼品</div>
                        <div class="p2">位置名称价格</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo U('Phone/Goods/stock_index'); ?>">
                    <div class="icon fa fa-money" style="color:#fe7b13;"></div>
                    <div class="text">
                        <div class="p1">礼品库存</div>
                        <div class="p2">查看礼品库存</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                    <div class="icon fa fa-line-chart" style="color:#ff524c;"></div>
                    <div class="text">
                        <div class="p1">库存日志</div>
                        <div class="p2">礼品库存记录</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="">
                    <div class="icon fa fa-gift" style="color:#3d7ce8;"></div>
                    <div class="text">
                        <div class="p1">礼品统计</div>
                        <div class="p2">消耗统计数据</div>
                    </div>
                    </a>
                </li>
            </ul>
        </div>


        
               <div class="module">
            <div class="long">
                <a href="#">
                <div class="icon fa fa-tachometer">
                    <!-- <span class=""></span> -->
                </div>
                <div class="text">
                    <p class="p1">配置</p>
                    <p class="p2"></p>
                </div>
                </a>


            </div>
            <ul class="short">
                <li>
                    <a href="<?php echo U('Phone/Machine/game_price_index'); ?>">
                    <div class="icon fa fa-recycle" style="color:#4864ed;"></div>
                    <div class="text">
                        <div class="p1" >游戏价格</div>
                        <div class="p2">设置单次游戏价格</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo U('Phone/Machine/odds_index'); ?>">
                    <div class="icon fa fa-money" style="color:#fe7b13;"></div>
                    <div class="text">
                        <div class="p1">设备赔率</div>
                        <div class="p2">调整经营策略</div>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                    <div class="icon fa fa-line-chart" style="color:#ff524c;"></div>
                    <div class="text">
                        <div class="p1">游戏变更</div>
                        <div class="p2">修改设备搭载游戏</div>
                    </div>
                    </a>
                </li>
             <!--    <li>
                    <a href="#">
                    <div class="icon fa fa-gift" style="color:#3d7ce8;"></div>
                    <div class="text">
                        <div class="p1">礼品统计</div>
                        <div class="p2">消耗统计及管理</div>
                    </div>
                    </a>
                </li> -->
            </ul>
        </div>
    </section>
</body>
<script src="__STATIC__/js/new/rem.js"></script>
<script src="__STATIC__/js/new/jquery-2.1.4.min.js"></script>
<!-- <script src="js/bootstrap.min.js"></script> -->
</html>