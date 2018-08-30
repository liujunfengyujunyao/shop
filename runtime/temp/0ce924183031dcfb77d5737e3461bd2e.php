<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view2/partner\add.html";i:1535603836;s:44:"./application/admin/view2/public\layout.html";i:1533876247;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>配货员管理 - 编辑配货员</h3>
				<h5>网站配货员管理</h5>
			</div>
		</div>
	</div>
	<form class="form-horizontal" id="handleForm" action="<?php echo U('Admin/Partner/add'); ?>" method="post">
		<input name="partner_id" type="hidden" value="<?php echo $partner['partner_id']; ?>" />
		<input name="user_id" type="hidden" value="<?php echo $partner['user_id']; ?>" />
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>配货员姓名</label>
				</dt>
				<dd class="opt">
					<input type="text" name="nickname" value="<?php echo $partner['nickname']; ?>" class="input-txt">
					<span class="err" id="err_nickname"></span>
					<p class="notic">配货员姓名</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>登录密码</label>
				</dt>
				<dd class="opt">
					<input type="password" name="password" value="" class="input-txt">
					<span class="err" id="err_password"></span>
					<p class="notic">6-16位字母、数字、下划线组合<?php if($act == edit): ?>
					，留空表示不修改密码<?php endif; ?></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>地址</label>
				</dt>
				<dd class="opt">
					<select name="province" id="province" onChange="get_city(this)">
						<option value="">请选择</option>
						<?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
							<option <?php if($partner['province_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<select name="city" id="city" onChange="get_area(this)">
						<option value="">请选择</option>
						<?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
							<option <?php if($partner['city_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<select name="district" id="district">
						<option value="">请选择</option>
						<?php if(is_array($district) || $district instanceof \think\Collection || $district instanceof \think\Paginator): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
							<option <?php if($partner['town_id'] == $p['id']): ?>selected<?php endif; ?>  value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<span class="err" id="err_district"></span>
					<p class="notic">地址</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label><em>*</em>联系电话</label>
				</dt>
				<dd class="opt">
					<input type="text" name="mobile" value="<?php echo $partner['phone']; ?>" onpaste="this.value=this.value.replace(/[^\d-]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d-]/g,'')" class="input-txt">
					<span class="err" id="err_mobile"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<div class="bot"><a onclick="checkForm();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
function checkForm(){
	$('span.err').hide();
	$.ajax({
		type: "POST",
		url: "<?php echo U('Admin/Partner/add'); ?>",
		data: $('#handleForm').serialize(),
		dataType: "json",
		error: function () {
			layer.alert("服务器繁忙, 请联系管理员!");
		},
		success: function (data) {
			if (data.status == 1) {
				layer.msg(data.msg, {icon: 1});
				location.href = "<?php echo U('Admin/Partner/index'); ?>";
			} else {
				layer.msg(data.msg, {icon: 2});
				$.each(data.result, function (index, item) {
					$('#err_' + index).text(item).show();
				});
			}
		}
	});
}
</script>
</body>
</html>