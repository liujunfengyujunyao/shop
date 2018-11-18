<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./template/phone/new/statistics\detail.html";i:1542192554;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Document</title>
		<link rel="stylesheet" href="__NEW__/css/common.css">
		<link rel="stylesheet" href="__NEW__/css/top_frame.css">
		<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
		<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
		<link rel="stylesheet" type="text/css" href="__NEW__/css/index1.css" />

	</head>
	<body>
		<header id="head">
			<div class="top_bar">
				<a href="#" class="fa fa-chevron-left" id="upper"></a>
				<span id="t_time"><?php echo $date; ?></span>
				<span id="t_money">合计<?php echo $data['all_count']; ?>(元)</span>
				<span id="t_jl"><a href="<?php echo U('Phone/Statistics/list_index'); ?>">历史记录</a></span> 
				<!-- <span class="title">账单详情</span>
				<p id="hertory"><a href="">历史记录</a> </p>
				<a class="no-addon" href="#"></a> -->
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
		<div class="money_body">
			<!-- <div class="m_title">
				<span id="m_t1">今日总营收600.00</span>
			</div> -->
			<!--图表-->
			<div id="ysecharts" style="height: 250px;background-color: #F3F3F3; margin-top: 0.05rem;"></div>
			<div class="m_content">
				<div class="m_c_left">
					<div id="m_c_f1">礼品消耗&nbsp;<?php echo $data['gift_out_count']; ?>个</div>
					<HR align=center width=90% color=#D5D5D5 SIZE=2>
					<div id="m_c_f1">卖出商品&nbsp;<?php echo $data['goods_out_count']; ?>个</div>
				</div>
				<div id="m_line" style="width: 0.1%; height: 0.8rem;background-color: #d5d5d5d; float: left;"></div>
				<div class="m_c_right">
					<div id="m_c_r1">出奖率</div>
					<div id="m_c_r2"><?php echo $data['rate']; ?>%</div>
				</div>
			</div>
			<div class="m_c_bottom">
				
			</div>
			<div class="m_b1">
				<div id="m_yx">
					游戏运行<span id="m_number">&nbsp;<?php echo $data['game_count']; ?>&nbsp;</span>次
				</div>
				<div id="m_cs">
					失败<span id="fail"><?php echo $data['fail_number']; ?></span>次
					成功<span id="success"><?php echo $data['success_number']; ?></span>次
				</div>
			</div>
		</div>
		<div class="m_cjl">
			<div id="m_c">
				<span id="cjl1">中奖趋势</span>
				<!-- <span id="cjl2">最近7天</span> -->
			</div>
			<!-- <div id="m_time">
					<input type="" name="" id="" value="" />
				</div> -->
		</div>
		<div id="echarts" style="height: 130px;background-color: white;"></div>
		<!-- <div id="m_nmb">
			<span id="m_nmb2">出奖比率</span>
		</div> -->
		</div>







	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script src="__NEW__/js/index.js"></script>
	<script src="__NEW__/js/echarts.min.js"></script>
	<!--头部-->
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		});
		$('#upper').click(function(){
	history.go(-1);
})
	</script>
	<!--图表-->
	<script>
		// 基于准备好的dom，初始化echarts实例
		var myChart = echarts.init(document.getElementById('ysecharts'));

		// 指定图表的配置项和数据
		option = {
			tooltip: {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			series: [{
				name: '支付内容',
				type: 'pie',
				radius: '80%',
				center: ['50%', '50%'],
				data: [{
						value: <?php echo $data['weixinpay_game_count']; ?>,
						name: '微信游戏',
						itemStyle: {
							color: '#A1DBB5',
						}
					},
					{
						value: <?php echo $data['weixinpay_goods_count']; ?>,
						name: '微信商品',
						itemStyle: {
							color: '#21974B',
						}

					},
					{
						value: <?php echo $data['alipay_game_count']; ?>,
						name: '支付宝游戏',
						itemStyle: {
							color: '#92C6D4',
						}
					},
					{
						value: <?php echo $data['alipay_goods_count']; ?>,
						name: '支付宝商品',
						itemStyle: {
							color: '#1B8AA8',
						}
					},
					{
						value: <?php echo $data['money_count']; ?>,
						name: '现金收入',
						itemStyle: {
							color: '#C33531',
						}
					},
					// {
					// 	value: <?php echo $data['money_goods']; ?>,
					// 	name: '现金商品',
					// 	itemStyle: {
					// 		color: '#DE908E',
					// 	}
					// }
				],
			}]
		};
		// 使用刚指定的配置项和数据显示图表。
		myChart.setOption(option);
	</script>

			<script>
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('echarts'));
			option = {
			 grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '4%',
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
</html>
