<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:39:"./template/mobile/new2/index\index.html";i:1536730365;s:41:"./template/mobile/new2/public\footer.html";i:1534215388;s:45:"./template/mobile/new2/public\footer_nav.html";i:1533876250;s:43:"./template/mobile/new2/public\wx_share.html";i:1533876250;}*/ ?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <title>首页-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <link rel="stylesheet" href="__STATIC__/css/style.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/iconfont.css"/>
    <script src="__STATIC__/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="__STATIC__/js/zepto-1.2.0-min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="__STATIC__/js/mobile-util.js" type="text/javascript" charset="utf-8"></script>
    <script src="__STATIC__/js/swipeSlide.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="__STATIC__/js/layer.js"  type="text/javascript" ></script>
</head>
<body>
    <!--顶部搜索栏-s-->
    <header>
        <div class="content">
            <div class="ds-in-bl search">
                <div class="sea-box">
                    <div class="logo">
                        <a href=""><img src="__STATIC__/images/logo.png" alt="LOGO"></a>
                    </div>
                    <span ></span>
                    <form action=""  method="post">
                        <div class="sear-input">
                            <a href="<?php echo U('Goods/ajaxSearch'); ?>">
                                <input type="text" name="q" id="search_text" class="search_text"   value="" placeholder="请输入您所搜索的商品">
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ds-in-bl login">
                <span>
                <?php if(isset($_COOKIE['user_id'])): ?>
                    <a href="<?php echo U('Mobile/User/index'); ?>"> <img class="after_login" src="__STATIC__/images/my.png"></a>
                <?php else: ?>
                    <a href="<?php echo U('Mobile/User/login'); ?>"> 登录</a>
                <?php endif; ?>
            </span>
            </div>
        </div>
    </header>
    <!--顶部搜索栏-e-->

    <!--顶部滚动广告栏-s-->
    <div class="banner ban1">
        <div class="mslide" id="slideTpshop">
            <ul>
                <!--广告表-->
                <?php $pid =2;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("5")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 5- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <li><a href="javascript:void(0)">
                        <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>" alt="">
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!--顶部滚动广告栏-e-->

    <!--菜单-start-->
    <div class="floor dh">
        <nav>
            <a href="<?php echo U('Mobile/Goods/goodslist',array('id'=> 1)); ?>">
                <span>
                    <img src="__STATIC__/images/hu.png" alt="护" /><br />
                    <span>护</span>
                </span>
            </a>
            <a href="<?php echo U('Mobile/Goods/goodslist',array('id'=> 2)); ?>">
                <span>
                    <img src="__STATIC__/images/yang.png" alt="养" /><br />
                    <span>养</span>
                </span>
            </a>
            <a href="<?php echo U('Mobile/Goods/goodslist',array('id'=> 3)); ?>">
                <span>
                    <img src="__STATIC__/images/tiao.png" alt="调" /><br />
                    <span>调</span>
                </span>
            </a>
            <a href="<?php echo U('Mobile/Goods/integralMall'); ?>">
                <span>
                    <img src="__STATIC__/images/icon_05.png" alt="积分商城" /><br />
                    <span>积分商城</span>
                </span>
            </a>

            <a href="<?php echo U('Mobile/Goods/goodslist', array('id' => 7)); ?>">
                <span>
                    <img src="__STATIC__/images/icon_07.png" alt="体验专区" /><br />
                    <span>体验专区</span>
                </span>
            </a>
            <a href="<?php echo U('Mobile/Activity/coupon_list'); ?>">
                <span>
                    <img src="__STATIC__/images/quan.png" alt="领券" /><br />
                    <span>领券</span>
                </span>
            </a>
            <!--<a href="shopcar.html">-->
            <a href="<?php echo U('Cart/cart'); ?>">
                <span>
                    <img src="__STATIC__/images/icon_17.png" alt="购物车" /><br />
                    <span>购物车</span>
                </span>
            </a>
            <!--<a href="my.html">-->
            <?php if(($level_id == 8) || ($level_id == 9)): if(($level_id == 8)): ?>
                    <a href="<?php echo U('Partner/index'); ?>">
                <?php endif; if(($level_id == 9)): ?>
                    <a href="<?php echo U('Store/index'); ?>">
                <?php endif; ?>
                    <span>
                        <img src="__STATIC__/images/guanli.png" alt="管理中心" /><br />
                        <span>管理中心</span>
                    </span>
                </a>
            <?php else: ?>
                <a href="<?php echo U('User/index'); ?>">
                <span>
                    <img src="__STATIC__/images/icon_19.png" alt="个人中心" /><br />
                    <span>个人中心</span>
                </span>
                </a>
            <?php endif; ?>

        </nav>
    </div>
    <!--菜单-end-->

    <!--秒杀-start-->
   <!-- <div class="floor secondkill">
        <div class="content">
            <div class="time p">
                <div class="djs lightning fl">
                    <span class="add">秒杀</span>
                    <span class="red" id=""><?php echo date('H',$start_time); ?>点场</span>
                    <span class="hms"></span>
                </div>
                <div class="xsxl fr">
                    <a href="<?php echo U('Mobile/Activity/flash_sale_list'); ?>">
                        <span>限时限量<img src="__STATIC__/images/or.png" alt="" /></span>
                    </a>
                </div>
            </div>
            <div class="shop p">
                <?php if(count($flash_sale_list) == nll): ?>
                    <div style="text-align: center;">暂无抢购商品。。。。</div>
                <?php endif; if(is_array($flash_sale_list) || $flash_sale_list instanceof \think\Collection || $flash_sale_list instanceof \think\Paginator): if( count($flash_sale_list)==0 ) : echo "" ;else: foreach($flash_sale_list as $key=>$v): ?>
                    <a href="<?php echo U('Mobile/Goods/goodsInfo',array('id'=>$v[goods_id],'item_id'=>$v[item_id])); ?>">
                        <div class="timerBox shopnum">
                            <img src="<?php echo goods_thum_images($v[goods_id],200,200); ?>"/>
                            <p>￥<span><?php echo $v[price]; ?></span>元</p>
                        </div>
                    </a>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>-->
    <!--秒杀-end-->

    <!--广告位-start-->
    <div class="banner ma-to-20">
        <?php $pid =400;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
            <a href="<?php echo $v['ad_link']; ?>">
            <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="floor advertisement">
        <div class="content">
            <div class="le lefhe fl">
                <?php $pid =301;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <a href="<?php echo $v['ad_link']; ?>">
                        <div class="td">
                            <img src="<?php echo $v[ad_code]; ?>">
                        </div>
                    </a>
                <?php endforeach; $pid =302;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <a href="<?php echo $v['ad_link']; ?>">
                        <div class="td">
                            <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>">
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="le re fr">
                <?php $pid =300;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <a href="<?php echo $v['ad_link']; ?>">
                        <div class="td">
                            <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>">
                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <!--广告位-end-->

    <!--新品上市-start-->
    <!--<div class="floor newshop">
        <div class="banner">
            <img src="__STATIC__/images/ind_22.jpg" alt="新品上市"/>
        </div>
        <div class="advertisement">
            <div class="content">
                <div class="le re fl">
                    <?php $pid =303;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                        <a href="<?php echo $v[ad_link]; ?>" >
                            <div class="td">
                                <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="le lefhe fr">
                    <?php $pid =304;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("2")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 2- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                        <a href="<?php echo $v[ad_link]; ?>" >
                            <div class="td">
                                <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>-->
    <!--新品上市-end-->

    <!--热销商品-start-->
    <!--<div class="floor hotshop index_hot">
        <div class="banner">
            <img src="__STATIC__/images/ind_33.jpg" alt="热销商品"/>
        </div>
        <div class="hotsome">
            <div class="bloc hottop">
                <?php $pid =305;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <div class="le fl">
                        <a href="<?php echo $v[ad_link]; ?>" >
                            <div class="td">
                                <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $pid =306;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("1")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                    <div class="le fr">
                        <a href="<?php echo $v[ad_link]; ?>" >
                            <div class="td">
                                <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="bloc">
                <div class="le foura">
                    <?php $pid =307;$ad_position = M("ad_position")->cache(true,TPSHOP_CACHE_TIME)->column("position_id,position_name,ad_width,ad_height","position_id");$result = M("ad")->where("pid=$pid  and enabled = 1 and start_time < 1536732000 and end_time > 1536732000 ")->order("orderby desc")->cache(true,TPSHOP_CACHE_TIME)->limit("4")->select();
