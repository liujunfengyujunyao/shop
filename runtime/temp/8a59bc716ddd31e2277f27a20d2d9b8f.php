<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"./template/mobile/new2/partner\stock_list.html";i:1537327139;s:41:"./template/mobile/new2/public\header.html";i:1533876250;s:45:"./template/mobile/new2/public\header_nav.html";i:1533876250;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>配货员库存列表--<?php echo $tpshop_config['shop_info_store_title']; ?></title>
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
            <span>配货员库存列表</span>
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
<style>
    .my {margin-bottom: 0}
    #store_stock .ask {
        background: #e23435;
        color: #fff;
        display: inline;
        padding: 5px 10px;
    }
</style>
<div class="quedbox bg_white" id="stock-list">
    <?php if(empty($list)): ?>
        <div class="nonenothing">
            <img src="__STATIC__/images/none.png"/>
            <p>没找到相关记录</p>
        </div>
        <?php else: ?>
        <div class="fukcuid mae">
            <div class="maleri30">
                <div class="head_acc ma-to-20">
                    <ul>
                        <a href="<?php echo U('Mobile/Partner/stockList'); ?>">
                            <li <?php if($type == 'list'): ?>class="red"<?php endif; ?>>库存列表</li>
                        </a>
                        <a href="<?php echo U('Mobile/Partner/stockLog'); ?>">
                            <li <?php if($type == 'log'): ?>class="red"<?php endif; ?>>库存日志</li>
                        </a>
                        <!--<a <?php if($type == 'list'): ?>class="red"<?php endif; ?>>-->
                            <!--<li href="<?php echo U('Mobile/Partner/stockList'); ?>" data-list="1">库存列表</li>-->
                        <!--</a>-->
                        <!--<a <?php if($type == 'log'): ?>class="red"<?php endif; ?>>-->
                            <!--<li href="<?php echo U('Mobile/Partner/stockLog'); ?>" data-list="2">库存日志</li>-->
                        <!--</a>-->
                    </ul>
                </div>
                <?php if($type == 'list'): ?>
                    <div class="allpion">
                        <div class="fll_acc fll_acc-h">
                            <ul><li class="time-h">商品信息</li><li class="time-h">当前库存/最大库存量</li><li class="time-h">更新时间</li></ul>
                        </div>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
                            <div class="fll_acc">
                                <ul>
                                    <li class="time-h"><a href="<?php echo U('Mobile/Goods/goodsInfo',array('id'=>$vo[goods_id])); ?>"><p><img src="<?php echo goods_thum_images($vo['goods_id'],80,80); ?>" width="80" height="80"></p><p><?php echo getSubstr($vo['goods_name'],0,7); ?></p></a></li>
                                    <li class="time-h <?php if($vo['goods_num'] <= ($storeage/100)*($vo['stock_num'])): ?>red<?php endif; ?>"><?php echo (isset($vo['goods_num']) && ($vo['goods_num'] !== '')?$vo['goods_num']:"0"); ?>/<?php echo $vo['stock_num']; ?></li>
                                    <li class="time-h"><?php if($vo['edittime'] == null): ?>无<?php else: ?><p><?php echo date("Y-m-d",$vo['edittime']); ?></p><p><?php echo date("H:i:s",$vo['edittime']); ?></p><?php endif; ?></li>
                                </ul>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div class="head_acc ma-to-20">
                        <ul>
                            <li class="red" style="float: right">
                                <a href="<?php echo U('Mobile/Partner/act_apply'); ?>">申请补货</a>
                            </li>
                        </ul>
                    </div>
                <?php endif; if($type == 'log'): ?>
                    <div class="allpion">
                        <div class="fll_acc fll_acc-h">
                            <ul><li class="time-h">商品信息</li><li class="time-h">库存</li><li class="time-h">变动时间</li></ul>
                        </div>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
                            <div class="fll_acc">
                                <ul>
                                    <li class="time-h"><a href="<?php echo U('Mobile/Goods/goodsInfo',array('id'=>$vo[goods_id])); ?>"><p><img src="<?php echo goods_thum_images($vo['goods_id'],80,80); ?>" width="80" height="80"></p><p><?php echo getSubstr($vo['goods_name'],0,7); ?></p></a></li>
                                    <li class="time-h"><?php echo $vo['stock']; ?></li>
                                    <li class="time-h"><?php if($vo['ctime'] == null): ?>无<?php else: ?><?php echo date("Y-m-d",$vo['ctime']); endif; ?></li>
                                </ul>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>