<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:47:"./template/phone/new/machine\addscore_list.html";i:1542767433;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Document</title>
		<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
		<link rel="stylesheet" href="__NEW__/css/common.css">
		<link rel="stylesheet" href="__NEW__/css/bootstrap.min.css">
		<link rel="stylesheet" href="__NEW__/css/util.css">
		<link rel="stylesheet" href="__NEW__/css/top_frame.css">
		<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
		<link rel="stylesheet" type="text/css" href="__NEW__/css/index1.css" />
	</head>
	<body>
		<header id="head">
			<div class="top_bar">
				<a href="#" class="fa fa-chevron-left" id="upper"></a>
				<span class="title">上分机器列表</span>
				<a class="<n></n>o-addon" href="#"></a>
			</div>
			<ul class="slide_bar">
				<li>
					<a href="<?php echo U('Phone/index/index'); ?>">
						<span class="fa fa-home"></span>
						<!-- <span>首页</span> -->
					</a>
				</li>
				<li>
					<a href="<?php echo U('Phone/machine/mine'); ?>">
						<span class="fa fa-user"></span>
						<!-- <span>我的</span> -->
					</a>
				</li>
			</ul>
			<!-- 搜索 -->
			<div id="find">
				<form name="search" class="search" id="search" action="">
					<div class="search-row">
						<div class="search_box">
							<i class="icon"><img src="__NEW__/img/sousuo.png" ></i>
							<!-- 搜索图标  嫌弃搜索框小调试高度就行-->
							<input type="text" name="" id="" value="" placeholder="搜索"/>
						</div>
					</div>
				</form>
			</div>
		</header>
		<div class="list">
				<ul id="list_content">
				<?php if(is_array($machine_list) || $machine_list instanceof \think\Collection || $machine_list instanceof \think\Paginator): $i = 0; $__LIST__ = $machine_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
					<li>
						<a href="<?php echo U('machine/add_score','machine_id='.$val['machine_id']); ?>">
							<p class="list_p">
								<span id="sp1"><?php echo $val['machine_name']; ?></span>
								<span id="sp2"><?php echo $val['address']; ?></span>
							</p>
							<p class="list_p">
								<span id="sp3"><?php echo $val['is_online']; ?></span>
								<span id="sp4"><?php echo $val['priority']; ?></span>
							</p>
						</a>
					</li>
				<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		
	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script>
		<!--头部-->
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
		$('#upper').click(function(){
			history.back();
		})
	</script>
</html>