if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->insert(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
  \think\Cache::clear();  
}


$c = 4- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && I("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Admin&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );  
    }
}
foreach($result as $key=>$v):       
    
    $v[position] = $ad_position[$v[pid]]; 
    if(I("get.edit_ad") && $v[not_adv] == 0 )
    {
        $v[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v[ad_link] = "/index.php?m=Admin&c=Ad&a=ad&act=edit&ad_id=$v[ad_id]";        
        $v[title] = $ad_position[$v[pid]][position_name]."===".$v[ad_name];
        $v[target] = 0;
    }
    ?>
                        <a href="<?php echo $v[ad_link]; ?>" >
                            <img src="<?php echo $v[ad_code]; ?>" title="<?php echo $v[title]; ?>" style="<?php echo $v[style]; ?>"/>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>-->
    <!--热销商品-end-->

    <!--猜您喜欢-start-->
    <div class="floor guesslike">
        <div class="banner">
            <img src="__STATIC__/images/ind_52.jpg" alt="猜您喜欢"/>
        </div>
        <div class="likeshop">
            <div id="J_ItemList">
                <ul class="product single_item info">
                </ul>
            </div>
        </div>
    </div>
    <!--猜您喜欢-end-->

    <!--底部-start-->
    <footer>
    <div class="flool1">
        <ul>
            <?php if(isset($_COOKIE['user_id'])): if($_COOKIE['user_id'] == 0): ?>
                    <li><a href="<?php echo U('Mobile/User/index'); ?>"><?php echo $_COOKIE['uname']; ?></a></li>
                <?php else: ?>
                    <li><a href="<?php echo U('Mobile/User/index'); ?>"><?php echo getSubstr(urldecode($_COOKIE['uname']),0,3);?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo U('Mobile/User/logout'); ?>">退出</a></li>
            <?php else: ?>
                <li><a href="<?php echo U('Mobile/User/login'); ?>">登录</a></li>
                <li><a href="<?php echo U('Mobile/User/reg'); ?>">注册</a></li>
            <?php endif; ?>
            <li><a href="tel:<?php echo $tpshop_config['shop_info_phone']; ?>">反馈</a></li>
            <li class="comebackTop">回到顶部</li>
        </ul>
    </div>

    <div class="flool3">
        <p>Copyright © <?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:'后台中心'); ?> 版权所有</p>
    </div>
