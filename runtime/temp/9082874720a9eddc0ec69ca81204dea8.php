<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"./application/admin/view2/partner\apply_info.html";i:1536029763;s:44:"./application/admin/view2/public\layout.html";i:1536395974;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="__PUBLIC__/static/css/main.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/css/page.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="__PUBLIC__/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__PUBLIC__/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="__PUBLIC__/static/js/admin.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.mousewheel.js"></script>
<script src="__PUBLIC__/js/myFormValidate.js"></script>
<script src="__PUBLIC__/js/myAjax2.js"></script>
<script src="__PUBLIC__/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }
    
    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }   
    
    function get_help(obj){
        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'), 
        });
    }
    
    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);	
    }
</script>  

</head>
<style>
.ncm-goods-gift {text-align: left;}
.ncm-goods-gift ul {display: inline-block; font-size: 0; vertical-align: middle;}
.ncm-goods-gift li {display: inline-block; letter-spacing: normal; margin-right: 4px; vertical-align: top; word-spacing: normal;}
.ncm-goods-gift li a {background-color: #fff; display: table-cell; height: 30px; line-height: 0; overflow: hidden; text-align: center; vertical-align: middle; width: 30px;}
.ncm-goods-gift li a img {max-height: 30px; max-width: 30px;}
a.green{background: #fff; border: 1px solid #f5f5f5; border-radius: 4px; color: #999; cursor: pointer !important; display: inline-block; font-size: 12px; font-weight: normal; height: 20px; letter-spacing: normal; line-height: 20px; margin: 0 5px 0 0; padding: 1px 6px; vertical-align: top;}
a.green:hover {color: #FFF; background-color: #1BBC9D; border-color: #16A086;}
.ncap-order-style .ncap-order-details {margin: 20px auto;}
.contact-info h3,.contact-info .form_class {display: inline-block; vertical-align: middle;}
.form_class i.fa {vertical-align: text-bottom;}
.ncap-order-style .ncap-order-details .text-red {color: #f00;}
</style>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="javascript:history.go(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>补货申请详情</h3>
				<h5>配货员申请补货记录明细</h5>
			</div>
		</div>	
	</div>
	<form id="apply-form" method="post">
		<!-- <input type="hidden" id="status" name="status" value="<?php echo $info['status']; ?>"> -->
		<input type="hidden" id="act" name="act" />
		<div class="ncap-order-style">
			<div class="ncap-order-details">
				<div class="tabs-panels">
					<div class="misc-info">
						<h3>基本信息</h3>
						<dl>
							<dt>申请人：</dt>
							<dd><?php echo $info['nickname']; ?></dd>
							<dt>联系电话：</dt>
							<dd><?php echo $info['mobile']; ?></dd>
							<dt>地址：</dt>
							<dd><?php echo $info['province']; ?> <?php echo $info['city']; ?> <?php echo $info['district']; ?></dd>
						</dl>
						<dl>
							<dt>申请时间：</dt>
							<dd><?php echo date('Y-m-d H:i:s',$info['addtime']); ?></dd>
							<dt>发货时间：</dt>
							<dd><?php echo (isset($info['delivery_time']) && ($info['delivery_time'] !== '')?$info['delivery_time']:"未发货"); ?></dd>
							<dt>收货时间：</dt>
							<dd><?php echo (isset($info['confirm_time']) && ($info['confirm_time'] !== '')?$info['confirm_time']:"未收货"); ?></dd>
						</dl>
						<?php if($info['express_code']): ?>
						<dl>
							<dt>物流名称：</dt>
							<dd><?php echo $info['express_name']; ?></dd>
							<dt>物流单号：</dt>
							<dd><span class="text-red"><?php echo $info['express_code']; ?></span></dd>
						</dl>
						<?php endif; ?>
					</div>
					<div class="goods-info">
						<h4>商品信息</h4>
						<table>
							<thead>
								<tr>
									<th>商品编号</th>
									<th colspan="2">商品</th>
									<th>数量</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?>
								<tr>
									<td class="w90"><?php echo $good['goods_sn']; ?></td>
									<td class="w30"><div class="goods-thumb"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$good['goods_id'])); ?>" target="_blank"><img alt="" src="<?php echo goods_thum_images($good['goods_id'],200,200); ?>" /> </a></div></td>
									<td style="text-align: left;"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$good['goods_id'])); ?>" target="_blank"><?php echo $good['goods_name']; ?></a><br/></td>
									<td class="w120"><?php echo $good['goods_num']; ?></td>
								</tr>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</table>
					</div>
					<div class="total-amount contact-info">
						<h3></h3>
					</div>
					<?php if($info['status'] == 0): ?>
					<div class="contact-info">
						<h3>操作信息</h3>
						<dl class="row">
							<dt class="tit">
								<label for="note">操作备注</label>
							</dt>
							<dd class="opt" style="margin-left:10px">
								<textarea id="note" name="remark" style="width:600px" rows="6"  placeholder="请输入操作备注" class="tarea"><?php echo $info['remark']; ?></textarea>
							</dd>
						</dl>
						<dl class="row">
							<dt class="tit">
								<label for="note">可执行操作</label>
							</dt>
							<dd class="opt" style="margin-left:10px">
								<a href="JavaScript:void(0);" onclick="delivery();" class="ncap-btn-big ncap-btn-green">去发货</a>
								<a href="JavaScript:void(0);" onclick="cancel_apply();" class="ncap-btn-big ncap-btn-green">拒绝申请</a>
							</dd>
						</dl> 
					</div>
					<?php endif; ?>
					<div class="goods-info">
						<h4>操作记录</h4>
						<table>
							<thead>
								<tr>
									<th>操作者</th>
									<th>操作时间</th>
									<th>描述</th>
									<th>备注</th>
							 	</tr>
							</thead>
							<tbody>
								<tr>
									<td>配货员：<?php echo $info['nickname']; ?></td>
									<td><?php echo date('Y-m-d H:i:s',$info['addtime']); ?></td>
									<td>提交申请</td>
									<td></td>
								</tr>
								<?php if($info['status'] != 0): ?>
								<tr>
									<td>管理员：<?php echo $info['user_name']; ?></td>
									<td><?php echo date('Y-m-d H:i:s',$info['edittime']); ?></td>
									<td>
										<?php if($info['status'] == 1): ?>
											通过申请
										<?php endif; if($info['status'] == 2): ?>
											拒绝申请
										<?php endif; ?>
									</td>
									<td><?php echo $info['remark']; ?></td>
								</tr>
								<?php endif; if($info['status'] == 1): ?>
								<tr>
									<td>管理员：<?php echo $info['user_name']; ?></td>
									<td><?php echo $info['delivery_time']; ?></td>
									<td>发货</td>
									<td></td>
								</tr>
								<?php endif; if($info['confirm_time']): ?>
								<tr>
									<td>配货员：<?php echo $info['nickname']; ?></td>
									<td><?php echo $info['confirm_time']; ?></td>
									<td>确认收货</td>
									<td></td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</body>
