<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:44:"./template/pc/rainbow/partner\act_apply.html";i:1533876251;}*/ ?>
<!Doctype html>
<html>
<head>
<meta charset="utf-8">
<title>申请补货-<?php echo $tpshop_config['shop_info_store_title']; ?></title>
<meta name="keywords" content="<?php echo $tpshop_config['shop_info_store_keyword']; ?>" />
<meta name="description" content="<?php echo $tpshop_config['shop_info_store_desc']; ?>" />
<script src="__STATIC__/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
<script src="__PUBLIC__/js/layer/layer-min.js"></script><!--弹窗js 参考文档 http://layer.layui.com/-->
<script src="__PUBLIC__/js/global.js"></script>
<link href="__PUBLIC__/static/font/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
body {margin: 0; font-size: 14px;}
.apply .box-ct table {padding: 5px 0 60px; width: 100%;}
.apply .box-ct table th {height: 40px; background: #f5f5f5; border-right: 1px solid #f5eded;}
.apply .box-ct table td {text-align: center; vertical-align: middle; border-bottom: 1px solid #f5eded; border-right: 1px solid #f5eded; padding: 10px 0;}
.apply .box-ct table td a {color: #3b639f; }
.apply .box-ct select {background: #fff; border: 1px solid #dedede; padding: 3px 10px;}
.apply .box-ct input {border: 1px solid #dedede; padding: 3px 10px;}
.apply .box-ct .submitBtn {background-color: #4fc0e8; padding: 6px 25px; color: #fff; text-decoration: none;}
</style>
</head>
<body>
<div class="apply">
	<div class="box-ct">
		<form action="" method="post" id="handleForm">
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="7%"></th>
						<th width="73%">商品名称</th>
						<th width="25%">数量</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<a href="javascript:;" title="添加行" onclick="addLine()" class="plus"><span class="fa fa-plus"></span></a>
							<a href="javascript:;" title="删除行" onclick="delLine(this)" class="minus"><span class="fa fa-minus"></span></a>
						</td>
						<td>
							<select onchange="getId(this)">
								<option value="">请选择</option>
								<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								<option value="<?php echo $v['goods_id']; ?>" max="<?php echo $v['stock_num']-$v['goods_num']; ?>"><?php echo $v['goods_name']; ?></option>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
						<td>
							<input type="text" class="number" value="1" onkeyup='this.value=this.value.replace(/\D/gi,"")'/>
						</td>
					</tr>
				</tbody>
			</table>
			<div style="text-align: center;">
				<a href="javascript:;" onclick="checkForm()" class="submitBtn">提 交</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
function addLine(){
	var content = $(".apply .box-ct table tbody tr:first").html();
	content = "<tr>" + content + "</tr>";
	$(".apply .box-ct table tbody tr:last").after(content);
	$(".apply .box-ct table tbody tr:last").css("border", '1px solid #dfdfdf');
}
function delLine(obj){
	var length = $(".apply .box-ct table tbody tr").length;
	if (length == 1) {
		layer.alert('请至少保留一项！');
	}else {
		$(obj).parents('tr').remove();
	}
}
function getId(opt) {
	var id = $(opt).val();
	var name1 = "goods['" + id + "'][goods_id]";
	var name2 = "goods['" + id + "'][number]";
	$(opt).attr('name', name1);
	$(opt).parents('tr').find('.number').attr('name', name2);
}
function checkForm(){
	var arr = [];
	var res = 0;
	$(".apply .box-ct table tbody tr").each(function(index, el) {
		var goods = $(el).find('select').val();
		var max = $(el).find("option:selected").attr("max");
		var val = $(el).find(".number").val();
		$(el).find('.number').css("border", '1px solid #dfdfdf');

		if ($.inArray(goods, arr) == -1) {
			if (goods != false) {
				arr.push(goods);
			}
		}else {
			res = 1;	//重复选择商品
			return false;
		}

		if (parseInt(max) < parseInt(val)) {
			res = 2;	//当前商品补货数量超过最大库存
			$(el).find('.number').css("border", '1px solid #f00');
			return false;
		}
	});

	if (arr.length == 0) {
		layer.alert('请至少选择一项商品！');
		return false;
	}else if (res == 1) {
		layer.alert('请勿重复添加商品！');
		return false;
	}else if (res == 2) {
		layer.alert('补货数量超过最大库存量！');
		return false;
	}else {
		$.ajax({
			type: "POST",
			url: "/index.php?m=Home&c=Partner&a=act_apply",
			data: $('#handleForm').serialize(),
			dataType: "json",
			error: function () {
				layer.alert("服务器繁忙, 请联系管理员!");
			},
			success: function (data) {
				if (data.status == 1) {
					layer.msg(data.msg, {icon: 1, time: 2000}, function() {
						//关闭iframe页面
						var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
						parent.layer.close(index);
						parent.location.href = "<?php echo U('Partner/apply'); ?>";
					});
				} else {
					layer.msg(data.msg, {icon: 2});
				}
			}
		});
	}
}
</script>