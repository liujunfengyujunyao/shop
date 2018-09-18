<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./template/phone/new/goods\stock_index.html";i:1537178516;}*/ ?>
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
	<title>Document</title>
</head>
<body>
	<header id="head">

		<h3>
			<span class="fa fa-cubes"></span>
			贩卖机库存列表<small>(共<?php echo $count; ?>条记录)</small></h2>
	</header>
	<section id="body">
		<ul class="main list-group">

			<li class="list-group-item toggler"
					role='button'
					data-open="false"
					data-toggle="collapse"
					data-target="#innn1"
					aria-expanded="false"
					aria-controls="innn1"
			>
				<span class="title textFlow">
					<i class="fa fa-toggle-right"></i>
					<span class="label label-primary">一号机器</span>
				</span>

				<span class="title textFlow">
					<span class="label label-default">北京市东城区</span>
				</span>

					<button id=" replenishment" class="btn btn-success">一键补货</button>

					<button id="clear" class="btn btn-danger">一键清货</button>

				<!-- 折叠部分 -->
				
			</li>
		
			<li class="col_holder collapse table-responsive" id="innn1">
				<table class="table table-bordered" >
					<thead>
						<tr>
							<td>
								<span class="fa fa-star"></span>
							</td>
							<td>位置</td>
							<td>商品名称</td>
							<td>当前库存/最大库存量</td>
							<td>更新时间</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>	<span class="fa fa-star"></span></td>
							<td>A1</td>
							<td>大口红</td>
							<td>{}/{}</td>
							<td>无</td>
						</tr>
						<tr>
							<td>	<span class="fa fa-star"></span></td>
							<td>A2</td>
							<td>玩具熊</td>
							<td>0/5</td>
							<td>无</td>
						</tr>
					</tbody>
				</table>
				</li>

				<!-- <li class="list-group-item toggler"
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
 -->
				<!-- 折叠部分 -->
				
			<!-- </li>
		
			<li class="col_holder collapse table-responsive" id="innn2">
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
</html>