<div id="layer" style="display: none">
	<form class="form-horizontal" id="handleForm" method="post">
		<input type="hidden" name="act" value="delivery" />
		<input type="hidden" name="id" value="<?php echo \think\Request::instance()->get('id'); ?>" />
		<input type="hidden" name="remark" />
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>物流名称：</label>
				</dt>
				<dd class="opt">
					<input type="text" name="express_name" value="" class="input-txt" placeholder="请填写物流名称" />
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>物流单号：</label>
				</dt>
				<dd class="opt">
					<input type="text" name="express_code" value="" class="input-txt" placeholder="请填写物流单号" />
				</dd>
			</dl>
			<div class="bot"><a onclick="checkForm();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认发货</a></div>
		</div>
	</form>
</div>
</html>
<script type="text/javascript">
// 拒绝申请
function cancel_apply() {
	if ($.trim($('#note').val()).length == 0) {
		layer.alert('请填写拒绝备注', {icon: 2});
		return false;
	}
	layer.confirm('确定要拒绝配货员的补货申请吗?', {
		btn: ['确定', '取消'] //按钮
	}, function () {
		// 确定
		$('#act').val('cancel');
		$.ajax({
			type: "POST",
			url: "<?php echo U('Admin/Partner/apply_info', array('id'=>\think\Request::instance()->get('id'))); ?>",
			data: $('#apply-form').serialize(),
			dataType: "json",
			error: function () {
				layer.alert("服务器繁忙, 请联系管理员!");
			},
			success: function (data) {
				if (data.status == 1) {
					layer.msg(data.msg, {icon: 1});
					layer.closeAll();
					window.location.reload();
				} else {
					layer.msg(data.msg, {icon: 2});
				}
			}
		});
//		$('#apply-form').submit();
	}, function (index) {
		layer.close(index);
	});
}
//去发货
function delivery() {
	if ($.trim($('#note').val()).length == 0) {
		layer.alert('请填写操作备注', {icon: 2});
		return false;
	}
	$("#layer input[name='remark']").val($('#note').val());
	layer.open({
		type: 1,
		skin: 'layui-layer-rim',	//加上边框
		area: ['550px', '300px'],	//宽、高
		title: "发货",
		content: $('#layer')
	})
}
function checkForm(){
	var name = $("#layer input[name='express_name']").val();
	var code = $("#layer input[name='express_code']").val();

	if ((name == '') || (code == '')) {
		layer.alert('信息填写不完整', {icon: 2});
		return;
	}else {
		$.ajax({
			type: "POST",
			url: "<?php echo U('Admin/Partner/apply_info'); ?>",
			data: $('#handleForm').serialize(),
			dataType: "json",
			error: function () {
				layer.alert("服务器繁忙, 请联系管理员!");
			},
			success: function (data) {
				if (data.status == 1) {
					layer.msg(data.msg, {icon: 1});
					layer.closeAll();
					window.location.reload();
				} else {
					layer.msg(data.msg, {icon: 2});
				}
			}
		});
	}
}
</script>