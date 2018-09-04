<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:45:"./template/pc/rainbow/order\refund_order.html";i:1533876251;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单取消申请</title> 
 <link href="__STATIC__/css/cOrderIframe.css" rel="stylesheet">
<script src="__STATIC__/js/jquery-1.11.3.min.js" type="text/javascript" charset="utf-8"></script>
<script src="__STATIC__/js/common.js"></script>
<script src="__PUBLIC__/js/global.js" type="text/javascript"></script>
<script src="__PUBLIC__/static/js/layer/layer.js" type="text/javascript"></script>
<script src="__ROOT__/public/static/js/layer/laydate/laydate.js"></script>
</head>
<body>
<div class="cancle-box paydetail-box">
	<form id='cancelForm'>
		<p>如取消成功，您所支付的金额将通过以下方式返还：</p>
		<div class="tb-void">
			<table>
				<tbody>
                    <tr>
                        <td width="175">订单总额:<strong class="ftx-01">￥<?php echo $order['total_amount']; ?></strong></td>
                        <td ></td>
                    </tr>
					<tr>
						<td width="175">支付明细: <?php echo (isset($order['pay_name']) && ($order['pay_name'] !== '')?$order['pay_name']:'其他方式'); ?><strong class="ftx-01"  id="payDetail_yhk">￥<?php echo $order['order_amount']; ?></strong></td>
						<td >返款方式 : 支付原路退还 </td>
					</tr>
					<tr>
						<td width="175">取消原因</td>
						<td ><div id="pay-type">
								<select name="user_note">
									<option value="订单不能按预计时间送达">订单不能按预计时间送达</option>
									<option value="操作有误（商品、地址等选错）">操作有误（商品、地址等选错）</option>
									<option value="重复下单/误下单">重复下单/误下单</option>
									<option value="其他渠道价格更低">其他渠道价格更低</option>
									<option value="该商品京东降价了">该商品京东降价了</option>
									<option value="不想买了">不想买了</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<dl class="pdetail" style="">
								<dd class="fore1">优惠券</dd>
								<dd class="fore2"><strong class="ftx-01" id="payDetail3">￥<?php echo $order['coupon_price']; ?></strong></dd>
							</dl>
							<dl class="pdetail" style="">
								<dd class="fore1">积分</dd>
								<dd class="fore2"><strong class="ftx-01" id="payDetail18">￥<?php echo $order['integral_money']; ?></strong></dd>
							</dl>
							<dl class="pdetail" style="">
								<dd class="fore1">余额</dd>
								<dd class="fore2"><strong class="ftx-01" id="payDetail1">￥<?php echo $order['user_money']; ?></strong></dd>
							</dl>
						</td>
						<td>
							<div class="return-msg">
								温馨提示：<br>
								· 订单成功取消后无法恢复<br>
								· 该订单所用余额、积分返至平台账户<br>
								· 拆单后取消订单，使用的优惠券将不再返还
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="item clearfix">
			<span class="label">联系方式：</span>
			<input type="text" class="text text1 gray" name="consignee" value="<?php echo (isset($user['realname']) && ($user['realname'] !== '')?$user['realname']:$user['nickname']); ?>"/>
			<input type="text" class="text gray" name="mobile" value="<?php echo $user['mobile']; ?>"/>
			<div class="clr"></div>
		</div>
		<div class="op-btns" id="butDiv">
			<a href="#" class="btn btn-11" onclick="btnSubmit()" id="cancelBut"><s></s>确定取消</a>
			<a href="#" class="btn btn-11" onclick="btnClose()"><s></s>暂不取消</a>
		</div>
        <input type="hidden" class="text gray" name="order_id" value="<?php echo $order['order_id']; ?>"/>
	</form>
</div>

<script type="text/javascript">
    function btnClose() {
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭   
    }
    function btnSubmit() {
        $.ajax({
            url:"<?php echo U('Order/record_refund_order'); ?>",
            method:'POST',
            data:$('#cancelForm').serialize(),
            dataType:'json',
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status === 1) {
                    layer.msg(data.msg, {icon: 1,time: 1000}, function() {
                        window.parent.location.href = "<?php echo U('Order/order_list',['type'=>'WAITSEND']); ?>";
                        btnClose();
                    });
                } else {
                    layer.msg(data.msg, {icon: 2,time: 1000});
                }
            }
        });
    }
</script>
</body>
</html>