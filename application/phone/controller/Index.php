<?php

namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use think\Db;
use think\Controller;
use think\Session;
set_time_limit(0);
class Index extends Base {

    public function index(){
        //微信登陆 自动获取用户ID;
        $manager_info= $_SESSION['think']['manager_info'];
        //如果belong_id有值 证明为二级员工
        $belong_id = DB::name('admin')->where(['admin_id'=>$manager_info['admin_id']])->getField('belong_id');
        if($belong_id){
            //员工 : 查找所属群组内的机器IDS集合
//            $client_ids = DB::name('admin')->alias('a')->join("__MACHINE_GROUP__ g","a.group_id = g.id",'LEFT')->where(['a.admin_id'=>$manager_info['admin_id']])->getField('g.group_machine');
            $group_ids = M('admin')->where(['admin_id'=>$manager_info['admin_id']])->getField('group_id');
            $client_arr = M('machine')->where("group_id in ($group_ids)")->getField('machine_id',true);
            $client_ids = implode(',',$client_arr);
        }else{
            $client_arr = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);

            $client_ids = implode($client_arr,',');
        }
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);//当天00:00点的时间戳
        $end = $morningTime-1;//前一天23:59:59的时间戳
        $star = $morningTime-60*60*24;//前一天0点的时间戳
        //测试前天的数据
        $start = $morningTime;
        $end =  $morningTime+60*60*24;



        if ($client_ids == "") {
            $client_ids = "-1";
        }
        // $data = DB::name('client_day_statistics')->where("machine_id in ({$client_ids})")->select();
        //出奖率 总收益 在线支付 礼品消耗数量 
        //总收入
        $all_count = DB::name('sell_log')->field("sum(amount) as all_count")->where("sell_time between $start and $end && machine_id in ($client_ids)")->find();

        /*12-17新增口红机&福袋机总收入*/
        $luck_ids = DB::name('machine')->where("machine_id in ($client_ids) && type_id = 2")->getField('machine_id',true);
        $luck_ids = implode(',',$luck_ids);
        if($luck_ids == ""){
            $luck_ids = "-1";
        }

        $luck_count = DB::name('sell_log')->field("sum(amount) as luck_count")->where("sell_time between $start and $end && machine_id in ($luck_ids)")->find();
        $luck_sell_number = DB::name('sell_log')->where("sell_time between $start and $end && machine_id in ($luck_ids) && usetype = 1")->count();//福袋机消耗的礼品数量
        /*----------------------------*/
        $lipstick_ids = DB::name('machine')->where("machine_id in ($client_ids) && type_id = 1")->getField('machine_id',true);
        $lipstick_ids = implode(',',$lipstick_ids);
        if($lipstick_ids == ""){
            $lipstick_ids = "-1";
        }
        $lipstick_count = DB::name('sell_log')->field("sum(amount) as lipstick_count")->where("sell_time between $start and $end && machine_id in ($lipstick_ids)")->find();//口红机总收入
        $game_success_number = DB::name('game_log')->where("end_time between $start and $end && machine_id in ($lipstick_ids) && result = 1")->count();//口红机游戏成功次数
        $game_all_number = DB::name('game_log')->where("end_time between $start and $end && machine_id in ($lipstick_ids)")->count();//口红机游戏总次数
        $lipstick_sell_number = DB::name('sell_log')->where("sell_time between $start and $end && machine_id in ($lipstick_ids) && usetype = 1")->count();//口红机售卖数量
        $lipstick_sell_number = intval($lipstick_sell_number + $game_success_number);//口红机消耗的礼品数量
        if ($game_all_number == 0){
            $lipstick_rate = 0;//除数为0
        }else{
            $lipstick_rate = $game_success_number/$game_all_number*100;
            $lipstick_rate = sprintf("%.2f", $lipstick_rate);//百分比
        }

        /*12-17新增口红机&福袋机总收入*/



        //网银收入(投币信号只有一种形态)
//        $online_count = DB::name('sell_log')->field("sum(amount) as online_count")->where("sell_time between $start and $end && paytype != 1 && machine_id in ($client_ids)")->find();
        //现金收入
        $offline_count = DB::name('sell_log')->field("sum(amount) as offline_count")->where("sell_time between $start and $end && paytype = 1 && machine_id in ($client_ids)")->find();
        
        //游戏成功次数
//        $success_number = DB::name('game_log')->field("count(id) as success_number")->where("end_time between $start and $end && result = 1 && machine_id in ($client_ids)")->find();
        //游戏失败次数
//        $fail_number = DB::name('game_log')->field("count(id) as fail_number")->where("end_time between $start and $end && result = 0 && machine_id in ($client_ids)")->find();
        //售卖数量
//        $sell_number = DB::name('sell_log')->field("count(id) as sell_number")->where("sell_time between $start and $end && usetype=1 && machine_id in ($client_ids)")->find();
//halt($client_ids);
        //在线机器数量
        $online_machine = DB::name('machine')->field("count(machine_id) as online_machine")->where("is_online=1 && machine_id in ({$client_ids})")->find();

