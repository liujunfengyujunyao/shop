<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:47:"./template/phone/new/statistics\list_index.html";i:1542001771;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Document</title>
		<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
		<link rel="stylesheet" href="__NEW__/css/util.css">
		<link rel="stylesheet" href="__NEW__/css/top_frame.css">
		<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
		<link rel="stylesheet" type="text/css" href="__NEW__/css/index1.css" />
	</head>
	<body>
		<header id="head">
			<div class="top_bar">
				<a href="#" class="fa fa-chevron-left" id="upper"></a>
				<span class="title">收益统计</span>
				<a class="<n></n>o-addon" href="#"><img src="__NEW__/img/mine.png" style="width: 0.2rem; height: 0.2rem;"></a>
			</div>
		</header>
		<section class="sytj">
			<ul class="sy_list">
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$v): ?>
				<li>
					<div id="sy_time1">
						<span id="time1"><?php echo date('Y-m-d',$v['statistics_date']); ?></span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥<?php echo $v['count']; ?></div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="<?php echo U('Phone/Statistics/detail',array('statistics_date'=>$v[statistics_date])); ?>" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="__NEW__/img/rightjt.png" >
							</a>
						</div>
					</div>
				</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
				<!-- <li>
					<div id="sy_time1">
						<span id="time1">星期一 下午12:15</span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥200.00</div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="img/rightjt.png" >
							</a>
						</div>
					</div>
				</li>
				<li>
					<div id="sy_time1">
						<span id="time1">星期一 下午12:15</span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥200.00</div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="img/rightjt.png" >
							</a>
						</div>
					</div>
				</li>
				<li>
					<div id="sy_time1">
						<span id="time1">星期一 下午12:15</span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥200.00</div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="img/rightjt.png" >
							</a>
						</div>
					</div>
				</li>
				<li>
					<div id="sy_time1">
						<span id="time1">星期一 下午12:15</span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥200.00</div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="img/rightjt.png" >
							</a>
						</div>
					</div>
				</li>
				<li>
					<div id="sy_time1">
						<span id="time1">星期一 下午12:15</span>
					</div>
					<div id="sy_content1">
						<div id="sy_c1">收入金额</div>
						<div id="sy_c2">￥200.00</div>
						<HR align=center width=90% color=#A5A5A5 SIZE=1>
						<div id="sy_3">
							<a href="" id="sy_31">
								<span id="sy_31_txt">查看收益详情</span>
								<img id="sy_31_pic" src="img/rightjt.png" >
							</a>
						</div>
					</div>
				</li> -->
			</ul>
		</section>

	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<!--头部-->
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		});
		$('#upper').click(function(){
			history.go(-1);
		})
	</script>
</html>
