<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:45:"./template/pc/rainbow/partner\apply_info.html";i:1533876251;s:38:"./template/pc/rainbow/user\header.html";i:1534215376;s:36:"./template/pc/rainbow/user\menu.html";i:1534751022;s:46:"./template/pc/rainbow/public\footer_index.html";i:1534215384;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>补货申请详情-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
<meta name="keywords" content="<?php echo $tpshop_config['shop_info_store_keyword']; ?>" />
<meta name="description" content="<?php echo $tpshop_config['shop_info_store_desc']; ?>" />
<link rel="stylesheet" type="text/css" href="__STATIC__/css/tpshop.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/css/myaccount.css" />
<link rel="shortcut  icon" type="image/x-icon" href="__STATIC__/images/favicon.ico" media="screen"  />
<script src="__STATIC__/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
<style>
.com-topyue .wacheng {width: 14%}
.com-topyue .wacheng2 {width: 83%; padding: 30px 5px;}
.liuchaar {margin-top: 40px;}
.order-alone-li th {background: #f9f9f9; height: 40px;}
.order-alone-li tr {border-bottom: 1px solid #f5f5f5;}
</style>
</head>

<body class="bg-f5">
<script src="__PUBLIC__/js/global.js" type="text/javascript"></script>
<link rel="stylesheet" href="__STATIC__/css/location.css" type="text/css"><!-- 收货地址，物流运费 -->
<script src="__PUBLIC__/static/js/layer/layer.js" type="text/javascript"></script>
<style>
	.list1 li{float:left;}
</style>
<div class="top-hander home-index-top p">
	<div class="w1224 pr">
		<div class="fl">
			<?php if(!(empty($user) || (($user instanceof \think\Collection || $user instanceof \think\Paginator ) && $user->isEmpty()))): ?>
			<div class="fl ler">
				<a href="<?php echo U('Home/User/index'); ?>"><?php echo $user['nickname']; ?></a>
			</div>
			<div class="fl ler">
				<a href="<?php echo U('Home/User/message_notice'); ?>">
					消息<?php if($user_message_count > 0): ?>（<span class="red"> <?php echo $user_message_count; ?> </span>）<?php endif; ?>
				</a>
			</div>
			<div class="fl ler">
				<a href="<?php echo U('Home/User/logout'); ?>">退出</a>
			</div>
			<?php else: ?>
			<div class="fl ler">
		        <a class="red" href="<?php echo U('Home/user/login'); ?>">登录</a>
		        <span class="spacer"></span>
		        <a href="<?php echo U('Home/user/reg'); ?>">注册</a>
		    </div>
			<?php endif; ?>
			<div class="fl spc"></div>
			<div class="sendaddress pr fl">
				<!-- 收货地址，物流运费 -start-->
				<ul class="list1">
					<li class="jaj"><span>配&nbsp;&nbsp;送：</span></li>
					<li class="summary-stock though-line" style="margin-top:2px">
						<div class="dd" style="border-right:0px;">
							<div class="store-selector add_cj_p">
								<div class="text" style="width: 150px;background: inherit;top: 2px;border-left: 0"><div></div><b style="top: 2px"></b></div>
								<div onclick="$(this).parent().removeClass('hover')" class="close"></div>
							</div>
						</div>
					</li>
				</ul>
				<!--<i class="jt-x"></i>-->
				<!-- 收货地址，物流运费 -end-->
				<!--<span>深圳<i class="jt-x"></i></span>-->
			</div>
		</div>
		<div class="top-ri-header fr">
			<ul>
				<li><a href="<?php echo U('/Home/Order/order_list'); ?>">我的订单</a></li>
				<li class="spacer"></li>
				<li><a href="<?php echo U('/Home/User/visit_log'); ?>">我的浏览</a></li>
				<li class="spacer"></li>
				<li><a href="<?php echo U('Home/User/goods_collect'); ?>">我的收藏</a></li>
				<li class="spacer"></li>
				<li><a title="点击这里给我发消息" href="tencent://message/?uin=<?php echo $tpshop_config['shop_info_qq']; ?>&amp;Site=后台中心&amp;Menu=yes" target="_blank">客户服务</a></li>
				<!--<li class="spacer"></li>-->
				<!--<li class="hover-ba-navdh">
					<div class="nav-dh">
						<span>网站导航</span>
						<i class="jt-x"></i>
						<div class="conta-hv-nav">
							<ul>
								<li>
									<a href="<?php echo U('/Home/Activity/group_list'); ?>">团购</a>
								</li>
								<li>
									<a href="<?php echo U('Home/Activity/flash_sale_list'); ?>">抢购</a>
								</li>
							</ul>
						</div>
					</div>
				</li>-->
			</ul>
		</div>
	</div>
</div>
<div class="nav-middan-z p home-index-head">
	<div class="header w1224">
		<div class="ecsc-logo">
			<a href="/" class="logo"> <img src="__STATIC__/images/logo2.png" height="69"></a>
		</div>
		<div class="m-index">
			<a href="<?php echo U('Home/User/index'); ?>" class="index">我的商城</a>
			<a href="/" class="home">返回商城首页</a>
		</div>
		<div class="m-navitems">
			<ul class="fixed p">
				<li><a href="<?php echo U('Home/Index/index'); ?>">首页</a></li>
				<li>
					<div class="u-dl">
						<div class="u-dt">
							<span>账户设置</span>
							<i></i>
						</div>
						<div class="u-dd">
							<a href="<?php echo U('Home/User/info'); ?>">个人信息</a>
							<!--<a href="<?php echo U('Home/User/safety_settings'); ?>">安全设置</a>-->
							<a href="<?php echo U('Home/User/address_list'); ?>">收货地址</a>
						</div>
					</div>
				</li>
				<li class="u-msg"><a class="J-umsg" href="<?php echo U('Home/User/message_notice'); ?>">消息<span><?php if($user_message_count > 0): ?><?php echo $user_message_count; endif; ?></span></a></li>
				<li><a href="<?php echo U('Home/Goods/integralMall'); ?>">积分商城</a></li>
				<li class="search_li">
				   <form id="sourch_form" id="sourch_form" method="post" action="<?php echo U('Home/Goods/search'); ?>">
		           	<input class="search_usercenter_text" name="q" id="q" type="text" value="<?php echo \think\Request::instance()->param('q'); ?>"/>
		           	<a class="search_usercenter_btn" href="javascript:;" onclick="if($.trim($('#q').val()) != '') $('#sourch_form').submit();">搜索</a>
		           </form>
		        </li>
			</ul>
		</div>
		<div class="shopingcar-index fr">
			<div class="u-g-cart fr fixed" id="hd-my-cart">
				<a href="<?php echo U('Home/Cart/cart'); ?>">
					<p class="c-n fl">我的购物车</p>

					<p class="c-num fl">(<span class="count cart_quantity" id="cart_quantity">0</span>)
						<i class="i-c oh"></i>
					</p>
				</a>

				<div class="u-fn-cart u-mn-cart" id="show_minicart">
					<div class="minicartContent" id="minicart">
					</div>
					<!--有商品时-e-->
				</div>
			</div>
		</div>
	</div>
</div>
<script src="__STATIC__/js/common.js"></script>
<!--------收货地址，物流运费-开始-------------->
<script src="__STATIC__/js/location.js"></script>
<!--------收货地址，物流运费--结束-------------->

<div class="home-index-middle">
	<div class="w1224">
		<div class="g-crumbs">
			<?php if(is_array($navigate_user) || $navigate_user instanceof \think\Collection || $navigate_user instanceof \think\Paginator): $i = 0; $__LIST__ = $navigate_user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<a href="<?php echo $vo; ?>"><?php echo $key; ?></a><?php if($i != count($navigate_user)): ?><i class="litt-xyb"></i><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="home-main">
			<div class="le-menu fl">
	<div class="menu-ul">
		<?php if($user['level'] == 9): ?>
		<ul>
			<li class="ma"><i class="account-acc6"></i>机器管理</li>
			<li><a href="<?php echo U('Home/Store/order_list'); ?>">订单</a></li>
			<li><a href="<?php echo U('Home/Store/return_goods_list'); ?>">退换货管理</a></li>
			<li><a href="<?php echo U('Home/Store/store_stock_list'); ?>">贩卖机库存</a></li>
			<li><a href="<?php echo U('Home/Store/stock_log'); ?>">库存日志</a></li>
			<li><a href="<?php echo U('Home/Store/apply'); ?>">补货申请记录</a></li>
			<li><a href="<?php echo U('Home/Store/delivery'); ?>">发货单</a></li>
		</ul>
		<ul>
			<li class="ma"><i class="account-acc6"></i>会员管理</li>
			<li><a href="<?php echo U('Home/Order/lower_list'); ?>">店会员</a></li>
			<!-- <li><a href="<?php echo U('Home/Order/income'); ?>">我的收益</a></li> -->
		</ul>
		<?php endif; ?>
		<ul>
			<li class="ma"><i class="account-acc1"></i>交易中心</li>
			<li><a href="<?php echo U('Home/Order/order_list'); ?>">我的订单</a></li>
			<!--<li><a href="">我的预售</a></li>-->
			<li><a href="<?php echo U('Home/Order/comment'); ?>">我的评价</a></li>
		</ul>
		<ul>
			<li class="ma"><i class="account-acc2"></i>资产中心</li>
			<li><a href="<?php echo U('Home/User/coupon'); ?>">我的优惠券</a></li>
			<!--<li><a href="">我的购物卡</a></li>-->
			<li><a href="<?php echo U('Home/User/recharge'); ?>">账户余额</a></li>
			<li><a href="<?php echo U('Home/User/account'); ?>">我的积分</a></li>
			<!--<li><a href="">积分换券明细</a></li>-->
		</ul>
		<ul>
			<li class="ma"><i class="account-acc3"></i>关注中心</li>
			<li><a href="<?php echo U('Home/User/goods_collect'); ?>">我的收藏</a></li>
			<!--<li><a href="">曾经购买</a></li>-->
			<li><a href="<?php echo U('Home/User/visit_log'); ?>">我的足迹</a></li>
		</ul>
		<ul>
			<li class="ma"><i class="account-acc4"></i>个人中心</li>
			<li><a href="<?php echo U('Home/User/info'); ?>">个人信息</a></li>
			<li><a href="<?php echo U('Home/User/bind_auth'); ?>">账号绑定</a></li>
			<li><a href="<?php echo U('Home/User/address_list'); ?>">地址管理</a></li>
			<li><a href="<?php echo U('Home/User/safety_settings'); ?>">安全设置</a></li>
		</ul>
		<ul>
			<li class="ma"><i class="account-acc5"></i>服务中心</li>
			<!--<li><a href="">我的发票</a></li>-->
			<li><a href="<?php echo U('Home/Order/return_goods_list'); ?>">退货管理</a></li>
		</ul>

		<?php if($user['level'] == 8): ?> <!-- 用户等级为合伙人 -->
		<ul>
			<li class="ma"><i class="account-acc6"></i>工厂店管理</li>
			<li><a href="<?php echo U('Home/Partner/storeList'); ?>">工厂店列表</a></li>
			<li><a href="<?php echo U('Home/Partner/orderList'); ?>">工厂店订单</a></li>
			<li><a href="<?php echo U('Home/Partner/store_apply'); ?>">工厂店补货申请</a></li>
			<li><a href="<?php echo U('Home/Partner/delivery'); ?>">配货单</a></li>
		</ul>
		<ul>
			<li class="ma"><i class="account-acc7"></i>库存管理</li>
			<li><a href="<?php echo U('Home/Partner/stockList'); ?>">库存列表</a></li>
			<li><a href="<?php echo U('Home/Partner/stockLog'); ?>">库存日志</a></li>
			<li><a href="<?php echo U('Home/Partner/apply'); ?>">补货申请</a></li>
			<li><a href="<?php echo U('Home/Partner/invoiceList'); ?>">发货单</a></li>
		</ul>
		<?php endif; ?>
	</div>
</div>
			<div class="ri-menu fr">
				<div class="menumain p">
					<div class="com-topyue">
						<div class="wacheng fl">
							<?php if($info['status'] == 0): ?>
								<h3 style="font: 700 16px/34px 'Microsoft YaHei';color: #e4393c; padding-top:65px;">等待管理员审核</h3>
							<?php endif; if(($info['status'] == 1) AND (empty($info['confirm_time']))): ?>
								<p class="ddn1"><span>物流名称：</span><span><?php echo $info['express_name']; ?></span></p>
								<p class="ddn1"><span>物流单号：</span><span><?php echo $info['express_code']; ?></span></p>
								<a class="ddn3" style="margin-top:20px;" href="javascript:;" onclick="order_confirm(<?php echo $info['delivery_id']; ?>)">确认收货</a>
							<?php endif; if(($info['status'] == 1) AND ($info['confirm_time'])): ?>
								<p class="ddn1"><span>物流名称：</span><span><?php echo $info['express_name']; ?></span></p>
								<p class="ddn1"><span>物流单号：</span><span><?php echo $info['express_code']; ?></span></p>
								<h3 class="ddn2">已完成</h3>
							<?php endif; if($info['status'] == 2): ?>
								<h3 style="font: 700 24px/34px 'Microsoft YaHei';color: #e4393c; padding-top:20px;">申请失败</h3>
							<?php endif; ?>
						</div>

						<div class="wacheng2 fr">
							<div class="liuchaar p">
								<ul>
									<li>
										<div class="aloinfe">
											<i class="y-comp"></i>
											<div class="ddfon">
												<p>提交申请</p>
												<p><?php echo date('Y-m-d',$info['addtime']); ?></p>
												<p><?php echo date('H:i:s',$info['addtime']); ?></p>
											</div>
										</div>
									</li>
									<li><i class='y-comp91 <?php if($info[status] != 1): ?>top322<?php endif; ?>'></i></li>
									<li>
										<div class="aloinfe fime1">
											<i class='y-comp2 <?php if($info[status] != 1): ?>lef64<?php endif; ?>'></i>
											<div class="ddfon">
												<p>审核通过</p>
												<?php if($info['status'] == 1): ?>
												<p><?php echo date('Y-m-d',$info['edittime']); ?></p>
												<p><?php echo date('H:i:s',$info['edittime']); ?></p>
												<?php endif; ?>
											</div>
										</div>
									</li>
									<li><i class='y-comp91 <?php if(empty($info[express_code]) == true): ?>top322<?php endif; ?>'></i></li>
									<li>
										<div class="aloinfe fime2">
											<i class='y-comp3 <?php if(empty($info[express_code]) == true): ?>lef64<?php endif; ?>'></i>
											<div class="ddfon">
												<p>管理员发货</p>
												<?php if($info[express_code]): ?>
													<p><?php echo $info['delivery_time']; ?></p>
												<?php endif; ?>
											</div>
										</div>
									</li>
									<li><i class='y-comp91 <?php if(empty($info[express_code]) == true): ?>top322<?php endif; ?>'></i></li>
									<li>
										<div class="aloinfe fime3">
											<i class='y-comp4 <?php if(empty($info[express_code]) == true): ?>lef64<?php endif; ?>'></i>
											<div class="ddfon">
												<p>等待收货</p>
												<?php if($info[delivery_time] > 0): ?>
													<p><?php echo $info['delivery_time']; ?></p>
												<?php endif; ?>
											</div>
										</div>
									</li>
									<li><i class='y-comp91 <?php if(empty($info[confirm_time]) == true): ?>top322<?php endif; ?>'></i></li>
									<li>
										<div class="aloinfe fime4">
											<i class='y-comp5 <?php if(empty($info[confirm_time]) == true): ?>lef64<?php endif; ?>'></i>
											<div class="ddfon">
												<p>完成</p>
												<?php if($info[confirm_time]): ?>
													<p><?php echo $info['confirm_time']; ?></p>
												<?php endif; ?>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="beenovercom">
						<div class="shoptist">
							<span><?php echo $tpshop_config['shop_info_store_name']; ?><a href="tencent://message/?uin=<?php echo $tpshop_config['shop_info_qq']; ?>&Site=<?php echo $tpshop_config['shop_info_store_name']; ?>&Menu=yes" target="_blank"><i class="y-comp9"></i></a></span>
						</div>
						<div class="order-alone-li">
							<table width="100%">
								<thead>
									<tr>
										<th>商品名称</th>
										<th>商品编号</th>
										<th>数量</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
									<tr class="conten_or">
										<td class="sx1">
											<div class="shop-if-dif">
												<div class="shop-difimg">
													<a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$vo['goods_id'])); ?>"><img src="<?php echo goods_thum_images($vo['goods_id'],78,78); ?>"></a>
												</div>
												<div class="cebigeze">
													<div class="shop_name"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$vo['goods_id'])); ?>"><?php echo $vo['goods_name']; ?></a></div>
													<p class="mayxl"><?php echo $vo['spec_key_name']; ?></p>
												</div>
											</div>
										</td>
										<td class="sx2"><?php echo $vo['goods_sn']; ?></td>
										<td class="sx3">
											<span><?php echo $vo['goods_num']; ?></span>
										</td>
									</tr>
									<?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--footer-s-->
