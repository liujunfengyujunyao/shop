<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:38:"./template/phone/new/machine\edit.html";i:1540274146;s:37:"./template/phone/new/public\tion.html";i:1537152658;}*/ ?>
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

	<h1 style='margin-top:0px'><a class="back" href="javascript:history.back();"  title="返回列表"><i class="fa fa-arrow-circle-o-left" style="font-size: .35rem;"></i></a>&nbsp;&nbsp;编辑机器<small><?php echo $username['user_name']; ?></small></h1>
	<form action="<?php echo U('Phone/machine/edit'); ?>" method="post">
		<input type="hidden" name="machine_id" value="<?php echo $info['machine_id']; ?>">
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">机器名称</span>
			</label>
			<input type="text" name="machine_name" class="form-control" id="machine_name" value="<?php echo $info['machine_name']; ?>">
		</div>
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">详细地址</span>
			</label>
			<input type="text" name="address" class="form-control" id="address" value="<?php echo $info['address']; ?>">
		</div>
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">备注</span>
			</label>
			<input type="text" name="brief" class="form-control" id="brief" value="<?php echo $info['brief']; ?>">
		</div>
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">统一游戏价格</span>
			</label>
			<input type="text" name="game_price" class="form-control" id="game_price" value="<?php echo $info['game_price']; ?>">
		</div>
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">统一游戏赔率</span>
			</label>
			<input type="text" name="odds" class="form-control" id="odds" value="<?php echo $info['odds']; ?>">
		</div>
		<div class="form-group">
			<label for="username" >
				<span>*</span>
				<span class="label label-primary">统一商品价格</span>
			</label>
			<input type="text" name="goods_price" class="form-control" id="goods_price" value="<?php echo $info['goods_price']; ?>">
		</div>
		
		<!--  <dl class="row">
                <dt class="tit">
                    <label><em>*</em>地址</label>
                </dt>
                <dd class="opt">
                    <select name="province" id="province" onChange="get_city(this)">
                        <option value="">请选择</option>
                        <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                            <option <?php if($info['province_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="city" id="city" onChange="get_area(this)">
                        <option value="">请选择</option>
                        <?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                            <option <?php if($info['city_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="district" id="district">
                        <option value="">请选择</option>
                        <?php if(is_array($district) || $district instanceof \think\Collection || $district instanceof \think\Paginator): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                            <option <?php if($info['district_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_district"></span>
                    <p class="notic">地址</p>
                </dd>
            </dl> -->
          
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
<script src="__PUBLIC__/js/global.js"></script>

</html>

  <!-- placeholder="请填写SN号" -->