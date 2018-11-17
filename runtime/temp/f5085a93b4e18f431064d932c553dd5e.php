<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:38:"./template/phone/new/machine\edit.html";i:1542356345;}*/ ?>
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
		<link rel="stylesheet" type="text/css" href="css/index1.css" />
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
					<input type="text" value="" id="input1" placeholder="请输入设备名称">
				</div>
				<div id="d_goup">
					<span id="goup">所属群组：</span>
					<input type="text" value="" id="input2" placeholder="请输入所属群组">
				</div>
				<div id="d_goup">
					<span id="goup">设备位置:</span>
					<input type="text" value="" id="input3" placeholder="请输入设备位置">
				</div>

				<div class="demo_main">
					<fieldset class="demo_content">
						<div>
							<select name="province"></select>
							<select name="city"></select>
							<select name="county"></select>
						</div>
						<div style=" min-height: 2.8rem; margin-top: 0.05rem; width: 100%;" id="map">
						</div>
					</fieldset>

				</div>
				<a href="<?php echo U('group/store_list'); ?>">
				<div id="btn_list">
					<input type="button" id="b_l" value="群组列表" />
				</div>
				</a>
			</div>
		</div>


	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/index.js"></script>
	<script src="__NEW__/js/area.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script src="__NEW__/js/demo.js"></script>
	<!--头部-->
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>
	<script type="text/javascript">
		//异步调用百度js  
		function map_load() {
			var load = document.createElement("script");
			load.src = "http://api.map.baidu.com/api?v=2.0&ak=sSelQoVi2L3KofLo1HOobonW";
			document.body.appendChild(load);
		}
		window.onload = map_load;


		//根据经纬度显示地区
		function loadPlace(longitude, latitude, level) {
			if (parseFloat(longitude) > 0 || parseFloat(latitude) > 0) {
				level = level || 13;
				//绘制地图
				var map = new BMap.Map("map"); // 创建Map实例  
				var point = new BMap.Point(longitude, latitude); //地图中心点 
				map.centerAndZoom(point, level); // 初始化地图,设置中心点坐标和地图级别。  
				map.enableScrollWheelZoom(true); //启用滚轮放大缩小  
				//向地图中添加缩放控件  
				var ctrlNav = new window.BMap.NavigationControl({
					anchor: BMAP_ANCHOR_TOP_LEFT,
					type: BMAP_NAVIGATION_CONTROL_LARGE
				});
				map.addControl(ctrlNav);

				//向地图中添加缩略图控件  
				var ctrlOve = new window.BMap.OverviewMapControl({
					anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
					isOpen: 1
				});
				map.addControl(ctrlOve);

				//向地图中添加比例尺控件  
				var ctrlSca = new window.BMap.ScaleControl({
					anchor: BMAP_ANCHOR_BOTTOM_LEFT
				});
				map.addControl(ctrlSca);

			}
		}
	</script>
</html>
