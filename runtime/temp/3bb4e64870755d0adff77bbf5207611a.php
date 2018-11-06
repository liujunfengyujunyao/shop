<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./template/phone/new/goods\stock_index.html";i:1541405079;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="__STATIC__/css/new/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/common.css">
	<link rel="stylesheet" href="__STATIC__/css/new/bootstrap.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/index2.css">
	<link rel="stylesheet" href="__STATIC__/css/new/util.css">
	<link rel="stylesheet" href="__STATIC__/css/new/top_frame.css">
	<title>Document</title>
</head>
<body>
	<header id="head">

		<!-- <h3>
			<span class="fa fa-cubes"></span>
			贩卖机库存列表<small>(共<?php echo $count; ?>条记录)</small></h2> -->
	<div class="top_bar">
			<a href="#" class="fa fa-chevron-left" id="upper"></a>
			<span class="title">贩卖机库存列表<small>(共<?php echo $count; ?>条记录)</small></span>
			<span class="tog fa fa-list"></span>
		</div>
		<ul class="slide_bar">
			<li><a href="<?php echo U('Phone/Index/index'); ?>">
				<span class="fa fa-home"></span>
				<span>首页</span>
			</a></li>
			<li><a href="#">
				<span class="fa fa-search"></span>
				<span>分类</span>
			</a></li>
			<li><a href="#">
				<span class="fa fa-shopping-cart"></span>
				<span>购物车</span>
			</a></li>
			<li><a href="<?php echo U('Phone/Index/index'); ?>">
				<span class="fa fa-user"></span>
				<span>我的</span>
			</a></li>
		</ul>
	
	</header>
	<section id="body">
		<ul class="main list-group">
			<?php if(is_array($machine) || $machine instanceof \think\Collection || $machine instanceof \think\Paginator): $i = 0; $__LIST__ = $machine;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
			<li class="list-group-item toggler"
					role='button'
					data-open="false"
					data-toggle="collapse"
					data-target="#<?php echo $val['machine_id']; ?>"
					aria-expanded="false"
					aria-controls="<?php echo $val['machine_id']; ?>"
			>
				<span class="title textFlow">
					<i class="fa fa-toggle-right"></i>
					<span class="label label-primary"><?php echo $val['machine_name']; ?></span>
				</span>

				<span class="title textFlow">
					<span class="label label-default"><?php echo $val['address']; ?></span>
				</span>

					<button id=" replenishment" class="btn btn-success" data-url="<?php echo U('room/operate_room'); ?>" data-id="<?php echo $val['machine_id']; ?>" onClick="supply(this)">一键补货</button>

					<button id="clear" class="btn btn-danger" data-url="<?php echo U('Goods/ajax_clear'); ?>" data-id="<?php echo $val['machine_id']; ?>" onClick="supply2(this)">一键清货</button>

				<!-- 折叠部分 -->
				
			</li>
		
			<li class="col_holder collapse table-responsive" id="<?php echo $val['machine_id']; ?>">
				<table class="table table-bordered" >
					<thead>
						<tr>
							<td>
								<span class="fa fa-star"></span>
							</td>
							<td>ID</td>
							<td>位置</td>
							<td>商品名称</td>
							<td>当前库存/最大库存量</td>
							<td>更新时间</td>
						</tr>
					</thead>
					<tbody>
					<?php if(is_array($val['goods']) || $val['goods'] instanceof \think\Collection || $val['goods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $val['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<tr>
							<td>	<span class="fa fa-star"></span></td>
							<td><?php echo $key+1; ?></td>
							<td><?php echo $v['location']; ?></td>
							<?php if($v['goods_price'] == 0): ?>
							<td style="color:red">未配置</td>
							<td style="color:red">0/0</td>
							<?php else: ?>
							<td><?php echo $v['goods_name']; ?></td>
							<td><?php echo $v['goods_num']; ?>/<?php echo $v['stock_num']; ?></td>
							<?php endif; ?>
							
							<td><?php echo $v['edittime']; ?></td>
						</tr>
						<!-- <tr>
							<td>	<span class="fa fa-star"></span></td>
							<td>A2</td>
							<td>玩具熊</td>
							<td>{}/{}</td>
							<td>无</td>
						</tr> -->
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
				</li>

				<?php endforeach; endif; else: echo "" ;endif; ?>
<!-- 
				<li class="list-group-item toggler"
					role='button'
					data-open="false"
					data-toggle="collapse"
					data-target="#innn2"
					aria-expanded="false"
					aria-controls="innn2"
			>
				<span class="title textFlow">
					<i class="fa fa-toggle-right"></i>
					<span class="label label-primary">二号机器</span>
				</span>

				<span class="title textFlow">
					<span class="label label-default">北京市东城区</span>
				</span>

					<button id=" replenishment" class="btn btn-success">一键补货</button>

					<button id="clear" class="btn btn-danger">一键清货</button>

				
				
			</li> -->
		<!-- 折叠部分 -->
			<!-- <li class="col_holder collapse table-responsive" id="innn2">
				<table class="table table-bordered" >
					<thead>
						<tr>
							<td>
								<span class="fa fa-star"></span>
							</td>
							<td>ID</td>
							<td>商品名称</td>
							<td>当前库存/最大库存量</td>
							<td>更新时间</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>	<span class="fa fa-star"></span></td>
							<td>1</td>
							<td>大口红</td>
							<td>3/4</td>
							<td>无</td>
						</tr>
						<tr>
							<td>	<span class="fa fa-star"></span></td>
							<td>12</td>
							<td>玩具熊</td>
							<td>0/5</td>
							<td>无</td>
						</tr>
					</tbody>
				</table>
				</li> -->


			

		</ul>
	</section>
</body>
<script src="__STATIC__/js/new/rem.js"></script>
<script src="__STATIC__/js/new/jquery-2.1.4.min.js"></script>
<script src="__STATIC__/js/new/bootstrap.min.js"></script>
<script src="__STATIC__/js/new/page_index2.js"></script>

<script src="__PUBLIC__/js/layer/layer.js"></script>

<script>
 function supply(obj) {
        layer.confirm('确认补满？', {
                    btn: ['确定', '取消'] //按钮
                }, function (index) {
                    layer.close(index);
                    // 确定
                    $.ajax({
                    	async : false,
                        type: 'post',
                        url: $(obj).attr('data-url'),
                        data: {act: '_ADD_', machine_id: $(obj).attr('data-id'), msgtype:'open_room'},
                        dataType: 'json',
                        success: function (v) {
                            if (v.status == 1) {
                                //layer.msg('操作成功', {icon: 1});
                                //location.reload();
                                //$(obj).parent().parent().parent().remove();
                                $.ajax({
                                	async : false,
                                	type: 'post',
                                	url: 'http://192.168.1.133/phone/room/check_status',
                                	data: {commandid:v.commandid},
                                	dataType: 'json',
                                	success: function(res){
                                		if(res.status == 1){
                                			layer.msg('操作成功', {icon: 1});
                                			//location.reload();
                                		}else{
                                			layer.msg(res.msg, {icon: 2, time: 2000});
                                		}
                                	}
                                })
                            } else {
                                layer.msg(v.msg, {icon: 2, time: 2000});
                            }
                        }
                    })
                }, function (index) {
                    layer.close(index);
                }
        );
    };


</script>
<script>
 function supply2(obj) {
        layer.confirm('确认重置？', {
                    btn: ['确定', '取消'] //按钮
                }, function (index) {
                    layer.close(index);
                    // 确定
                    $.ajax({
                        type: 'post',
                        url: $(obj).attr('data-url'),
                        data: {act: '_DEL_', id: $(obj).attr('data-id')},
                        dataType: 'json',
                        success: function (v) {
                            if (v.status == 1) {
                                layer.msg('操作成功', {icon: 1});
                                location.reload();
                                //$(obj).parent().parent().parent().remove();
                                
                            } else {
                                layer.msg(v.msg, {icon: 2, time: 2000});
                            }
                        }
                    })
                }, function (index) {
                    layer.close(index);
                }
        );
    };

$('.tog').click(function(){
		$('.slide_bar').slideToggle();
	});

$('#upper').click(function(){
	history.back();
})
</script>
</html>