</footer>
    <!--底部-end-->

    <!--底部导航-start-->
    <div class="foohi">
    <div class="footer">
        <ul>
            <li>
                <a <?php if(CONTROLLER_NAME == 'Index'): ?>class="yello" <?php endif; ?>  href="<?php echo U('Index/index'); ?>">
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
                <a href="<?php echo U('Cart/cart'); ?>">
                    <div class="icon">
                        <i class="icon-gouwuche iconfont"></i>
                        <p>购物车</p>
                    </div>
                </a>
            </li>
            <li>
                <a <?php if(CONTROLLER_NAME == 'User'): ?>class="yello" <?php endif; ?> href="<?php echo U('User/index'); ?>">
                    <div class="icon">
                        <i class="icon-wode iconfont"></i>
                        <p>我的</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	  var cart_cn = getCookie('cn');
	  if(cart_cn == ''){
		$.ajax({
			type : "GET",
			url:"/index.php?m=Home&c=Cart&a=header_cart_list",//+tab,
			success: function(data){								 
				cart_cn = getCookie('cn');
				$('#cart_quantity').html(cart_cn);						
			}
		});	
	  }
	  $('#cart_quantity').html(cart_cn);
});
</script>
<!-- 微信浏览器 调用微信 分享js-->
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="__PUBLIC__/js/global.js"></script>
<script type="text/javascript">
<?php if(ACTION_NAME == 'goodsInfo'): ?>
	var ShareLink = "<?php echo U('Mobile/Goods/goodsInfo',['id'=>$goods[goods_id]],'',true); ?>"; //默认分享链接
	var ShareImgUrl = "http://<?php echo \think\Request::instance()->server('SERVER_NAME'); ?><?php echo goods_thum_images($goods[goods_id],400,400); ?>"; // 分享图标
	var ShareTitle = "<?php echo (isset($goods['goods_name']) && ($goods['goods_name'] !== '')?$goods['goods_name']:$tpshop_config['shop_info_store_title']); ?>"; // 分享标题
	var ShareDesc = "<?php echo (isset($goods['goods_remark']) && ($goods['goods_remark'] !== '')?$goods['goods_remark']:$tpshop_config['shop_info_store_desc']); ?>"; // 分享描述
