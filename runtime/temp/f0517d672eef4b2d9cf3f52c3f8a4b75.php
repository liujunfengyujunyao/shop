<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"./template/mobile/new2/partner\order_list.html";i:1533876250;s:41:"./template/mobile/new2/public\header.html";i:1533876250;s:45:"./template/mobile/new2/public\header_nav.html";i:1533876250;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>订单管理--<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <link rel="stylesheet" href="__STATIC__/css/style.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/iconfont.css"/>
    <script src="__STATIC__/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="__STATIC__/js/zepto-1.2.0-min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="__STATIC__/js/style.js" type="text/javascript" charset="utf-8"></script>
    <script src="__STATIC__/js/mobile-util.js" type="text/javascript" charset="utf-8"></script>
    <script src="__PUBLIC__/js/global.js"></script>
    <script src="__STATIC__/js/layer.js"  type="text/javascript" ></script>
    <script src="__STATIC__/js/swipeSlide.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body class="g4">

<div class="classreturn loginsignup ">
    <div class="content">
        <div class="ds-in-bl return">
            <a href="javascript:history.back(-1)"><img src="__STATIC__/images/return.png" alt="返回"></a>
        </div>
        <div class="ds-in-bl search center">
            <span>订单管理</span>
        </div>
        <div class="ds-in-bl menu">
            <a href="javascript:void(0);"><img src="__STATIC__/images/class1.png" alt="菜单"></a>
        </div>
    </div>
</div>
<div class="flool tpnavf">
    <div class="footer">
        <ul>
            <li>
                <a class="yello" href="<?php echo U('Index/index'); ?>">
                    <div class="icon">
                        <i class="icon-shouye iconfont"></i>
                        <p>首页</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Goods/categoryList'); ?>">
                    <div class="icon">
                        <i class="icon-fenlei iconfont"></i>
                        <p>分类</p>
                    </div>
                </a>
            </li>
            <li>
                <!--<a href="shopcar.html">-->
                <a href="<?php echo U('Cart/cart'); ?>">
                    <div class="icon">
                        <i class="icon-gouwuche iconfont"></i>
                        <p>购物车</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('User/index'); ?>">
                    <div class="icon">
                        <i class="icon-wode iconfont"></i>
                        <p>我的</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- 订单列表Start -->
<div class="ajax_return">
    <?php if(empty($orderList)): ?>
        <!-- 没有内容 Start -->
        <div class="comment_con p">
            <div class="nonenothing">
                <img src="__STATIC__/images/none.png"/>
                <p>暂无记录</p>
            </div>
        </div>
        <!-- 没有内容 End -->
        <?php else: ?>
        <!-- 列表Start -->
        <?php if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): if( count($orderList)==0 ) : echo "" ;else: foreach($orderList as $key=>$vo): ?>
            <div class="mypackeg ma-to-20 getmore">
                <div class="packeg p">
                    <div class="maleri30">
                        <div class="fl">
                            <h1><span></span><span class="bgnum"></span></h1>

                            <p class="bgnum"><span>订单编号：<?php echo $vo['order_sn']; ?></span></p>
                        </div>
                        <div class="fr">
                            <span>收益：￥<?php echo $vo['money']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="shop-mfive p">
                    <div class="maleri30">
                        <?php if(is_array($vo['goods_list']) || $vo['goods_list'] instanceof \think\Collection || $vo['goods_list'] instanceof \think\Paginator): if( count($vo['goods_list'])==0 ) : echo "" ;else: foreach($vo['goods_list'] as $key=>$goods): ?>
                            <div class="sc_list se_sclist paycloseto" style="border-top: 1px solid #e5e5e5;">
                                <div class="shopimg fl">
                                    <img src="<?php echo goods_thum_images($goods[goods_id],200,200); ?>"/>
                                </div>
                                <div class="deleshow fr">
                                    <div class="deletes">
                                        <span class="similar-product-text"><?php echo getSubstr($goods[goods_name],0,20); ?></span>
                                    </div>
                                    <div class="prices wiconfine">
                                        <p class="sc_pri"><span>￥<?php echo $goods['member_goods_price']; ?></span></p>
                                    </div>
                                    <div class="qxatten wiconfine">
                                        <p class="weight"><span>数量</span>&nbsp;<span><?php echo $goods[goods_num]; ?></span></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>

                <div class="shop-rebuy-price p">
                    <div class="maleri30">
                        <span class="price-alln">
                            <span class="red">￥<?php echo $vo['total_amount']; ?></span><span class="threel" id="goodsnum">共<?php echo $vo['count_goods_num']; ?>件</span>
                        </span>
                        <a class="shop-rebuy paysoon" href="<?php echo U('/Mobile/Partner/orderInfo',array('id'=>$vo['order_id'])); ?>">查看详情</a>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <!-- 列表End -->
    <?php endif; ?>
</div>
<!-- 订单列表End -->
</body>
</html>