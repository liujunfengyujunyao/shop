<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"./application/admin/view2/machine\optionMachine.html";i:1535099298;s:44:"./application/admin/view2/public\layout.html";i:1533876247;}*/ ?>
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

<!--编辑商品-->
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>商品列表</h3>
        <h5></h5>
      </div>
        <a href=""><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
    <form action="" id="search-form2" class="navbar-form form-inline" method="post" onSubmit="return false">
      <div class="sDiv">
        <div class="sDiv2">           
          <select name="cat_id" id="cat_id" class="select">
            <option value="">所有分类</option>
            <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $k=>$v): ?>
                <option value="<?php echo $v['id']; ?>"> <?php echo $v['name']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <select name="brand_id" id="brand_id" class="select">
            <option value="">所有品牌</option>
                <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>
                   <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>          
          <select name="is_on_sale" id="is_on_sale" class="select">
            <option value="">全部</option>                  
            <option value="1">上架</option>
            <option value="0">下架</option>
          </select>
            <select name="intro" class="select">
                <option value="0">全部</option>
                <option value="is_new">新品</option>
                <option value="is_recommend">推荐</option>
            </select>     

            <!--排序规则-->
            <input type="hidden" name="orderby1" value="goods_id" />
            <input type="hidden" name="orderby2" value="desc" />
            <input type="hidden" name="machine_id" value="<?php echo $data['machine_id']; ?>">
          <input type="text" size="30" name="key_word" class="qsbox" placeholder="搜索词...">
          <input type="button" onClick="ajax_get_table('search-form2',1)" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="sign" axis="col6" onclick="checkAllSign(this)">
                <div style="width: 24px;"><i class="ico-check"></i></div>
              </th>
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: left; width:65px;" class="">操作</div>
              </th>              
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: left; width:50px;" class="" onClick="sort('goods_id');">id</div>
              </th>
              <th align="left" abbr="ac_id" axis="col4" class="">
                <div style="text-align: left; width: 300px;" class="" onClick="sort('goods_name');">商品名称</div>
              </th>
              <th align="center" abbr="article_show" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('goods_sn');">货号</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('cat_id');">分类</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('shop_price');">价格</div>
              </th>                  
            <!--   <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 30px;" class="" onClick="sort('is_recommend');">推荐</div>
              </th>                       
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 30px;" class="" onClick="sort('is_new');">新品</div>
              </th>                                     
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 30px;" class="" onClick="sort('is_hot');">热卖</div>
              </th>  
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('is_on_sale');">上/下架</div>
              </th> -->
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('store_count');">本机库存</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('store_count');">存放位置</div>
              </th>
              <!-- <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('sort');">排序</div>
              </th>  -->                     
              <th style="width:100%" axis="col7">
                <div></div>
              </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>    
    <div class="tDiv">
     <!--  <div class="tDiv2">
        <div class="fbutton">       
          <a href="<?php echo U('Admin/goods/addEditGoods'); ?>">
          <div class="add" title="添加商品">
            <span><i class="fa fa-plus"></i>添加商品</span>
          </div>
          </a>          
          </div> 
      </div> -->
      <div style="clear:both"></div>
    </div>
    <div class="bDiv" style="height: auto;">
     <!--ajax 返回 --> 
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0" data-url="<?php echo U('admin/goods/delGoods'); ?>"></div>
    </div>

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

    //ajax获取商品列表
      $(document).ready(function(){
        // 刷选条件 鼠标 移动进去 移出 样式
        $(".hDivBox > table > thead > tr > th").mousemove(function(){
            $(this).addClass('thOver');
        }).mouseout(function(){
            $(this).removeClass('thOver');
        });

        // 表格行点击选中切换
        $(document).on('click','#flexigrid > table>tbody >tr',function(){
            $(this).toggleClass('trSelected');
            var checked = $(this).hasClass('trSelected');
            $(this).find('input[type="checkbox"]').attr('checked',checked);
        });
    });

    $(document).ready(function () {
        // ajax 加载商品列表
        ajax_get_table('search-form2', 1);

    });

    // ajax 抓取页面 form 为表单id  page 为当前第几页
    function ajax_get_table(form, page) {
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type: "POST",
            url: "/index.php?m=Admin&c=machine&a=ajaxGoodsList&p=" + page,//+tab,
            data: $('#' + form).serialize(),// 你的formid
            success: function (data) {
                $("#flexigrid").html('');
                $("#flexigrid").append(data);
            }
        });
    }
    
        // 点击排序
        function sort(field)
        {
           $("input[name='orderby1']").val(field);
           var v = $("input[name='orderby2']").val() == 'desc' ? 'asc' : 'desc';             
           $("input[name='orderby2']").val(v);
           ajax_get_table('search-form2',cur_page);
        }
</script>
</body>
</html>