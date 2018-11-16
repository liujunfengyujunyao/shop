<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:36:"./template/phone/new/scan\index.html";i:1542001771;}*/ ?>
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
		<link rel="stylesheet" href="__PUBLIC__/css/csss/powerIndex.css">
		<link rel="stylesheet" href="__PUBLIC__/css/csss/alert.css">

		<link rel="stylesheet" href="//at.alicdn.com/t/font_626784_0j006ef09vff.css" />
		<script src="https://cdn.staticfile.org/jquery/1.11.2/jquery.min.js"></script>
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
			<div id="result"></div><!-- 有用 -->
			<ul>
				<li>
					<span id="">
						<a href="http://sao315.com/w/api/saoyisao?redirect_uri=">扫一扫</a>
					</span>
				</li>
				<li>
					<!-- <input type="text" name="" id="" value="" placeholder="手动输入SN绑定设备"/> -->
					<button class="btn"><a href="<?php echo U('Scan/add'); ?>"><i class="fa fa-plus"></i>手动添加机台</a></button>
				</li>
				<li>

					<button class="btn"><i class="fa fa-pencil-square-o"></i><a href="<?php echo U('Index/index'); ?>">返回首頁</a></button>
				</li>
			</ul>
			<div style="height: 40px;"></div>
			<div class="addStoreBtn1">
				<a href="" id="adb">确定绑定 </a>
			</div>
		</section>



	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script>
		<!--头部-->
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>
	<script src="__PUBLIC__/js/js/page_power.js"></script>
	<!-- <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> -->
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="__PUBLIC__/js/jquery.min.js"></script>
	<script src="__PUBLIC__/js/js/alert.js"></script>
	<script src="__PUBLIC__/js/js/util.js"></script>

	<script type="text/javascript">
		var qr = getQueryString("qrresult"); //获取带参数查询,post方法传的参数获取不到
		// console.log(qr);
		// alert(qr);



		if (qr) {

			// $("#result").html(qr);
			var sendMsg = confirm(qr);
			if (sendMsg) {
				// alert('yes');
				var data = {
					sn: qr
				};
				try {
					// $.post('{U:("phone/Scan/index")}' , data, function(e) {
					//     alert(e);
					// })
					$.ajax({
						url: "<?php echo U('Scan/index'); ?>",
						type: 'POST',
						data: data,
						success: function(e) {
							if (e.error_code == 1) {
								alert('SN号不存在');
							} else if (e.error_code == 2) {
								alert('机台已删除');
							} else if (e.error_code == 3) {
								alert('机台已注册');
							} else if (e.error_code == 4) {
								alert('机台注册成功');
							}
						},
						// error: function(e){
						//     alert('error');
						//     // $('#inner').text(e);

						//     // console.log(e);
						// }
					})
				} catch (e) {
					alert(e);
				}

			} else {
				alert('no');
			}
			var wl = qr.split("CODE_128,");
			if (wl.length == 2) {
				// window.open("http://192.168.1.145/phone" + wl[1]);
				// window.open("http://192.168.1.145/s?wd=" + wl[1]);
			} else {
				// console.log(qr);
				// alert("您扫描的不是快递单号！所以无法为您查询物流。请对准快递单条形码进行扫描！");
			}
		}

		function getQueryString(name) {
			var reg = new RegExp("\\b" + name + "=([^&]*)");
			var r = location.href.match(reg);
			if (r != null) return unescape(r[1]);
		}
	</script>
</html>