<?php elseif(ACTION_NAME == 'info'): ?>
	var ShareLink = "<?php echo U('Mobile/Team/info',['goods_id'=>$team[goods_id],'team_id'=>$team[team_id]]); ?>"; //默认分享链接
	var ShareImgUrl = "http://<?php echo \think\Request::instance()->server('SERVER_NAME'); ?><?php echo $team[share_img]; ?>"; //分享图标
	var ShareTitle = "<?php echo $team[share_title]; ?>"; //分享标题
	var ShareDesc = "<?php echo $team[share_desc]; ?>"; //分享描述
<?php elseif(ACTION_NAME == 'found'): ?>
	var ShareLink = "<?php echo U('Mobile/Team/found',['id'=>$teamFound[found_id]],'',true); ?>"; //默认分享链接
	var ShareImgUrl = "http://<?php echo \think\Request::instance()->server('SERVER_NAME'); ?><?php echo $team[share_img]; ?>"; //分享图标
	var ShareTitle = "<?php echo $team[share_title]; ?>"; //分享标题
	var ShareDesc = "<?php echo $team[share_desc]; ?>"; //分享描述
<?php else: ?>
	var ShareLink = "http://<?php echo $_SERVER[HTTP_HOST]; ?>/index.php?m=Mobile&c=Index&a=index"; //默认分享链接
	var ShareImgUrl = "http://<?php echo \think\Request::instance()->server('SERVER_NAME'); ?><?php echo $tpshop_config['shop_info_store_logo']; ?>"; //分享图标
	var ShareTitle = "<?php echo $tpshop_config['shop_info_store_title']; ?>"; //分享标题
	var ShareDesc = "<?php echo $tpshop_config['shop_info_store_desc']; ?>"; //分享描述
<?php endif; ?>

var is_distribut = getCookie('is_distribut'); // 是否分销代理
var user_id = getCookie('user_id'); // 当前用户id
//alert(is_distribut+'=='+user_id);
// 如果已经登录了, 并且是分销商
if(parseInt(is_distribut) == 1 && parseInt(user_id) > 0)
{									
	ShareLink = ShareLink + "&first_leader="+user_id;									
}

