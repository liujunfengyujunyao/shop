<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:36:"./template/phone/new/scan\index.html";i:1541741167;}*/ ?>
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
				<a href="#" class="fa fa-chevron-left"></a>
				<span class="title">设备绑定</span>
				<a class="<n></n>o-addon" href="#"></a>
			</div>
			<ul class="slide_bar">
				<li>
					<a href="#">
						<span class="fa fa-home"></span>
						<!-- <span>首页</span> -->
					</a>
				</li>
				<li>
					<a href="#">
						<span class="fa fa-user"></span>
						<!-- <span>我的</span> -->
					</a>
				</li>
			</ul>
		</header>
		<section class="ib_content">
			<ul>
				<li>
					<span id="ib_c1"><a href="">二维码绑定设备</a></span>
				</li>
				<li>
					<input type="text" name="" id="ib_c2" value="" placeholder="手动输入SN绑定设备"/>
				</li>
			</ul>
			<div style="height: 40px;"></div>
			<div class="addStoreBtn1">
				确定绑定  
			</div>
		</section>

		
		
	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script>
		<!--头部-->
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>
</html>
