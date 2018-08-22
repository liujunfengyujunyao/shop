<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"./application/admin/view2/machine\optionMachine.html";i:1534851229;s:44:"./application/admin/view2/public\layout.html";i:1533876247;}*/ ?>
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
                <h3>贩卖机管理 - 配置贩卖机</h3>
                <h5>贩卖机管理</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="machine" method="post">
        <!-- <input type="hidden" name="act" value="<?php echo $act; ?>"> -->
        <input type="hidden" name="machine_id" value="<?php echo $data['machine_id']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="machine_name"><em>*</em>贩卖机名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="machine_name" value="<?php echo $data['machine_name']; ?>" id="machine_name" class="input-txt">
                    <span class="err" id="err_machine_name"></span>
                    <p class="notic">设置贩卖机名称</p>
                </dd>
            </dl>
            
            <dl class="row">
                <dt class="tit">
                    <label for="version">游戏版本</label>
                </dt>
                <dd class="opt">
                    <select name="version" id="version"  class="class-select valid">
                    <?php if(is_array($version) || $version instanceof \think\Collection || $version instanceof \think\Paginator): if( count($version)==0 ) : echo "" ;else: foreach($version as $key=>$vo): ?>
                        <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $data['version_id']): ?>selected<?php endif; ?>><?php echo $vo['version']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                   </select>
                    <span class="err" id="err_type"></span>
                    <p class="notic">设置次机器的游戏版本</p>
                </dd>
            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="machine_name"><em>*</em>贩卖机赔率</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="odds" value="<?php echo $data['odds']; ?>" id="odds" class="input-txt">
                    <span class="err" id="err_machine_name"></span>
                    <p class="notic">填入整数</p>
                </dd>
            </dl>
             <!--  <dl class="row">
                <dt class="tit">
                    <label for="machine_admin">机台管理员</label>
                </dt>
                <dd class="opt">
                    <select name="machine_admin" id="machine_admin"  class="class-select valid">
                    <?php if(is_array($machine_admin) || $machine_admin instanceof \think\Collection || $machine_admin instanceof \think\Paginator): if( count($machine_admin)==0 ) : echo "" ;else: foreach($machine_admin as $key=>$vo): ?>
                        <option value="<?php echo $vo['user_id']; ?>" <?php if($vo['user_id'] == $info['machine_admin']): ?>selected<?php endif; ?>><?php echo $vo['nickname']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                   </select>
                    <span class="err" id="err_type"></span>
                    <p class="notic">负责此机台的管理员</p>
                </dd>
            </dl> -->
            <div class="bot"><a href="JavaScript:void(0);" onclick="verifyForm()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function verifyForm(){
        var type_name = $.trim($('input[name="machine_name"]').val());
        var nickname = $.trim($('input[name="nickname"]').val());
        var mobile = $('input[name="mobile"]').val();
        //var password = $('input[name="password"]').val();
        var error ='';
        if(type_name == ''){
            error += "贩卖机名称不能为空\n";
        }
        // if(password == ''){
        //     error += "密码不能为空\n";
        // }
        // if(password.length<6 || password.length>16){
        //     error += "密码长度不正确\n";
        // }
        // if(!checkMobile(mobile) && mobile != ''){
        //     error += "手机号码填写有误\n";
        // }
        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        $('#machine').submit();
    }
</script>
</body>
</html>