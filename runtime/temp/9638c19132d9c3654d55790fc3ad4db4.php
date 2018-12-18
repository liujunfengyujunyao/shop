<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:35:"./template/phone/new/msg\index.html";i:1545101343;}*/ ?>
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
		<a href="<?php echo U('Phone/index/index'); ?>" class="fa fa-chevron-left"></a>
		<span class="title">消息通知</span>
		<a class="<n></n>o-addon" href="#"></a>
	</div>
	<ul class="slide_bar">
		<li>
			<a href="<?php echo U('Phone/index/index'); ?>">
				<span class="fa fa-home"></span>
				<!-- <span>首页</span> -->
			</a>
		</li>
		<li>
			<a href="<?php echo U('Phone/machine/mine'); ?>">
				<span class="fa fa-user"></span>
				<!-- <span>我的</span> -->
			</a>
		</li>
	</ul>
</header>
<section class="ifm_content">
	<div class="ifm_cont">
		<ul>
			<?php if(is_array($msg) || $msg instanceof \think\Collection || $msg instanceof \think\Paginator): if( count($msg)==0 ) : echo "" ;else: foreach($msg as $key=>$v): if($v['errid']==2): ?>
					<li class="do">
						<a href="<?php echo U('Phone/Machine/stock_list'); ?>">
							<div id="if_pic1">
								<img src="__NEW__/img/ui16.png" id="">
							</div>
							<div id="if_ct1">
								<?php echo $v['errtype']; ?>
							</div>
							<div id="if_ct2" style="width:75px;">
								<?php echo $v['machine_name']; ?>
							</div>
							<div id="if_ct2" style="width:90px;">
								<?php if(strlen($v['errmsg']) < 13): ?>
									<?php echo $v['errmsg']; else: ?>
									<p style="line-height: 0.24rem;"><?php echo $v['errmsg']; ?></p>
								<?php endif; ?>
							</div>
							<div id="if_ct2" style="width:75px;">
								<?php echo date('m-d',$v['time']); ?>
							</div>

							<div id="if_pic2">
								<!-- <img src="__NEW__/img/rightjt.png"> -->
								<?php if($v['status'] == 0): ?>
									<img src="__NEW__/img/error.png" style="width: 0.1rem;height: 0.1rem; margin-right: 1%;" class="dot">
								<?php endif; ?>
							</div>
						</a>
					</li>
					<?php else: ?>
					<li class="do">
						<div id="if_pic1">
							<img src="__NEW__/img/ui16.png" id="">
						</div>
						<div id="if_ct1">
							<?php echo $v['errtype']; ?>
						</div>
						<div id="if_ct2" style="width:75px;">
							<?php echo $v['machine_name']; ?>
						</div>
						<div id="if_ct2" style="width:90px;">
							<?php if(strlen($v['errmsg']) < 13): ?>
								<?php echo $v['errmsg']; else: ?>
								<p style="line-height: 0.24rem;"><?php echo $v['errmsg']; ?></p>
							<?php endif; ?>
						</div>
						<div id="if_ct2" style="width:75px;">
							<?php echo date('m-d',$v['time']); ?>
						</div>

						<div id="if_pic2">
							<!-- <img src="__NEW__/img/rightjt.png"> -->
							<?php if($v['status'] == 0): ?>
								<img src="__NEW__/img/error.png" style="width: 0.1rem;height: 0.1rem; margin-right: 1%;" class="dot">
							<?php endif; ?>
						</div>
					</li>
				<?php endif; endforeach; endif; else: echo "" ;endif; ?>
			<!-- <li>
                <a href="">
                    <div id="if_pic1">
                        <img src="__NEW__/img/ui15.png" id="">
                    </div>
                    <div id="if_ct1">
                        <span id="">离线</span>
                    </div>
                    <div id="if_ct2">
                        <span id="">口红机1号</span>
                        <span id="">2018.10.02已离线</span>
                    </div>
                    <div id="if_pic2">
                        <img src="__NEW__/img/rightjt.png">
                    </div>
                </a>
            </li>
            <li>
                <a href="">
                    <div id="if_pic1">
                        <img src="__NEW__/img/ui16.png" id="">
                    </div>
                    <div id="if_ct1">
                        <span id="">库存</span>
                    </div>
                    <div id="if_ct2">
                        <span id="">口红机1号</span>
                        <span id="">剩余库存500件</span>
                    </div>
                    <div id="if_pic2">
                        <img src="__NEW__/img/rightjt.png">
                    </div>
                </a>
            </li>
            <li>
                <a href="">
                    <div id="if_pic1">
                        <img src="__NEW__/img/ui15.png" id="">
                    </div>
                    <div id="if_ct1">
                        <span id="">离线</span>
                    </div>
                    <div id="if_ct2">
                        <span id="">口红机1号</span>
                        <span id="">2018.10.02</span>
                    </div>
                    <div id="if_pic2">
                        <img src="__NEW__/img/rightjt.png">
                    </div>
                </a>
            </li> -->
		</ul>
	</div>
</section>


</body>
<script src="__NEW__/js/rem.js"></script>
<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
<script>
    <!--头部-->
    $('.tog').click(function() {
        $('.slide_bar').slideToggle();
    });
    $('.do').click(function(){
        $(this).find('.dot').hide();
    });
</script>
</html>
