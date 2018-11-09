<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:37:"./template/phone/new/index\index.html";i:1541671493;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__NEW__/css/common.css">
	<link rel="stylesheet" href="__NEW__/css/index1.css">
	<script src="__NEW__/js/echarts.min.js"></script>
	<title>设备管理系统</title>
</head>
<body>
	<header id="head">
	<a href="<?php echo U('Phone/statistics/index'); ?>">
		<div class="titlebar">
			<div id="tit">
				<img src="__NEW__/img/mine.png" id="me" >
				<p class="title">今日总收益（元）</p>
				<img src="__NEW__/img/message.png" id="msg">
			</div>
			<p class="title_num"><?php echo $data['all_count']; ?></p>
		</div>

		<ul class="foobar">
			<li class="foo_item"><span>在线支付</span><span><?php echo $data['online_count']; ?>元</span></li>
			<li class="foo_item"><span>现金支付</span><span><?php echo $data['offline_count']; ?>元</span></li>
			<li class="foo_item"><span>霸屏收入</span><span>0元</span></li>
		</ul>

		<div class="lastbar">
			<div href="#"><span>礼品消耗</span><!-- <img src="img/gift.png" id="gt" > --><span><?php echo $data['machine_count']; ?>台，<?php echo $data['gift_out_number']; ?>只</span></div>
			<div href="#"><span>出奖率</span><p id="sp1"><?php echo $data['rate']; ?>%</p></div>
		</div>
		</a>
	</header>
	<section id="body">

		<div id="echarts" style="height: 200px;background-color: white;">

		</div>
		<script>
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('echarts'));

            // 指定图表的配置项和数据
//    option = {
//     legend: {
//         data:['钱(k)与天数(天)变化关系']
//     },
//     tooltip: {
//         trigger: 'axis',
//         formatter: "Temperature : <br/>{b}k : {c}天"
//     },
//     grid: {
//         left: '3%',
//         right: '4%',
//         bottom: '3%',
//         containLabel: true
//     },
//     xAxis: {
//         type: 'value',
//         axisLabel: {
//             formatter: '{value} 天'
//         },
				
//     },
//     yAxis: {
//         type: 'category',
//         axisLine: {onZero: false},
//         axisLabel: {
//             formatter: '{value} k'
//         },
//         boundaryGap: false,
//         data: ['0', '10', '15', '20', '25', '30', '35', '40', '45','50',]
//     },
//     series: [
//         {
//             // name: '高度(km)与气温(°C)变化关系',
//             type: 'line',
//             smooth: true,
//             lineStyle: {
//                 normal: {
//                     width: 3,
//                     shadowColor: 'rgba(0,0,0,0.4)',
//                     shadowBlur: 10,
//                     shadowOffsetY: 10
//                 }
//             },
//             data:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
//         }
//     ]
// };
	option = {
	 grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },	
   xAxis: {
        type: 'category',
        // data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        data: <?php echo $checkdate; ?>
    },
    yAxis: {
        type: 'value'
    },
    series: [{
        data: <?php echo $rate; ?>,
        type: 'line'
    }]
};

// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
		</script>
		<!--监控-->
		<div class="control">
			<ul>
				<li id="ctt1"><a href=""><span id="control_txt">正常   <?php echo $data['normal']; ?>台， <?php echo $data['normal_rate']; ?>%      &nbsp; &nbsp; &nbsp;> </span></a></li>
				<li id="ctt2"><a href=""><span id="control_txt">异常   <?php echo $data['fault']; ?>台， <?php echo $data['fault_rate']; ?>%         &nbsp; &nbsp; &nbsp;> </span></li>
			</ul>
		</div>
		<div class="module">
			<div class="long">
				<a href="#">
				<div class="icon fa fa-tachometer">
					<!-- <span class=""></span> -->
				</div>
				<div class="text">
					<div id="txt1">
						<img src="__NEW__/img/device%20management.png" alt="" style="width: 0.3rem;height: 0.3rem; float: left;">
					</div>
					<!-- <img src="img/error.png" style="float: right; width: 0.31rem; height: 0.3rem;" > -->
					<p class="p1">设备管理</p>
					<p class="p2">共<?php echo $data['machine_count']; ?>台，当前在线<?php echo $data['online_machine']; ?>台</p>
				</div>
				<!-- <div class="text2" style="float: right; ">
					<a href="index1.html">4</a>
				</div> -->
				</a>
			</div>
			<ul class="short">
				<li>
					<a href="<?php echo U('Phone/Scan/index'); ?>">
					<div class="icon fa fa-recycle" style="color:#4864ed;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/Equipment%20registration.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1" >设备注册</p>
						</div>
						<div class="p2" >扫描设备二维码</div>
					</div>
					</a>
				</li>
				<li>
					<a href="<?php echo U('Phone/Machine/index'); ?>">
					<div class="icon fa fa-money" style="color:#fe7b13;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/setting.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1" >设备管理</p>
						</div>
						<div class="p2">设备参数修改</div>
					</div>
					</a>
				</li>
				<li>
					<a href="<?php echo U('Phone/Statistics/index'); ?>">
					<div class="icon fa fa-line-chart" style="color:#ff524c;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/Statistics.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1" >统计</p>
						</div>
						<div class="p2">实时收益流水</div>
					</div>
					</a>
				</li>
				<li>
					<a href="#">
					<div class="icon fa fa-gift" style="color:#3d7ce8;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/gift.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1">礼品管理</p>
						</div>
						<div class="p2">礼品统计及管理</div>
					</div>
					</a>
				</li>
			</ul>
		</div>

		<div class="module">
			<ul class="short">
				<li>
					<a href="#">
					<div class="icon fa fa-recycle" style="color:#4864ed;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/Statistics.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1">收益统计</p>
						</div>
						<div class="p2">所有营收统计</div>
					</div>
					</a>
				</li>
				<li>
					<a href="#">
					<div class="icon fa fa-money" style="color:#fe7b13;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/Personnel%20management.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1">人员管理</p>
						</div>
						<div class="p2">编辑管理员信息</div>
					</div>
					</a>
				</li>
				<li>
					<a href="#">
					<div class="icon fa fa-line-chart" style="color:#ff524c;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/Remote.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1">远程上分</p>
						</div>
						<div class="p2">远程为游戏机充值</div>
					</div>
					</a>
				</li>
				<li>
					<a href="#">
					<div class="icon fa fa-gift" style="color:#3d7ce8;"></div>
					<div class="text">
						<div id="txt1">
							<img src="__NEW__/img/binding.png" alt="" style="width: 0.31rem;height: 0.3rem; float: left;">
							<p class="p1">设备绑定</p>
						</div>
					</div>
					</a>
				</li>
			</ul>
		</div>


	</section>
</body>
<script src="__NEW__/js/rem.js"></script>
<script src="__NEW__/js/jquery-2.1.4.min.js"></script>

<!-- <script src="js/bootstrap.min.js"></script> -->
</html>