<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:38:"./template/phone/new/machine\edit.html";i:1543397095;}*/ ?>

<!DOCTYPE html>
<html lang="en" id="rootHTML">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Document</title>
		
		<link rel="stylesheet" href="__NEW__/css/common.css">
		<!-- <link rel="stylesheet" href="./css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="__NEW__/css/top_frame.css">
		<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
		<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
		<link rel="stylesheet" type="text/css" href="__NEW__/css/index1.css" />
		<!-- <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=zDOttlXWVz6hDfeLsTHWi2Eo"></script> -->
		
		<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=uCfRKGGyT37VtYzkMx5qKY8Wgzlxsoaj"></script>
	</head>
	<body>
		<header id="head">
			<div class="top_bar">
				<a href="<?php echo U('machine/index'); ?>" class="fa fa-chevron-left"></a>
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
		<section class="dd_body" style="padding-bottom:0.8rem;">
			<div class="device_body">
			<form name="ditu" method="post" action="<?php echo U('machine/edit'); ?>">
				<div class="d_body">
					<div id="d_name">
						<span id="name">设备名称：</span>
						<input type="text" id="input1" name="machine_name" placeholder="请输入设备名称" value="<?php echo $info['machine_name']; ?>">
						<input type="hidden" name="machine_id" value="<?php echo $info['machine_id']; ?>">
					</div>
					<div id="d_goup">
						<span id="goup">所属群组：</span>
	                    <select contenteditable="true" name="store">
							<option contenteditable="contenteditable" value="0">请选择(无)</option>		
							<?php if(is_array($store) || $store instanceof \think\Collection || $store instanceof \think\Paginator): $i = 0; $__LIST__ = $store;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								<option contenteditable="contenteditable" name="store" value="<?php echo $v['id']; ?>" <?php if($info['group_id'] == $v['id']): ?>selected<?php endif; ?>><?php echo $v['group_name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
					<div id="d_goup">
						<!-- 当前位置：<input  type="text"   id="txtposition" value="正在定位..."/> -->
						<div class="sd" style="display: none;">
							<span id="goup">手动设备位置:</span>
							<input type="text" id="suggestId" value="" placeholder="手动输入" /><br />
							<input type="radio" class="now" value="值" name="名称" />使用手动输入位置
						</div>
						<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>

						<div class="zd">
							<span id="" style="color:#333333;font-size: 0.2rem;">当前位置:</span>
							<input type="text" name="address" style="width: 1.8rem; border: 0px;outline:none;cursor: pointer;" id="txtposition" value="正在定位..." readonly="readonly"/><br />
							<input type="checkbox" class="now" value="1" name="is_group" />同步群组定价
							<input type="text" name="detail_address" style="width: 1.8rem; border: 0px;outline:none;cursor: pointer;margin-top: 0.1rem;margin-left: 5%;" id="txtposition" value="" placeholder="补全位置" /><br />
							
						</div>

					</div>
					<fieldset class="demo_content" style="height:2.8rem;width: 85%; margin:0 auto;margin-top: 0.2rem;margin-bottom: 0.2rem;">
						<div style=" min-height: 2.5rem; margin-top: 0.05rem; width: 100%;height:100%;" id="allmap">
						</div>
					</fieldset>
					<!-- <div class="demo_main">
						<fieldset class="demo_content">
							<div>
								<select name="province"></select>
								<select name="city"></select>
								<select name="county"></select>

							</div>
							<div style=" min-height: 2.8rem; margin-top: 0.05rem; width: 100%;" id="map">
							</div>
						</fieldset>

					</div> -->
					<div id="btn_list">
						<input type="submit" id="b_l" value="保存" />
					</div>
				</div>
			</form>
			</div>
		</section>

	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/index.js"></script>
	<!-- <script src="js/area.js"></script> -->
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<!-- <script src="js/demo.js"></script> -->
	<!--头部-->
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>
	<script type="text/javascript">
		// 百度地图API功能
		var map = new BMap.Map("allmap");
		var point = new BMap.Point(118.778, 32.05);
		map.centerAndZoom(point, 12);
		map.enableScrollWheelZoom(); // 启用滚轮放大缩小
		map.addControl(new BMap.NavigationControl()); // 启用放大缩小 尺
		var geolocation = new BMap.Geolocation();
		//自动定位
		geolocation.getCurrentPosition(function(r) {
			console.log(r.point);
			if (this.getStatus() == BMAP_STATUS_SUCCESS) {
				var mk = new BMap.Marker(r.point);
				map.addOverlay(mk); //标出所在地
				map.panTo(r.point); //地图中心移动
				mk.addEventListener("dragend", showInfo);
				mk.enableDragging(); //可拖拽
				var point = new BMap.Point(r.point.lng, r.point.lat); //用所定位的经纬度查找所在地省市街道等信息
				var gc = new BMap.Geocoder();
				gc.getLocation(point, function(rs) {
					console.log(rs);

					var addComp = rs.addressComponents;
					console.log(rs.address); //地址信息
					document.getElementById("txtposition").value = rs.address;
					// alert(rs.address);//弹出所在地址
					var label = new BMap.Label(rs.address, {
						offset: new BMap.Size(20, -10)
					});
					map.removeOverlay(mk.getLabel()); //删除之前的label

					mk.setLabel(label);
				});

				function showInfo(e) {
					var gc = new BMap.Geocoder();
					gc.getLocation(e.point, function(rs) {
						var addComp = rs.addressComponents;
						var address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber; //获取地址
						document.getElementById("txtposition").value = rs.address;
						//画图 ---》显示地址信息
						var label = new BMap.Label(address, {
							offset: new BMap.Size(20, -10)
						});
						map.removeOverlay(mk.getLabel()); //删除之前的label

						mk.setLabel(label);

					});
				}
			} else {
				alert('failed' + this.getStatus());
			}
		}, {
			enableHighAccuracy: true
		})

		// 百度地图API功能
		function G(id) {

			return document.getElementById(id)
		}

		var ac = new BMap.Autocomplete( //建立一个自动完成的对象
			{
				"input": "suggestId",
				"location": map
			});

		ac.addEventListener("onhighlight", function(e) { //鼠标放在下拉列表上的事件
			var str = "";
			var _value = e.fromitem.value;
			var value = "";
			if (e.fromitem.index > -1) {
				value = _value.province + _value.city + _value.district + _value.street + _value.business;
			}
			str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

			value = "";
			if (e.toitem.index > -1) {
				_value = e.toitem.value;
				value = _value.province + _value.city + _value.district + _value.street + _value.business;
			}
			str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
			G("searchResultPanel").innerHTML = str;
		});

		var myValue;
		ac.addEventListener("onconfirm", function(e) { //鼠标点击下拉列表后的事件
			var _value = e.item.value;
			myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
			G("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

			setPlace();
		});

		function setPlace() {
			map.clearOverlays(); //清除地图上所有覆盖物
			function myFun() {
				var pp = local.getResults().getPoi(0).point; //获取第一个智能搜索的结果
				map.centerAndZoom(pp, 18);
				map.addOverlay(new BMap.Marker(pp)); //添加标注

			}
			var local = new BMap.LocalSearch(map, { //智能搜索
				onSearchComplete: myFun
			});
			local.search(myValue);
		}

		if ($('.now').is(":checked")) {
			$('#suggestId').attr("disabled", true);
		} else {
			$('#suggestId').attr("disabled", false);
		}
	</script>
	<!-- <script type="text/javascript">
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
				//向地图中添加覆盖物(红点)
				map.addControl(ctrlNav);
				var marker = new BMap.Marker(point); //创建标注
				map.addOverlay(marker); //方法addOverlay() 向地图中添加覆盖物
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
	</script> -->

</html>
