<?php

namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use Think\Db;
class Index extends Base {

    public function index(){
        //微信登陆 自动获取用户ID;
        // halt($_SESSION);
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
       
    //测试微信H5支付
    public function test(){
        return $this->fetch();
    }

    public function wait(){
        $machine_id = I('get.machine_id');//选择更改策略后 按button
        $add = array(
            'msgtype' => 'change_priority',
            'machine_id' => $machine_id,
            'send_time' => time(),
            );
        $commandid = DB::name('command')->add($add);
        $machine = DB::name('machine')->where(['machine_id'=>$machine_id])->find();//$machine_id是手机管理端传过来的

        $prices = DB::name('client_machine_conf')
                ->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
                ->where(['machine_id'=>$machine['machine_id']])
                ->select();
        //手机管理端修改应用的模式 设备为准->平台为准 手机管理端发送 (发送)
        if($machine['priority'] == 0){//修改完为1
        $msg = array(
            'msgtype' => 'change_priority',
            'commandid' => $commandid,
            'priority' => 1,
            'gameprice' => $machine['game_price'],
            'singleprice' => $machine['goods_price'],
            'singleodds' => $machine['odds'],
            'prices' => $prices,
            );
            $priority = 1;
            }else{
                $msg = array(
                    'msgtype' => 'change_priority',
                    'commandid' => $commandid,
                    'priority' => 0,
                    );
            $priority = 0;
            }
        $params = array(
            'msgtype' => 'send_message',
            'machinesn' => $machine['sn'],
            'msg' => $msg,
            );
        $url = "http://192.168.1.3/";
        $result = json_curl($url,$params);
        for($x=0; $x<=3; $x++){//轮询查找是否返回成功
            $command = DB::name('command')->where(['commandid'=>$commandid])->find();
            if ($command['status'] == 1) {//查询出对应的command   完成后删除此$commandid
                //执行成功
                DB::name('command')->where(['commandid'=>$commandid])->delete();
                DB::name('machine')->where(['machine_id'=>$machine_id])->save(['priority'=>$priority]);
                $data = ['返回结果','成功'];
            }elseif($command['status'] == 0){
                sleep(2);
            }else{
                DB::name('command')->where(['commandid'=>$commandid])->delete();
                $data = ['返回结果','请求超时'];
            }
            
        }
        $this->assign('data',$data);
        return $this->fetch();

    }

    //ajax请求
    public function edit_priority(){

    }

    public function oauth(){
        return $this->fetch();
    }
}