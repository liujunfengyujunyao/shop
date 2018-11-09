<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./template/phone/new/machine\add_score.html";i:1541734243;}*/ ?>
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
				<span class="title">远程上分</span>
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
		<section class="up_number">
			<div class="un1">
				<div id="un1_name">
					<span id="<?php echo $machine['machine_id']; ?>"><?php echo $machine['machine_name']; ?></span>
				</div>
				<input type="text" name="" id="un1_money" value="" />
			</div>
			<ul class="checkbox">
				<li class="checkbox_item">1元</li>
				<li class="checkbox_item">5元</li>
				<li class="checkbox_item">10元</li>
				<li class="checkbox_item">20元</li>
				<li class="checkbox_item">50元</li>
				<li class="checkbox_item">100元</li>
				<b class="cle"></b>
			</ul>
			<div class="affirmBtn">确认上分</div>
		</section>
	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	
	<script>
		<!--头部-->
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
		<!--上分-->
		$(".checkbox_item").click(function() {
			$("#un1_money").val(parseInt($(this).text()));
		})
		$(".affirmBtn").click(function() {
			$.ajax({
            	type: 'post',
            	url: "<?php echo U('machine/add_score'); ?>",
            	data: {machine_id:<?php echo $machine['machine_id']; ?>,amount:$("#un1_money").val()},
            	dataType: 'json',
            	success: function(res){
            		if(res.status == 1){
            			alert("上分成功");
            		}else{
            			alert("上分失败");
            		}
            	}
            })
		})
	</script>
</html>