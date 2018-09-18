<?php

namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use Think\Db;
class Index extends MobileBase {

    public function index(){
        //微信登陆 自动获取用户ID;
        return $this->fetch();
    }

    /**
     * 分类列表显示
     */
    public function categoryList(){
        return $this->fetch();
    }

    /**
     * 模板列表
     */
    public function mobanlist(){
        $arr = glob("D:/wamp/www/svn_tpshop/mobile--html/*.html");
        foreach($arr as $key => $val)
        {
            $html = end(explode('/', $val));
            echo "<a href='http://www.php.com/svn_tpshop/mobile--html/{$html}' target='_blank'>{$html}</a> <br/>";            
        }        
    }
    
    /**
     * 商品列表页
     */
    public function goodsList(){
        $id = I('get.id/d',0); // 当前分类id
        $lists = getCatGrandson($id);
        $this->assign('lists',$lists);
        return $this->fetch();
    }
    
    public function ajaxGetMore(){
    	$p = I('p/d',1);
        $where = ['is_recommend'=>1,'is_on_sale'=>1];
    	$favourite_goods = Db::name('goods')->where($where)->order('goods_id DESC')->page($p,C('PAGESIZE'))->cache(true,TPSHOP_CACHE_TIME)->select();//首页推荐商品
    	$this->assign('favourite_goods',$favourite_goods);
    	return $this->fetch();
    }
    
    //微信Jssdk 操作类 用分享朋友圈 JS
    public function ajaxGetWxConfig(){
    	$askUrl = I('askUrl');//分享URL
    	$weixin_config = M('wx_user')->find(); //获取微信配置
    	$jssdk = new JssdkLogic($weixin_config['appid'], $weixin_config['appsecret']);
    	$signPackage = $jssdk->GetSignPackage(urldecode($askUrl));
    	if($signPackage){
    		$this->ajaxReturn($signPackage,'JSON');
    	}else{
    		return false;
    	}
    }

    public function admin_index(){
        return $this->fetch();
    }
       
}