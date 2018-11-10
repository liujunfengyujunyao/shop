<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:35:"./template/phone/new/msg\index.html";i:1541828819;}*/ ?>
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
				<span class="title">消息通知</span>
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
		<section class="ifm_content">
			<div class="ifm_cont">
				<ul>
					<?php if(is_array($msg) || $msg instanceof \think\Collection || $msg instanceof \think\Paginator): if( count($msg)==0 ) : echo "" ;else: foreach($msg as $k=>$v): ?>
					<li>
						<?php echo $v; ?>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				<!-- 	<li>
						
					</li>
					<li>
						
					</li>
					<li>
						
					</li> -->
				</ul>
			</div>
		</section>


	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script>
		<!--头部-->
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		});
		$('#upper').click(function(){
			history.back();
		})
	</script>
</html>
