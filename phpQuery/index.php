<?php
require 'QueryList.class.php';

$pattern = array("title" => array(".joke-title a", "text"),"href" => array(".joke-title a", "href"));
$url = "http://www.mahua.com/";
$qy = new QueryList($url, $pattern, '', '', 'utf-8');
$rs = $qy->jsonArr;
$ul = "<ul>";
foreach($rs as $v){
    $ul .= "<li><a href='".$v['href']."' target='_blank'>".$v['title']."</li>";
}
$ul .= "</ul>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <title>演示：phpQuery强大的采集器</title>
        <meta name="keywords" content="phpQuery,小偷采集" />
        <meta name="description" content="Jquery chosen一款选择插件，支持检索，多选，下面与大家分享下其使用示例，调用方法很简单，你可以很方便的应用到你的项目中。" />
        <link rel="stylesheet" type="text/css" href="http://www.sucaihuo.com/jquery/css/common.css" />

    </head>
    <body>
        <div class="head">
            <div class="head_inner clearfix">
                <ul id="nav">
                    <li><a href="http://www.sucaihuo.com">首 页</a></li>
                    <li><a href="http://www.sucaihuo.com/templates">网站模板</a></li>
                    <li><a href="http://www.sucaihuo.com/js">网页特效</a></li>
                    <li><a href="http://www.sucaihuo.com/php">PHP</a></li>
                    <li><a href="http://www.sucaihuo.com/site">精选网址</a></li>
                </ul>
                <a class="logo" href="http://www.sucaihuo.com"><img src="http://www.sucaihuo.com/Public/images/logo.jpg" alt="素材火logo" /></a>
            </div>
        </div>
        <div class="container">
            <div class="demo">
                <h2 class="title"><a href="http://www.sucaihuo.com/js/178.html">教程：phpQuery强大的采集器</a></h2>
                <p class="notice">提示：从快乐麻花http://www.mahua.com/首页采集下来的笑话列表</p>
               <?php echo $ul;?>
            </div>
        </div>
        <div class="foot">
            Powered by sucaihuo.com  本站皆为作者原创，转载请注明原文链接：<a href="http://www.sucaihuo.com" target="_blank">www.sucaihuo.com</a>
        </div>
        <script type="text/javascript" src="http://www.sucaihuo.com/Public/js/other/jquery.js"></script> 
    </body>
</html>



