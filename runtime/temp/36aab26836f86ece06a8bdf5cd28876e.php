<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:34:"./template/phone/new/scan\add.html";i:1542854387;s:37:"./template/phone/new/public\tion.html";i:1540012757;}*/ ?>
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

<link rel="stylesheet" href="__PUBLIC__/css/csss/power_edit.css">
<body>

	<h1 style='margin-top:0px'><a class="back" href="javascript:history.back();"  title="返回列表"><i class="fa fa-arrow-circle-o-left" style="font-size: .35rem;"></i></a>&nbsp;&nbsp;绑定设备<small><?php echo $username['user_name']; ?></small></h1>
	<form action="<?php echo U('Phone/Scan/add'); ?>" method="post">
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">UUID</span>
			</label>
			<input type="text" name="sn" class="form-control" id="username"  placeholder="请填写SN号">
		</div>
		<div class="form-group">
			<label for="username" >
				<span></span>
				<span class="label label-primary">机台名称</span>
			</label>
			<input type="text" name="machine_name" class="form-control" id="username"  placeholder="机台名称">
		</div>
	<!-- 	<div class="form-group">
			<label for="role" >
				<span>*</span>
				<span class="label label-primary">机台类型</span>
			</label>
			<select name="type_id" id="role" class="form-control">
			<option value="0">请选择类型</option>
			<?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			<option value="<?php echo $v['type_id']; ?>"><?php echo $v['type_name']; ?></option>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</div> -->
		<button class="btn btn-primary" type="submit">提交</button>
	</form>
</body>
<script src="__PUBLIC__/js/js/rem.js"></script>

</html>