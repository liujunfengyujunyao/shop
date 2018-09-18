<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:42:"./template/phone/new/machine\delivery.html";i:1537180231;}*/ ?>
<!DOCTYPE html>
<html lang="en" id="rootHTML">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="__STATIC__/css/new/fontawesome/font-awesome.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/common.css">
	<link rel="stylesheet" href="__STATIC__/css/new/item_list.css">
	<link rel="stylesheet" href="__STATIC__/css/new/bootstrap.min.css">
	<link rel="stylesheet" href="__STATIC__/css/new/util.css">
</head>
<body>
		<header id="head">

		<h3>
			<span class="fa fa-list-alt"></span>
			商品列表<small>(共2条记录)</small>
			<a href="#"class="fa fa-cog">
				<span id="config">配置</span>
			</a>
			
		</h3>
	</header>
	<section id="body" class="table-responsive">
		<table class="table table-bordered">
					<thead>
						<tr class="title">
							<td> <span class="fa fa-star" style="color: #666;"></span> </td>
							<td>ID</td>
							<td>商品名称</td>
							<td>价格</td>
							<td>本机库存</td>
							<td>存放位置</td>
						</tr>
						<!-- <tr class="title2">
							<td colspan="5">
								<span><i class="fa fa-plus"></i>添加管理员</span>
							</td>
							
						</tr> -->
					</thead>
					<tbody>
						<tr>
							<td><span class="fa fa-star"></span></td>
							<td>1</td>
							<td>可乐</td>
							<td>10.00</td>
							<td>0</td>
							<td>A2</td>
						</tr>
						<tr>
							<td><span class="fa fa-star"></span></td>
							<td>2</td>
							<td>雪碧</td>
							<td>10.00</td>
							<td>0</td>
							<td>B2</td>
						</tr>
						<tr>
							<td><span class="fa fa-star"></span></td>
							<td>3</td>
							<td>芬达</td>
							<td>11.00</td>
							<td>0</td>
							<td>C2</td>
						</tr>
					</tbody>
				</table>


	</section>
	<section id="pag">
		<nav aria-label="Page navigation">
				<!-- 根据data-index来确定当前所在页渲染分页 -->
				  <ul class="pagination" data-index="3">
				    <li>
				      <a href="#" aria-label="Previous">
				        <span aria-hidden="true">&laquo;</span>
				      </a>
				    </li>
				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
				    <li><a href="#">4</a></li>
				    <li><a href="#">5</a></li>
				    <li>
				      <a href="#" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				      </a>
				    </li>
				  </ul>
				</nav>
	</section>

		<form id="setup" action="" data-show="false">
			<div class="bg"></div>
			<table class="table table-bordered">

				<thead>
					<tr>
						<td>存放位置</td>
						<td>商品名称</td>
						<td>本店单价</td>
						<td>本机库存</td>
						<td>最大库存量</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="text" disabled="disabled" value="A2" class="form-control">
						</td>
						<td>
							<input type="text"  value="可乐可乐可乐可乐可乐" class="form-control">
						</td>
						<td>
							<input type="text"  value="￥10.00" class="form-control">
						</td>
						<td>
							<input type="text" disabled="disabled" value="0" class="form-control">
						</td>
						<td>
							<input type="text" disabled="disabled" value="5" class="form-control">
						</td>
					</tr>
					
					<!-- 提交和总价 -->
					<tr>
						<td colspan="2"><b>总价</b>:￥100</td>
						<td colspan="3">
							<button class="btn btn-success" type="submit">确认配货</button>
						
						</td>
					</tr>

				</tbody>
			</table>
		</form>


</body>
<script src="__STATIC__/js/new/rem.js"></script>
<script src="__STATIC__/js/new/jquery-2.1.4.min.js"></script>
<script>
	$(function(){
		var indexer = $('#pag ul').attr('data-index');
		$('#pag li').eq(indexer).addClass('active');
	})

	$('#body table tbody td').click(function(){
		$activer = $(this).parent();
		$(this).parent().addClass('tableActive').siblings().removeClass('tableActive');
	})

	$('#pag li').click(function(){
		if(!$(this).hasClass('active'))
		{
			$(this).addClass('active').siblings().removeClass('active');
		}
	})


// 弹出弹框
	$('#config').click(function(){
		console.log($('#setup').css('justifyContent'))
		
			
			$('#setup').css({ transform: 'scale(1,1)' })
			$("#setup").attr('data-show','false')
			var $width = $('#setup table').width() + 3;
			// console.log($width);
			// $('#setup .bg').css('width',$width);
	
	})

// 收回弹框
$('#setup .bg').click(function(){
			$('#setup').css({ transform: 'scale(0,0)' })
			$("#setup").attr('data-show','true')
			var $width = $('#setup table').width();
			console.log($width);
			// $('#setup .bg').css('width',$width);
})

// $('#setup table').click(function(){
// 	console.log(123);
// 	$(this).css({ transform : 'scale(0.7,0.7)' })
// })
</script>
</html>