$(function() {
	if(isWeiXin() && parseInt(user_id)>0){
		$.ajax({
			type : "POST",
			url:"/index.php?m=Mobile&c=Index&a=ajaxGetWxConfig&t="+Math.random(),
			data:{'askUrl':encodeURIComponent(location.href.split('#')[0])},		
			dataType:'JSON',
			success: function(res)
			{
				//微信配置
				wx.config({
				    debug: false, 
				    appId: res.appId,
				    timestamp: res.timestamp, 
				    nonceStr: res.nonceStr, 
				    signature: res.signature,
				    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareQZone','hideOptionMenu'] // 功能列表，我们要使用JS-SDK的什么功能
				});
			},
			error:function(){
				return false;
			}
		}); 

		// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在 页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready 函数中。
		wx.ready(function(){
		    // 获取"分享到朋友圈"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareTimeline({
		        title: ShareTitle, // 分享标题
		        link:ShareLink,
		        desc: ShareDesc,
		        imgUrl:ShareImgUrl // 分享图标
		    });

		    // 获取"分享给朋友"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareAppMessage({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
		    });
			// 分享到QQ
			wx.onMenuShareQQ({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});	
			// 分享到QQ空间
			wx.onMenuShareQZone({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});

		   <?php if(CONTROLLER_NAME == 'User'): ?> 
				wx.hideOptionMenu();  // 用户中心 隐藏微信菜单
		   <?php endif; ?>	
		});
	}
});

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
</script>
<!--微信关注提醒 start-->
<?php if(\think\Session::get('subscribe') == 0): endif; ?>
<button class="guide" style="display:none;" onclick="follow_wx()">关注公众号</button>
<style type="text/css">
.guide{width:1rem;height:4rem;text-align: center;border-radius: 0.2rem ;font-size:0.5rem;padding:0.2rem 0;border:1px solid #adadab;color:#000000;background-color: #fff;position: fixed;right: 0.1rem;bottom: 10rem;}
#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
#guide{display:none;position:absolute;top:0.1rem;z-index:19999;}
#guide img{width: 70%;height: auto;display: block;margin: 0 auto;margin-top: 0.2rem;}
</style>
<script type="text/javascript">
  //关注微信公众号二维码	 
function follow_wx()
{
	layer.open({
		type : 1,  
		title: '关注公众号',
		content: '<img src="<?php echo $wx_qr; ?>" width="100%">',
		style: ''
	});
}
  
$(function(){
	if(isWeiXin()){
		var subscribe = getCookie('subscribe'); // 是否已经关注了微信公众号		
		if(subscribe == 0)
			$('.guide').show();
	}else{
		$('.guide').hide();
	}
})
 
</script> 

<!--微信关注提醒  end-->
<!-- 微信浏览器 调用微信 分享js  end-->
    <!--底部导航-end-->
    <script src="__PUBLIC__/js/jqueryUrlGet.js"></script><!--获取get参数插件-->
<script src="__STATIC__/js/style.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="__STATIC__/js/sourch_submit.js"></script>
<script type="text/javascript">
    /**
     * 秒杀模块倒计时
     * */
    function GetRTime(end_time){
        var NowTime = new Date();
        var t = (end_time*1000) - NowTime.getTime();
        var d=Math.floor(t/1000/60/60/24);
        var h=Math.floor(t/1000/60/60%24);
        var m=Math.floor(t/1000/60%60);
        var s=Math.floor(t/1000%60);
        if(s >= 0)
            return (d * 24 + h) + '时' + m + '分' +s+'秒';
    }
    function GetRTime2(){
        var text = GetRTime('<?php echo $end_time; ?>');
        if (text== 0){
            $(".hms").text('活动已结束');
        }else{
            $(".hms").text(text);
        }
    }
    setInterval(GetRTime2,1000);

    /**
     * 继续加载猜您喜欢
     * */
    var before_request = 1; // 上一次请求是否已经有返回来, 有才可以进行下一次请求
    var page = 0;
    function ajax_sourch_submit(){
        if(before_request == 0)// 上一次请求没回来 不进行下一次请求
            return false;
        before_request = 0;
        page++;
        $.ajax({
            type : "get",
            url:"/index.php?m=Mobile&c=Index&a=ajaxGetMore&p="+page,
            success: function(data)
            {
                if(data){
                    $("#J_ItemList>ul").append(data);
                    before_request = 1;
                }else{
                    $('.get_more').hide();
                }
            }
        });
    }
    ajax_sourch_submit();
  //设置推荐人
    set_first_leader();
     
</script>
	</body>
</html>