<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"./application/admin/view2/machine\_addEditType.html";i:1534996559;s:44:"./application/admin/view2/public\layout.html";i:1533876247;}*/ ?>
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
        /*最外层容器*/
        #tableContainer{
            width: 300px;
            height: 300px;
            margin: 20px auto;
            list-style: none;
        }
        .li {
            width: 11.1%;
            height: 11.1%;
            box-sizing: border-box;
            float:left;
            text-align: center;
            margin: 0 ;
            padding: 0;
        }
        .static {
            border: 1px solid green;
            /*background: red;*/
        }
        /*.active {
            border: 1px solid red;
            background: red;
        }*/
    </style>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>贩卖机类型管理 - 编辑贩卖机类型</h3>
                <h5>贩卖机类型管理</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="storetype" method="post">
        <input type="hidden" name="act" value="<?php echo $act; ?>">
        <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="type_name"><em>*</em>分类名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="type_name" value="<?php echo $info['type_name']; ?>" id="type_name" class="input-txt">
                    <span class="err" id="err_type_name"></span>
                    <p class="notic">设置贩卖机类型名称</p>
                </dd>
            </dl>
               <dl class="row">
                <dt class="tit">
                    <label for="count_value"><em>*</em>总价值(￥)</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="count_value" value="<?php echo $info['count_value']; ?>" id="count_value" class="input-txt">
                    <span class="err" id="err_count_value"></span>
                    <p class="notic">设置贩卖机价值</p>
                </dd>
            </dl>
        <!--     <dl class="row">
                <dt class="tit">
                    <label for="count_value"><em>*</em>规定种类数量</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="goods_count" value="<?php echo $info['goods_count']; ?>" id="goods_count" class="input-txt">
                    <span class="err" id="err_count_value"></span>
                    <p class="notic">设置贩卖机固定容纳种类数量</p>
                </dd>
            </dl> -->
               <dl class="row">
                <dt class="tit">
                    <label for="allSelect"><em>*</em>规定规格</label>
                </dt>
                <dd class="opt" id="tableContainer">
                   
                </dd>
            </dl>

<!--             <div id="tableContainer">   
        

    </div> -->
        <dl class="row">
                <dt class="tit">
                    <label for="brief">备注</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="brief" value="<?php echo $info['brief']; ?>" id="brief" class="input-txt">
                    <span class="err" id="err_brief"></span>
                    <p class="notic">可填写规格、售卖种类等</p>
                </dd>
            </dl>

        <div class="bot"><a href="JavaScript:void(0);" onclick="verifyForm()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>

</div>
<script type="text/javascript">
    function verifyForm(){
        var type_name = $.trim($('input[name="type_name"]').val());
        var count_value = $.trim($('input[name="count_value"]').val());
        var error ='';
        if(type_name == ''){
            error += "贩卖机名称不能为空\n";
        }
        if(count_value == ''){
            error += "贩卖机总价值不能为空\n";
        }
        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        $('#storetype').submit();
    }

        //规定规格
        $(function(){
        // 接口相对路径
        // var serverUrl = '/ok';

        // $('#tableContainer').attr('action',serverUrl);

        var row = 9;
        var column = 9;
        var titleColumn = ['Z','A','B','C','D','E','F','G','H'];
        var titleRow = [0,1,2,3,4,5,6,7,8];
        for(var i = 0; i < row; i++){
            for(var k = 0; k < column;k++){
                var $li = $('<input  type="text" name="allSelect[]" disabled class="li static">');
                
                if(i == 0 && k != 0){
                    
                    $li.val(titleRow[k])
                }
                if(k == 0 && i !=0){
                    
                    $li.val(titleColumn[i]);
                }
                if(i != 0 && k!=0)
                {
                    var $li = $('<input  type="checkbox" name="allSelect[]" class="li static">');

                }
                if(i != 0 && k != 0 )
                {
                    (function(i,k){
                        var location = titleColumn[i] + titleRow[k];
                        $li.click(function(){
                        // console.log($(this).attr('location'));
                        $(this).val(location)
                        console.log($(this).val())
                        })
                    })(i,k)

                }
                
                // console.log(location);
                $li.attr('location',location)
                // console.log(123)
                $('#tableContainer').append($li);
            }
        }
        // var $submit = $('<input type="submit" value="send">')
        //     $('#tableContainer').append($submit);
        })
</script>
</body>
</html>