<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:36:"./template/phone/new/scan\index.html";i:1539157494;}*/ ?>
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
    <link rel="stylesheet" href="__PUBLIC__/css/csss/alert.css">

    <link rel="stylesheet" href="//at.alicdn.com/t/font_626784_0j006ef09vff.css" />
    <script src="https://cdn.staticfile.org/jquery/1.11.2/jquery.min.js"></script>
   
   
</head>
<body>
<div id="result"></div><!-- 有用 -->
<a href="http://sao315.com/w/api/saoyisao?redirect_uri=">扫一扫</a>
<p id="inner"></p>
<button class="btn btn-success"><a href="<?php echo U('Scan/add'); ?>" ><i class="fa fa-plus"></i>手动添加机台</a></button><br/>
<button class="btn btn-success"><i class="fa fa-pencil-square-o"></i><a href="<?php echo U('Index/index'); ?>" >返回首頁</a></button><br/>
<!-- <span><a href="<?php echo U('Machine/add'); ?>" ><i class="fa fa-plus"></i>手动添加机台</a></span> -->
</body>
<script src="__PUBLIC__/js/js/rem.js"></script>

<script src="__PUBLIC__/js/js/page_power.js"></script>
	<!-- <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> -->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="__PUBLIC__/js/jquery.min.js"></script>
 <script src="__PUBLIC__/js/js/alert.js"></script>
 <script src="__PUBLIC__/js/js/util.js"></script>

<script type="text/javascript">
var qr=getQueryString("qrresult");
// console.log(qr);



if(qr){
    
    // $("#result").html(qr);
    var sendMsg = confirm(qr);
    if(sendMsg)
    {
        // alert('yes');
        var data = {
            sn: qr
        };
        try{
            // $.post('{U:("phone/Scan/index")}' , data, function(e) {
            //     alert(e);
            // })
            $.ajax({
                url: "<?php echo U('Scan/index'); ?>",
                type: 'POST',
                data: data,
                success: function(e){
                    if(e.error_code == 1){
                        alert('SN号不存在');
                    }else if(e.error_code == 2){
                        alert('机台已删除');
                    }else if(e.error_code == 3){
                        alert('机台已注册');
                    }else if(e.error_code == 4){
                        alert('机台注册成功');
                    }
                },
                // error: function(e){
                //     alert('error');
                //     // $('#inner').text(e);
                    
                //     // console.log(e);
                // }
            })
        }catch(e){
            alert(e);
        }
        
    }else{
         alert('no');
    }
    var wl=qr.split("CODE_128,");
    if (wl.length==2){
        // window.open("http://192.168.1.145/phone" + wl[1]);
        // window.open("http://192.168.1.145/s?wd=" + wl[1]);
    }else{
        // console.log(qr);
        // alert("您扫描的不是快递单号！所以无法为您查询物流。请对准快递单条形码进行扫描！");
    }
}

function getQueryString(name)
{
    var reg = new RegExp("\\b"+ name +"=([^&]*)");
    var r = location.href.match(reg);
    if (r!=null) return unescape(r[1]);
}
</script>
</html>