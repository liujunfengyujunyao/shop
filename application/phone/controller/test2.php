<?php
require_once("jssdk.php");
$jssdk=new JSSDK(appID,appsecret);
$signPackage = $jssdk->GetSignPackage();
require_once('header.php');
?>
<a href="javascript:;">扫一扫</a>
<?php
require_once('footer.php');
?>
<script src="scripts/jquery/3.1.1/jquery.min.js"></script>
<script src="scripts/jweixin-1.0.0.js"></script>
<script>
  wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
	  "scanQRCode"
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
	$(document).on("click","a",function(){
		wx.scanQRCode({
			needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
			scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
			success: function (res) {
				//alert(res);
				for(i in res ){
				//  alert(i);           //获得属性 
				  alert(i + "---" + res[i]);  //获得属性值
				}
				//var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
			}
		});
 
	})
  });
</script>