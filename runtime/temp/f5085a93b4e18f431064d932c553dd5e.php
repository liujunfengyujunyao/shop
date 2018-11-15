<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:38:"./template/phone/new/machine\edit.html";i:1542192280;}*/ ?>
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
		<link rel="stylesheet" type="text/css" href="__NEW__/css/index1.css" />
		<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=sSelQoVi2L3KofLo1HOobonW"></script>
	</head>
	<body>
		<header id="head">
			<div class="top_bar">
				<a href="#" class="fa fa-chevron-left"></a>
				<span class="title">
					</i>设备编辑</span>
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
		</header>
		<div class="device_body">
			<div class="d_body">
				<div id="d_name">
					<span id="name">设备名称：</span>
					<input type="text" value="" id="input1">
				</div>
				<div id="d_goup">
					<span id="goup">所属群组：</span>
					<input type="text" value="" id="input2">
				</div>
				<div id="d_city">
					<span id="city">设备位置:</span>
					<div id="city2">
						<select id="cmbProvince" name="cmbProvince">
							<?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								<option value ="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						<select id="cmbCity" name="cmbCity">
							<?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								<option value ="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						<select id="cmbArea" name="cmbArea">
							<?php if(is_array($district) || $district instanceof \think\Collection || $district instanceof \think\Paginator): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								<option value ="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						<input type="text" id="cmb_d" placeholder="请输入详细地址" />
					</div>
				</div>
				<div id="map">
					<div id="allmap"></div>
				</div>
				<div id="btn_list">
					<input type="button" id="b_l" value="群组列表" />
				</div>
			</div>
		</div>







	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script src="__NEW__/js/index.js"></script>
	<!--头部-->
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>
	<!--省市区三级联动-->
	<script>
		addressInit('cmbProvince', 'cmbCity', 'cmbArea');
	</script>
	<!--地图-->
	<script type="text/javascript">
		// 百度地图API功能
		var map = new BMap.Map("allmap"); // 给allmap设置地图
		map.centerAndZoom(new BMap.Point(116.4035, 39.915), 12); // 第二个参数为级别，数字越大，聚焦越清晰
// 		setTimeout(function() {
// 			map.panTo(new BMap.Point(113.262232, 23.154345)); //两秒后移动到广州
// 		}, 2000);
	</script>
</html>
