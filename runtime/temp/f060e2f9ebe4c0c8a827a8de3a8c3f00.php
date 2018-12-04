<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:38:"./template/phone/new/logina\login.html";i:1543648685;s:37:"./template/phone/new/public\tion.html";i:1537152658;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="__PUBLIC__/css/csss/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__PUBLIC__/css/csss/common.css">
	<link rel="stylesheet" href="__PUBLIC__/css/csss/powerIndex.css">
	<link rel="stylesheet" href="__PUBLIC__/css/csss/bootstrap.min.css">
	<style type="text/css">
		#main>ul>li a {
			display: block;
		}
	</style>
</head>

	<link rel="stylesheet" href="__PUBLIC__/css/csss/login.css">
	<link rel="stylesheet" href="__PUBLIC__/css/csss/alert.css">
<body>
	<header>
		<img src="__PUBLIC__/images/logo.jpg" alt="">
		
	</header>
	<form action="<?php echo U('Phone/Logina/index'); ?>" id="body" method="post">
		<div class="form-group">
			<input type="text" class="main form-control" name="tel">
			<label for="tel">手机号</label>
		</div>
		<div class="form-group">
			<input type="password" class="main form-control"  name="pass">
			<label for="pass">密码</label>
		</div>
		<div class="forget">
			<!-- <a href=<?php echo $link; ?>><img src="__PUBLIC__/images/we_chat.png" alt=""></a> -->
			<a href="<?php echo U('Phone/Register/verify'); ?>" >忘记密码?</a>
		</div>
		

		<button class="btn login" type="submit">
			
		登陆
	</button>
		<a href = "<?php echo U('Phone/Register/index'); ?>"><button type="button" class="btn regist">注册</button></a>
	</form>

	<section id="foot">
		登陆即代表阅读并同意13070132093
		<a href="#">服务条款</a>
	</section>
</body>
<script src="__PUBLIC__/js/js/rem.js"></script>
<script src="__PUBLIC__/js/js/jquery-2.1.4.min.js"></script>
<script src="__PUBLIC__/js/js/alert.js"></script>
<script src="__PUBLIC__/js/js/util.js"></script>
<script>
	function failed(){
		$('.load').remove();
		$('button.login').text('登陆')
		$('button').prop('disabled',false);
		Alert('手机号或密码不正确')
	}
	$('form').submit(function(){
		


		$allInput = $(this).find('input');
		var e = window.event;
		// e.preventDefault();
		var ifValidated = Array.prototype.every.call($allInput, function(input){
			if(input.value == '' || input.value.indexOf(' ') != -1)
			{
				console.log(input.name);
				// switch(input.name){
				// 	case 'tel':
				// 	Alert('手机号不能为空')
				// 	break;
				// 	case 'pass':
				// 	Alert('密码不能为空')
				// 	break;
				}
				e.preventDefault();
			}
			return !(input.value == '' || input.value.indexOf(' ') != -1)
		})

		if(ifValidated) {
				// 设置按钮不可点击
			$('button').prop('disabled',true);
			$('button.login').text('登陆中')
		

			// 加入加载动画元素
			var $spin = $('<span class="load fa fa-spinner fa-spin"></span>')
			$('.login').prepend($spin);
			var $spinWidth = $spin[0].offsetWidth;
			$spin.css({marginLeft: - $spinWidth});
		}

	})
</script>
</html>