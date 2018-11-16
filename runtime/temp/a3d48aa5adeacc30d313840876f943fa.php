<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:46:"./template/phone/new/machine\machine_list.html";i:1542192554;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="__NEW__/css/common.css">
	<link rel="stylesheet" href="__NEW__/css/bootstrap.min.css">
	<link rel="stylesheet" href="__NEW__/css/top_frame.css">
	<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
</head>

<body>
	<header id="head">
		<div class="top_bar">
			<a href="#" class="fa fa-chevron-left"></a>
			<span class="title">
				</i>设备管理</span>
			<a class="no-addon" href="#"></a>
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
		<!-- 搜索 -->
		<div id="find">
			<form name="search" class="search" id="search" action="">
				<div class="search-row">
					<div class="search_box">
						<i class="icon"><img src="__NEW__/img/sousuo.png" ></i>
						<!-- 搜索图标  嫌弃搜索框小调试高度就行-->
						<input type="text" placeholder="搜索">
					</div>
				</div>
			</form>
		</div>
	</header>
	<div class="list">
		<ul id="list_content">
			<?php if(is_array($machine_list) || $machine_list instanceof \think\Collection || $machine_list instanceof \think\Paginator): $i = 0; $__LIST__ = $machine_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
					<li>
						<a href="">
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
<script>
	$('.tog').click(function () {
		$('.slide_bar').slideToggle();
	})
</script>

<script type="text/javascript">
	/* 输入框获取到焦点 表示用户正在输入 */
	$("#word").focusin(function () {
		$(".search-row").addClass("active iconfont icon-sousuo");
	});
	/* 输入框失去焦点 表示用户输入完毕 */
	$("#word").focusout(function () {
		/* 判断用户是否有内容输入 */
		if ($(this).val() == "") {
			/* 没有内容输入 改变样式 */
			$(".search-row").removeClass("active iconfont icon-sousuo");
		} else {
			/* 有内容输入 保持样式 并提交表单 */
			$("#search").submit();
		}
	});
</script>

</html>