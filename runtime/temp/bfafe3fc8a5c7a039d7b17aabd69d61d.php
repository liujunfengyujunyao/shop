<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:39:"./template/phone/new/machine\index.html";i:1542270496;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Document</title>
		<link rel="stylesheet" href="__NEW__/css/common.css">
		<link rel="stylesheet" href="__NEW__/css/bootstrap.css">
		<link rel="stylesheet" href="__NEW__/css/top_frame.css">
		<link rel="stylesheet" href="__NEW__/css/fontawesome/font-awesome.min.css">
		<link rel="stylesheet" href="__NEW__/css/iconfont.css" />
	</head>

	<body>
		<header id="head">
			<div class="top_bar">
				<a href="#" class="fa fa-chevron-left"></a>
				<span class="title">
					</i>设备管理</span>
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
			<!-- 搜索 -->
			<div id="find">
				<form name="search" class="search" id="search" action="">
					<div class="search-row">
						<div class="search_box">
							<i class="icon"><img src="__NEW__/img/sousuo.png"></i>
							<!-- 搜索图标  嫌弃搜索框小调试高度就行-->
							<input type="text" placeholder="搜索">
						</div>
					</div>
				</form>
			</div>
		</header>
		
		<div>
		<?php if(is_array($machine) || $machine instanceof \think\Collection || $machine instanceof \think\Paginator): if( count($machine)==0 ) : echo "" ;else: foreach($machine as $k=>$v): ?>
		<div class="list">
		
			<ul id="list_content">
			
				<li>
					<a href="" role="text" data-toggle="modal" data-target="#myModal" class="huoqu">
						<p class="list_p">
							<span id="sp1"><?php echo $v['machine_name']; ?></span>
							<span id="sp2"><?php echo $v['address']; ?></span>
						</p>
						<p class="list_p">
							<span id="sp3"><?php echo $v['priority']; ?></span>
							<!-- <span id="sp4">平台策略</span> -->
						</p>
					</a>
					<hr width="90%" color="#333333" size="1" align="center">
					<div id="huo">
						<div class="sb_show">显示详情信息</div>
						<div class="sb_hide">
							<div id="s_h">
								<table class="table">
									<th><?php echo $v['machine_name']; ?></th>
									<th><?php echo $v['is_online']; ?> </th>
									<tr>
										<td>设备策略</td>
										<td><?php echo $v['priority']; ?></td>
									</tr>
									<tr>
										<td>管理员</td>
										<td><?php echo $v['user_name']; ?></td>
									</tr>
									<tr>
										<td>设备地点</td>
										<td><?php echo $v['address']; ?></td>
									</tr>
									<tr>
										<td>设备编号</td>
										<td class="bianhao"><?php echo $v['machine_id']; ?></td>
									</tr>
									<tr>
										<td>编辑日期</td>
										<td><?php echo $v['addtime']; ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</li>
			
				
			</ul>
			
		</div>



		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">

				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">选择操作类型<?php echo $v['machine_name']; ?></h4>
					</div>
					<div class="modal-body">
						<form action="#">
							<a href="<?php echo U('Phone/machine/test',array('id'=>$v['machine_id'])); ?>">
								<div class="form-group">
									<label for="addname">设备编辑</label>
								</div>
							</a>
							<a href="<?php echo U('Phone/machine/test',array('id'=>$v['machine_id'])); ?>">
								<div class="form-group">
									<label for="addpassword">设备配置</label>
								</div>
							</a>
							<a href="<?php echo U('Phone/machine/test',array('id'=>$v['machine_id'])); ?>">
								<div class="form-group">
									<label for="addpassword1">经营日志</label>
								</div>
							</a>
							<a href="<?php echo U('Phone/machine/test',array('id'=>$v['machine_id'])); ?>">
								<div class="form-group">
									<label for="addemail">解绑设备</label>
								</div>
							</a>
							<a href="<?php echo U('Phone/machine/test',array('id'=>$v['machine_id'])); ?>">
								<div class="form-group">
									<label for="addyonghuzu">设备库存</label>
								</div>
							</a>




						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						<!-- <button type="button" class="btn btn-primary">提交</button> -->
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; ?>
</div>

	</body>
	<script src="__NEW__/js/rem.js"></script>
	<script src="__NEW__/js/jquery-2.1.4.min.js"></script>
	<script src="__NEW__/js/bootstrap.js"></script>
	<script>
		$('.tog').click(function() {
			$('.slide_bar').slideToggle();
		})
	</script>

	<script type="text/javascript">
		/* 输入框获取到焦点 表示用户正在输入 */
		$("#word").focusin(function() {
			$(".search-row").addClass("active iconfont icon-sousuo");
		});
		/* 输入框失去焦点 表示用户输入完毕 */
		$("#word").focusout(function() {
			/* 判断用户是否有内容输入 */
			if ($(this).val() == "") {
				/* 没有内容输入 改变样式 */
				$(".search-row").removeClass("active iconfont icon-sousuo");
			} else {
				/* 有内容输入 保持样式 并提交表单 */
				$("#search").submit();
			}
		});

		/*显示隐藏*/
		$(document).ready(function() {
			$(".sb_show").click(function() {
				$(this).siblings(".sb_hide").slideToggle("slow");
			});
		});

		$("#list_content li a").click(function(e) {
			e.preventDefault();
			var $bianhao = $(this).parents("li").find(".bianhao").text();
			console.log("编号为:" + $bianhao)
		})
	</script>

</html>
