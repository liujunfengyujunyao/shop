<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view2/partner\storeList.html";i:1534934849;s:44:"./application/admin/view2/public\layout.html";i:1536395974;}*/ ?>
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
	body {overflow: hidden;}
	.dataTables_paginate {text-align: left; padding-left: 30%}
	.empty {font-size: 16px; padding: 5px 0 5px 30%; color: #f00}
</style>
<div class="flexigrid" id="ajax_return">
	<div class="hDiv">
		<div class="hDivBox">
			<table cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th class="sign" axis="col0">
							<div style="width: 50px;"><i class="ico-check"></i></div>
						</th>
						<th align="center" abbr="article_title" axis="col3" class="">
							<div style="text-align: center; width: 50px;" class="">ID</div>
						</th>
						<th align="center" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 150px;" class="">贩卖机名称</div>
						</th>
						<th align="center" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 150px;" class="">贩卖机种类</div>
						</th>
						<!-- <th align="center" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 120px;" class="">负责人</div>
						</th>
						<th align="center" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 120px;" class="">联系方式</div>
						</th> -->
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 120px;" class="">添加时间</div>
						</th>
						<th style="width:100%" axis="col7">
							<div></div>
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			<table>
				<tbody>
				<?php if(is_array($machineList) || $machineList instanceof \think\Collection || $machineList instanceof \think\Paginator): $i = 0; $__LIST__ = $machineList;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
					<tr>
						<td class="sign">
							<div style="width: 50px;"><i class="ico-check"></i></div>
						</td>
						<td align="left" class="">
							<div style="text-align: center; width: 50px;"><?php echo $list['machine_id']; ?></div>
						</td>
						<td align="left" class="">
							<div style="text-align: center; width: 150px;"><?php echo getSubstr($list['machine_name'],0,33); ?></div>
						</td>
						<td align="left" class="">
							<div style="text-align: center; width: 150px;"><?php echo getSubstr($list['type_name'],0,33); ?></div>
						</td>
						<!-- <td align="left" class="">
							<div style="text-align: center; width: 120px;"><?php echo $list['nickname']; ?></div>
						</td>
						<td align="left" class="">
							<div style="text-align: center; width: 120px;"><?php echo $list['phone']; ?></div>
						</td> -->
						<td align="left" class="">
							<div style="text-align: center; width: 120px;"><?php echo $list['addtime']; ?></div>
						</td>
						<td align="" class="" style="width: 100%;">
							<div>&nbsp;</div>
						</td>
					</tr>
				<?php endforeach; endif; else: echo "$empty" ;endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<!--分页位置-->
	<?php echo $page; ?>
	<div class="partner_id" style="display: none"><?php echo $partner_id; ?></div>
</div>
<script>
$(document).ready(function(){
	// 表格行点击选中切换
	$('#flexigrid>table>tbody>tr').click(function(){
		$(this).toggleClass('trSelected');
	});
	$('#count').empty().html("<?php echo $pager->totalRows; ?>");
});
$(".pagination a").click(function(){
	cur_page = $(this).data('p');
	ajax_get_table('search-form2', cur_page);
});

// ajax 抓取页面 form 为表单id  page 为当前第几页
function ajax_get_table(form,page) {
	var id = $('.partner_id').text();
	cur_page = page; //当前页面 保存为全局变量
	$.ajax({
		type: "POST",
		url: "/index.php?m=Admin&c=Partner&a=view&partner_id="+id+"&p="+page,
		data: $('#'+form).serialize(),// 你的formid
		success: function(data){
			$("#ajax_return").html('');
			$("#ajax_return").append(data);
		}
	});
}
</script>