//        if ($success_number['success_number'] == 0) {
//            $rate = 0;
//
//        }elseif($fail_number['fail_number']+$success_number['success_number'] == 0){
//            $rate = 0;
//
//        }else{
//
//            $game_count = $success_number['success_number']+$fail_number['fail_number'];
//            $rate = $success_number['success_number']/$game_count*100;
//        }
        
        $data = array(
//            'rate' => $rate,
            'rate' => $lipstick_rate,
            'all_count' => sprintf("%.2f", $all_count['all_count']),//总收益sprintf("%.2f", $num)
            'luck_count' => sprintf("%.2f", $luck_count['luck_count']),//福袋机的总收入
            'lipstick_count' => sprintf("%.2f", $lipstick_count['lipstick_count']),//口红机的总收入

            'luck_sell_number' => $luck_sell_number,//福袋机的礼品消耗

//            'online_count' => sprintf("%.2f", $online_count['online_count']),//线上收入
            'online_count' => 0,//线上收入
            'offline_count' => sprintf("%.2f", $offline_count['offline_count']),//线下收入
            'machine_count' => intval(count($client_arr)),//机器数量
//            'gift_out_number' => intval($sell_number['sell_number'] + $success_number['success_number']),//礼品消耗  sell_log+game_log

            'lipstick_sell_number' => $lipstick_sell_number ,//口红机的礼品消耗数量

            'online_machine' => intval($online_machine['online_machine']),
            );
        $one_date = $morningTime - 60*60*24*1;
        $two_date = $morningTime - 60*60*24*2;
        $three_date = $morningTime - 60*60*24*3;
        $four_date = $morningTime - 60*60*24*4;
        $five_date = $morningTime - 60*60*24*5;
        $six_date = $morningTime - 60*60*24*6;

        $one = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$one_date])->getField('rate');
        $two = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$two_date])->getField('rate');
        $three = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$three_date])->getField('rate');
        $four = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$four_date])->getField('rate');
        $five = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$five_date])->getField('rate');
        $six = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$six_date])->getField('rate');
        if (is_null($one)) {
            $one = 0;
        }if(is_null($two)){
            $two = 0;
        }if(is_null($three)){
            $three = 0;
        }if(is_null($four)){
            $four = 0;
        }if(is_null($five)){
            $five = 0;
        }if(is_null($six)){
            $six = 0;
        }
        $rate = array(
            $six,$five,$four,$three,$two,$one
            );
        $rate = json_encode($rate,true);

        session('history',$rate);
        

        $normal = DB::name('machine')->where(['client_id'=>$manager_info['admin_id'],'status'=>1])->getField("count(machine_id) as normal");//正常
        $fault = DB::name('machine')->where(['client_id'=>$manager_info['admin_id'],'status'=>2])->getField("count(machine_id) as fault");//异常

        if ($data['machine_count'] == 0) {
            $normal_rate = 0;
            $fault_rate = 0;
        }else{
            $normal_rate = $normal/$data['machine_count']*100;
            $fault_rate = $fault/$data['machine_count']*100;
        }
        
        
        $data['normal'] = $normal;
        $data['fault'] = $fault;
        $data['normal_rate'] = $normal_rate;
        $data['fault_rate'] = $fault_rate;

        $machine_ids = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);
        $machine_ids = implode(",",$machine_ids);
        
        if ($machine_ids == "") {
            $data['error_number'] = "";
        }else{
             $data['error_number'] = count(DB::name('error')->where("machine_id in ({$machine_ids}) and status = 0")->select());
        }
       
        
        // $history = array(
        //     $six['success_number']/$six['game_count']*100,
        //     $five['success_number']/$five['game_count']*100,
        //     $four['success_number']/$four['game_count']*100,
        //     $three['success_number']/$three['game_count']*100,
        //     $two['success_number']/$two['game_count']*100,
        //     $one['success_number']/$one['game_count']*100,
        //     );
        // $history = json_encode($history,true);
        // halt($history);

        // $rate = array()
       
        $data['rate'] = sprintf("%.2f",$data['rate']);
        $checkdate = array(
            date("m-d",strtotime("-6 day")),
            date("m-d",strtotime("-5 day")),
            date("m-d",strtotime("-4 day")),
            date("m-d",strtotime("-3 day")),
            date("m-d",strtotime("-2 day")),
            date("m-d",strtotime("-1 day")),
            );
        
        $checkdate = json_encode($checkdate,true);
        session('checkdate',$checkdate);
        $this->assign('power',explode(',',$manager_info['nav_list']));
        $this->assign('belong_id',$manager_info['belong_id']);
        $this->assign('rate',$rate);
        $this->assign('checkdate',$checkdate);
        $this->assign('data',$data);

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