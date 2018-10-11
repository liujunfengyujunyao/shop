<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./template/phone/new/register\register.html";i:1538981971;s:37:"./template/phone/new/public\tion.html";i:1537152658;}*/ ?>
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

	<link rel="stylesheet" href="__PUBLIC__/css/csss/alert.css">
	<link rel="stylesheet" href="__PUBLIC__/css/csss/regist.css">
<body>
	<section id="body">
		<form action="<?php echo U('Phone/register/add'); ?>" novalidate method="post">
			<div class="form-group">
				<input type="text" class="form-control" name="name" pattern="^([\u4e00-\u9fa5]){2,7}$">
				<label for="name">姓名</label>
			</div>
			<div class="indent">请填写真实姓名以便审核</div>
			<div class="form-group">
				<input type="password" class="form-control pass" name="pass" pattern="^[0-9A-Za-z]{6,20}$">
				<label for="pass">密码</label>
			</div>
			<div class="form-group">
				<input type="password" class="form-control passQ" name="passQ" >
				<label for="passQ">确认密码</label>
			</div>
	
			<div class="form-group" style="margin-top: .2rem;">
				<input type="text" class="form-control tel" name="tel" pattern="^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$">
				<label for="tel">手机号</label>
			</div>
			<div class="form-group">
				<input type="text" class="form-control yanzheng" name="validate">
				<button class="btn validate" type="button">获取验证码</button>
				<label for="validate">验证码</label>
			</div>

			<div class="form-group" style="margin-top: .3rem;">
				<button class="btn" type="submit">注册</button>
			</div>

		</form>
	</section>
</body>
<script src="__PUBLIC__/js/js/rem.js"></script>
<script src="__PUBLIC__/js/js/jquery-2.1.4.min.js"></script>
<script src="__PUBLIC__/js/js/alert.js"></script>
<script src="__PUBLIC__/js/js/util.js"></script>
<script>

	var count = 59;
	$('.validate').click(function(){
				
					var telEl = $('.tel')[0];
						if(!new RegExp(telEl.pattern).test(telEl.value))
						{
							Alert('请输入合法手机号码');
							return;
						}

					var tel = $("input[name='tel']").val();
						// alert(123);
						// setTimeout(function(){
							$.ajax({
								url:"<?php echo U('Phone/Register/index'); ?>",
								type:"post",
								data:{phone:tel},
								success:function(data){
									console.log(data);
									if(data.error_code ==  1){
										alert("手机已注册请登录");
									}else if(data.error_code ==  2){
										alert('验证码已发送');
										var $el = $('.validate');
										$el.prop('disabled',true);
										$el.text(count + 's');
										var int = setInterval(function(){
											$el.text(--count+'s')
											if(count == 0)
											{
												clearInterval(int);
												$el.prop('disabled',false);
												$el.text('重新获取')
												count = 59;
											}
										}, 1000)

									}else if(data.error_code == 3){

											alert('验证码发送失败');
									}
								},
								error:function(){
									alert('注册失败，重新注册');
								}
							})
						// }, 1500)
						
		

		console.log('hhhh')
		
	})

$("form").submit(function(){
	var $allInput = $(this).find('input');
	var e = window.event;
	// e.preventDefault();
	// $allInput.each(function(index,input){
	// 	console.log(input);
	// 	if(input.pattern)
	// 	{
	// 		if(!input.pattern.test(input.value))
	// 		{
	// 			Alert('')
	// 		}

	// 	}
	// })
	var ifValidate = Array.prototype.every.call($allInput,function(input){
		// console.log(item);
		if(input.pattern)
		{
			// console.log(new RegExp(input.pattern).test(input.value));
			if(!new RegExp(input.pattern).test(input.value))
			{
				console.log(input.name)
				switch(input.name){
					case 'name':
					Alert('请输入正确的真实姓名');
					break;
					case 'pass':
					Alert('请输入6-20位之间的字母或数字');
					break;
					case 'tel':
					Alert('请输入合法手机号码');
					break;
				}
				e.preventDefault();
			}
		}
		return new RegExp(input.pattern).test(input.value);
	})
	if(!ifValidate) return;
	if($('.pass').val() != $('.passQ').val())
	{
		Alert('两次输入密码不一致');
		e.preventDefault();
	}
	console.log($('.passQ').val().indexOf(' '))
	if($('.passQ').val() == '' || $('.passQ').val().indexOf(' ') != -1)
	{
		Alert('确认密码不能为空或包含空格');
		e.preventDefault();
	}
	if($('.yanzheng').val() == '' || $('.yanzheng').val().indexOf(' ') != -1)
	{
		Alert('验证码不能为空');
		e.preventDefault();
	}
	// alert('over')
})

</script>
</html>