<div class="footer p">
	<!-- <div class="mod_service_inner">
        <div class="w1224">
            <ul>
                <li>
                    <div class="mod_service_unit">
                        <h5 class="mod_service_duo">多</h5>
                        <p>品类齐全，轻松购物</p>
                    </div>
                </li>
                <li>
                    <div class="mod_service_unit">
                        <h5 class="mod_service_kuai">快</h5>
                        <p>多仓直发，极速配送</p>
                    </div>
                </li>
                <li>
                    <div class="mod_service_unit">
                        <h5 class="mod_service_hao">好</h5>
                        <p>正品行货，精致服务</p>
                    </div>
                </li>
                <li>
                    <div class="mod_service_unit">
                        <h5 class="mod_service_sheng">省</h5>
                        <p>天天低价，畅选无忧</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
<div class="w1224">
    <div class="footer-ewmcode">
        <div class="foot-list-fl">
            <?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article_cat` where parent_id = 2");
                                $result_name = $sql_result_v = S("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\Db::query("select * from `__PREFIX__article_cat` where parent_id = 2"); 
                                    S("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
                <ul>
                    <li class="foot-th">
                        <?php echo $v[cat_name]; ?>
                    </li>
                    <?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article` where cat_id = $v[cat_id]  and is_open=1");
                                $result_name = $sql_result_v2 = S("sql_".$md5_key);
                                if(empty($sql_result_v2))
                                {                            
                                    $result_name = $sql_result_v2 = \think\Db::query("select * from `__PREFIX__article` where cat_id = $v[cat_id]  and is_open=1"); 
                                    S("sql_".$md5_key,$sql_result_v2,1);
                                }    
                              foreach($sql_result_v2 as $k2=>$v2): ?>
                        <li>
                            <a href="<?php echo U('Home/Article/detail',array('article_id'=>$v2[article_id])); ?>"><?php echo $v2[title]; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
        <div class="QRcode-fr">
            <ul>
                <li class="foot-th">客户端</li>
                <li><img src="__STATIC__/images/qrcode1.jpg"/></li>
            </ul>
            <ul>
                <li class="foot-th">微信</li>
                <li><img src="__STATIC__/images/qrcode2.jpg"/></li>
            </ul>
        </div>
    </div>
    <div class="mod_copyright p">
        <div class="grid-top">
            <?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article` where cat_id = 5 and is_open=1");
                                $result_name = $sql_result_v = S("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\Db::query("select * from `__PREFIX__article` where cat_id = 5 and is_open=1"); 
                                    S("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
                <a href="<?php echo U('Home/Article/detail',array('article_id'=>$v[article_id])); ?>"><?php echo $v[title]; ?></a>
                <span>|</span>
            <?php endforeach; ?>
            <a href="javascript:void (0);">客服热线:<?php echo $tpshop_config['shop_info_phone']; ?></a>
        </div>
        <p>Copyright © 2016-2025 <?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:'TPshop商城'); ?> 版权所有 保留一切权利 备案号:<?php echo $tpshop_config['shop_info_record_no']; ?></p>

        <p class="mod_copyright_auth">
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_1" href="" target="_blank">经营性网站备案中心</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_2" href="" target="_blank">可信网站信用评估</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_3" href="" target="_blank">网络警察提醒你</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_4" href="" target="_blank">诚信网站</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_5" href="" target="_blank">中国互联网举报中心</a>
            <a class="mod_copyright_auth_ico mod_copyright_auth_ico_6" href="" target="_blank">网络举报APP下载</a>
        </p>
    </div>
</div> -->
<div class="footer p">
    <div class="w1224">
        <div class="footer-ewmcode">
            <div class="foot-list-fl">
                <?php if($link): ?>
                    <ul>
                        <li class="foot-th">友情链接</li>
                        <?php if(is_array($link) || $link instanceof \think\Collection || $link instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($link) ? array_slice($link,0,6, true) : $link->slice(0,6, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a href="<?php echo $item['link_url']; ?>" <?php if($item['target'] == 1): ?>target="_blank"<?php endif; ?>><?php echo $item['link_name']; ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php if(count($link) > 6): ?>
                    <ul>
                        <li class="foot-th">&nbsp;</li>
                        <?php if(is_array($link) || $link instanceof \think\Collection || $link instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($link) ? array_slice($link,6,6, true) : $link->slice(6,6, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a href="<?php echo $item['link_url']; ?>" <?php if($item['target'] == 1): ?>target="_blank"<?php endif; ?>><?php echo $item['link_name']; ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php endif; endif; 
                                   
                                $md5_key = md5("select * from `__PREFIX__article_cat` where parent_id = 2");
                                $result_name = $sql_result_v = S("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\Db::query("select * from `__PREFIX__article_cat` where parent_id = 2"); 
                                    S("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
                    <ul>
                        <?php if($v['show_in_nav'] == 1): ?>
                        <li class="foot-th">
                            <?php echo $v[cat_name]; ?>
                        </li>
                        <?php
                                   
                                $md5_key = md5("select * from `__PREFIX__article` where cat_id = $v[cat_id]  and is_open=1");
                                $result_name = $sql_result_v2 = S("sql_".$md5_key);
                                if(empty($sql_result_v2))
                                {                            
                                    $result_name = $sql_result_v2 = \think\Db::query("select * from `__PREFIX__article` where cat_id = $v[cat_id]  and is_open=1"); 
                                    S("sql_".$md5_key,$sql_result_v2,1);
                                }    
                              foreach($sql_result_v2 as $k2=>$v2): ?>
                            <li>
                                <?php if($v2['link']): ?>
                                    <a href="<?php echo $v2['link']; ?>"><?php echo $v2[title]; ?></a>
                                <?php else: ?>
                                    <a href="<?php echo U('Home/Article/detail',array('article_id'=>$v2[article_id])); ?>"><?php echo $v2[title]; ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                <?php endforeach; ?>
                <ul class="foot-con continue">
                    <li class="foot-th">联系我们</dt>
                    <li>
                        <span class="cellphone_con"><?php echo $tpshop_config['shop_info_phone']; ?></span>
                        <span class="time_con">周一至周日8:00-18:00</span>
                        <span class="cost_con">（仅收市话费）</span>
                        <a class="software_con" href="tencent://message/?uin=<?php echo $tpshop_config['shop_info_qq2']; ?>&amp;Site=后台中心&amp;Menu=yes" target="_blank">
                            <img src="__STATIC__/images/continue.png"/>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mod_copyright p">
            <p>Copyright © 2016-2025 <?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:'后台中心'); ?> 版权所有 保留一切权利 备案号:<?php echo $tpshop_config['shop_info_record_no']; ?></p>
        
            <p class="mod_copyright_auth">
                <a class="mod_copyright_auth_ico mod_copyright_auth_ico_1" href="javascript:void(0)">经营性网站备案中心</a>
                <!-- <a class="mod_copyright_auth_ico mod_copyright_auth_ico_2" href="" target="_blank">可信网站信用评估</a>
                <a class="mod_copyright_auth_ico mod_copyright_auth_ico_3" href="" target="_blank">网络警察提醒你</a>
                <a class="mod_copyright_auth_ico mod_copyright_auth_ico_4" href="" target="_blank">诚信网站</a>
                <a class="mod_copyright_auth_ico mod_copyright_auth_ico_5" href="" target="_blank">中国互联网举报中心</a>
                <a class="mod_copyright_auth_ico mod_copyright_auth_ico_6" href="" target="_blank">网络举报APP下载</a> -->
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?6ab0b9cb68d35fe3b6c45d2930dcc8f2";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</div>
<!--footer-e-->
</body>
</html>
<script>
//确定收货
function order_confirm(id){
	layer.confirm("你确定收到货了吗?",{
		btn:['确定','取消']
	},function(){
		$.ajax({
			type : 'post',
			url : '/index.php?m=Home&c=Partner&a=invoice_confirm&id='+id,
			dataType : 'json',
			success : function(data){
				if(data.status == 1){
					showErrorMsg(data.msg);
					location.reload();
				}else{
					showErrorMsg(data.msg);
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				showErrorMsg('网络失败，请刷新页面后重试');
			}
		})
	}, function(index){
		layer.close(index);
	});
}
</script>
