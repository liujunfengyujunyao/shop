<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:39:"./template/phone/new/machine\index.html";i:1537180880;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="__STATIC__/css/new/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/common.css">
	<link rel="stylesheet" href="__STATIC__/css/new/machine_list.css">
	<link rel="stylesheet" href="__STATIC__/css/new/bootstrap.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/util.css">
</head>
<body>
	<header id="head">

		<h3>
			<span class="fa fa-list-alt"></span>
			贩卖机列表<small>(共2条记录)</small></h2>
	</header>
	<section id="body" class="table-responsive">
		<table class="table table-bordered">
					<thead>
						<tr class="title">
							<td> <span class="fa fa-star" style="color: #666;"></span> </td>
							<td>ID</td>
							<td>贩卖机名称</td>
							<!-- <td>配货员</td> -->
							<!-- <td>类型</td> -->
							<!-- <td>联系方式</td> -->
							<td>操作</td>
						</tr>
						<!-- <tr class="title2">
							<td colspan="5">
								<span><i class="fa fa-plus"></i>添加管理员</span>
							</td>
							
						</tr> -->
					</thead>
					<tbody>
					<?php if(is_array($machine) || $machine instanceof \think\Collection || $machine instanceof \think\Paginator): $i = 0; $__LIST__ = $machine;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<tr>
							<td><span class="fa fa-star"></span></td>
							<td><?php echo $v['machine_id']; ?></td>
							<td><?php echo $v['machine_name']; ?></td>
							<!-- <td>某某配货员</td> -->
							<!-- <td>测试</td> -->
							<!-- <td>1366666666</td> -->
							<td>
								<a href="<?php echo U('Phone/Machine/goods_list',array('machine_id'=>$v['machine_id'])); ?>"><i class="fa fa-edit"></i>配置</a>
								<a href="#"><i class="fa fa-edit"></i>编辑</a>
								<span><i class="fa fa-trash"></i>删除</span>
							</td>
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
	</section>
</body>
<script src="__STATIC__/js/new/rem.js"></script>
<script src="__STATIC__/js/new/jquery-2.1.4.min.js"></script>
<script>
	$('table tbody td').click(function(){
		$activer = $(this).parent();
		$(this).parent().addClass('tableActive').siblings().removeClass('tableActive');
	})
</script>
</html>