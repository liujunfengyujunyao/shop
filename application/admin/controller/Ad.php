<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-21
 */

namespace app\admin\controller;
use think\Page;
use think\Db;

class Ad extends Base
{
    public function ad()
    {
        $act = I('get.act', 'add');
        $ad_id = I('get.ad_id/d');
        $ad_info = array();
        if ($ad_id) {
            $ad_info = D('ad')->where('ad_id', $ad_id)->find();
//            halt($ad_info);
            $ad_info['start_time'] = date('Y-m-d', $ad_info['start_time']);
            $ad_info['end_time'] = date('Y-m-d', $ad_info['end_time']);
        }
        if ($act == 'add')
            $ad_info['pid'] = $this->request->param('pid');
        $position = D('ad_position')->select();
        $this->assign('info', $ad_info);
        $this->assign('act', $act);
        $this->assign('position', $position);
        return $this->fetch();
    }

    public function adList()
    {

        delFile(RUNTIME_PATH . 'html'); // 先清除缓存, 否则不好预览

        $Ad = M('ad');
        $pid = I('pid', 0);
        if ($pid) {
            $where['pid'] = $pid;
            $this->assign('pid', I('pid'));
        }
        $keywords = I('keywords/s', false, 'trim');
        if ($keywords) {
            $where['ad_name'] = array('like', '%' . $keywords . '%');
        }
        $count = $Ad->where($where)->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = $Ad->where($where)->order('pid desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $list = array();
        if ($res) {
//        	$media = array('图片','文字','flash');
            $media = array('图片', 'flash');
            foreach ($res as $val) {
                $val['media_type'] = $media[$val['media_type']];
                $list[] = $val;
            }
        }


        $ad_position_list = M('AdPosition')->getField("position_id,position_name,is_open");
        $this->assign('ad_position_list', $ad_position_list);//广告位
        $show = $Page->show();// 分页显示输出
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $pager);
        return $this->fetch();
    }

    public function position()
    {
        $act = I('get.act', 'add');
        $position_id = I('get.position_id/d');
        $info = array();
        if ($position_id) {
            $info = D('ad_position')->where('position_id', $position_id)->find();
        }
        $this->assign('info', $info);
        $this->assign('act', $act);
        return $this->fetch();
    }

    public function positionList()
    {
        $count = Db::name('ad_position')->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = Db::name('ad_position')->order('position_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('pager', $Page);
        return $this->fetch();
    }


    public function adHandle()
    {
        $data = I('post.');
//        $data['start_time'] = strtotime($data['begin']);
//        $data['end_time'] = strtotime($data['end']);
        $data['create_time'] = time();
        if ($data['act'] == 'add') {
            if($data['media_type'] == 1){//如果是视频就是ad_vidoe
                $data['ad_code'] = $data['ad_video'];
                unset($data['ad_video']);
            }

            $r = D('ad')->add($data);
        }
        if ($data['act'] == 'edit') {
            $r = D('ad')->where('ad_id', $data['ad_id'])->save($data);
        }

        if ($data['act'] == 'del') {
            $r = D('ad')->where('ad_id', $data['del_id'])->delete();
            if ($r) {
                $this->ajaxReturn(['status' => 1, 'msg' => "操作成功", 'url' => U('Admin/Ad/adList')]);
            } else {
                $this->ajaxReturn(['status' => -1, 'msg' => "操作失败"]);
            }
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Ad/adList');
        // 不管是添加还是修改广告 都清除一下缓存
        delFile(RUNTIME_PATH . 'html'); // 先清除缓存, 否则不好预览
        \think\Cache::clear();
        if ($r) {
            $this->success("操作成功", U('Admin/Ad/adList'));
        } else {
            $this->error("操作失败", $referurl);
        }
    }
//    public function adHandle(){
//    	$data = I('post.');
//    	$adurl = "http://www.goldenbrother.cn" . $data['ad_code'];
////    	dump($data);
//        /*写时间冲突判断*/
//
//
//    	$data['start_time'] = strtotime($data['begin']);
//    	$data['end_time'] = strtotime($data['end']);
//
//    	if($data['act'] == 'add'){
////    		$r = D('ad')->add($data);
//            if($data['time_type'] == "a"){//连续时间段
//                $txt = array(
//                    'adid' => $r,//广告的ID
//                    'adurl' => $adurl,//素材下载地址
//                    'admd5' => md5($adurl),//素材MD5
//                    'adtype' => $data['media_type'],//1图片,2视频
//                    'repeattimes' => $data['plays'],//每次播放重复次数
//                    'monopoly' => $data['monopoly'],//1独占,2非独占
//                    'daytimeperiod' => $data['begin1'] . "-" . $data['end1'],
//                    'datecycle' => NULL,
//                    'daycycle' => NULL,
//                    'timecycle' => NULL,
//
//                );
//
//
//            }elseif($data['time_type'] == "b"){
//                $txt = array(
//                    'adid' => $r,
//                    'adurl' => $adurl,
//                    'admd5' => md5($adurl),
//                    'adtype' => $data['media_type'],
//                    'repeattimes' => $data['plays'],
//                    'monopoly' => $data['monpoly'],
//                    'daycycle' => $data['begin2'] . "-" . $data['end2'],
//                    'timecycle' => $data['begin-hour2'] . "-" . $data['end-hour2'],
//                    'daytimeperiod' => NULL,
//                    'datecycle' => NULL,
//                );
//
//            }else{
//                $txt = array(
//                    'adid' => $r,//广告的ID
//                    'adurl' => $adurl,//素材下载地址
//                    'admd5' => md5($adurl),//素材MD5
//                    'adtype' => $data['media_type'],//1图片,2视频
//                    'repeattimes' => $data['plays'],//每次播放重复次数
//                    'monopoly' => $data['monopoly'],//1独占,2非独占
//                    'datecycle' => $data['week'],//[1,3,7]表示周一、周三和周日
//                    'timecycle' => $data['begin-hour3'] . "-" . $data['end-hour3'],
//                    'daytimeperiod' => NULL,
//                    'daycycle' => NULL,
//                );
//            }
////            halt($txt);
////            $txt = json_encode($txt,JSON_UNESCAPED_UNICODE);dump($txt);
////            fopen("./public/ad_json/" . time() .".txt",$txt);
//            $filename = "./public/adjson/" . time() .".txt";
////            $log = array(
////              'adlistid' =>
////            );
////            halt($filename);
//            file_put_contents($filename,$txt);die;
//    	}
//    	if($data['act'] == 'edit'){
//    		$r = D('ad')->where('ad_id', $data['ad_id'])->save($data);
//    	}
//
//    	if($data['act'] == 'del'){
//            $r = D('ad')->where('ad_id', $data['del_id'])->delete();
//            if($r){
//                $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>U('Admin/Ad/adList')]);
//            }else{
//                $this->ajaxReturn(['status'=>-1,'msg'=>"操作失败"]);
//            }
//    	}
//    	$referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Ad/adList');
//        // 不管是添加还是修改广告 都清除一下缓存
//        delFile(RUNTIME_PATH.'html'); // 先清除缓存, 否则不好预览
//        \think\Cache::clear();
//    	if($r){
//    		$this->success("操作成功",U('Admin/Ad/adList'));
//    	}else{
//    		$this->error("操作失败",$referurl);
//    	}
//    }

    public function positionHandle()
    {
        $data = I('post.');
        if ($data['act'] == 'add') {
            $r = M('ad_position')->add($data);
        }

        if ($data['act'] == 'edit') {
            $r = M('ad_position')->where('position_id', $data['position_id'])->save($data);
        }

        if ($data['act'] == 'del') {
            if (M('ad')->where('pid', $data['position_id'])->count() > 0) {
                $this->error("此广告位下还有广告，请先清除", U('Admin/Ad/positionList'));
            } else {
                $r = M('ad_position')->where('position_id', $data['position_id'])->delete();
                if ($r) exit(json_encode(1));
            }
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Ad/positionList');
        if ($r) {
            $this->success("操作成功", $referurl);
        } else {
            $this->error("操作失败", $referurl);
        }
    }

    public function changeAdField()
    {
        $field = $this->request->request('field');
        $data[$field] = I('get.value');
        $data['ad_id'] = I('get.ad_id');
        M('ad')->save($data); // 根据条件保存修改的数据
    }

    /**
     * 编辑广告中转方法
     */
    public function editAd()
    {
        \think\Cache::clear();
        $request_url = urldecode(I('request_url'));
        $request_url = U($request_url, array('edit_ad' => 1));
        echo "<script>location.href='" . $request_url . "';</script>";
        exit;
    }


    //播放规则列表
    public function ruleList()
    {
        $Ad = M('ad_rule');
//        $pid = I('pid', 0);
//        if ($pid) {
//            $where['pid'] = $pid;
//            $this->assign('pid', I('pid'));
//        }
        $keywords = I('keywords/s', false, 'trim');
        if ($keywords) {
            $where['rule_name'] = array('like', '%' . $keywords . '%');
        }
        $count = $Ad->where($where)->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
//        $res = $Ad->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $res = $Ad->alias('t1')->field('t1.*,t2.ad_code')->join("__AD__ t2","t1.ad_id = t2.ad_id")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $list = array();
//        halt($res);
        if ($res) {
//        	$media = array('图片','文字','flash');
            $media = array('连续日期时间段','连续日期周期时间段', '特定日期周期时间段');
            foreach ($res as $val) {
                $val['rule_type'] = $media[$val['time_type']];
                $list[] = $val;
            }
        }


//        $ad_position_list = M('AdPosition')->getField("position_id,position_name,is_open");
//        $this->assign('ad_position_list', $ad_position_list);//广告位
        $show = $Page->show();// 分页显示输出
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $pager);
        return $this->fetch();
    }

    public function rule()
    {
        $act = I('get.act', 'add');
        $ad_id = I('get.ad_id/d');
        $ad = DB::name('ad')->select();

        $this->assign('ad',$ad);
        $this->assign('act', $act);
        return $this->fetch();
    }

    public function ruleHandle()
    {

        $data = I('post.');

        if($data['monopoly'] == 2 && $data['plays'] == ""){
            $this->error('播放次数未填');
        }
        if ($data['act'] == 'add') {
//            $r = D('ad_rule')->add($data);

            if ($data['time_type'] == "a") {//连续时间段
                $add = array(

                    'repeattimes' => $data['plays'],//每次播放重复次数
                    'monopoly' => $data['monopoly'],//1独占,2非独占
                    'daytimeperiod' => $data['begin1'] . "-" . $data['end1'],
                    'ad_id' => intval($data['ad_id']),
                    'rule_name' => $data['rule_name'],
                    'time_type' => 0,
                );

                $r = DB::name('ad_rule')->add($add);
            } elseif ($data['time_type'] == "b") {
                $add = array(

                    'repeattimes' => $data['plays'],
                    'monopoly' => $data['monopoly'],
                    'daycycle' => $data['begin2'] . "-" . $data['end2'],
                    'timecycle' => $data['begin-hour2'] . "-" . $data['end-hour2'],
                    'ad_id' => intval($data['ad_id']),
                    'rule_name' => $data['rule_name'],
                    'time_type' => 1,
                );
                $r = DB::name('ad_rule')->add($add);
            } else {
                $add = array(
                    'repeattimes' => $data['plays'],//每次播放重复次数
                    'monopoly' => $data['monopoly'],//1独占,2非独占
                    'datecycle' => implode(',', $data['week']),//[1,3,7]表示周一、周三和周日
                    'timecycle' => $data['begin-hour3'] . "-" . $data['end-hour3'],
                    'ad_id' => intval($data['ad_id']),
                    'rule_name' => $data['rule_name'],
                    'time_type' => 2,
                );
                $r = DB::name('ad_rule')->add($add);
            }
            if ($data['act'] == 'edit') {
                $r = D('ad_rule')->where('id', $data['ad_id'])->save($data);
            }

            if ($data['act'] == 'del') {
                $r = D('ad_rule')->where('id', $data['del_id'])->delete();
                if ($r) {
                    $this->ajaxReturn(['status' => 1, 'msg' => "操作成功", 'url' => U('Admin/Ad/ruleList')]);
                } else {
                    $this->ajaxReturn(['status' => -1, 'msg' => "操作失败"]);
                }
            }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Ad/ruleList');
//        // 不管是添加还是修改广告 都清除一下缓存
//        delFile(RUNTIME_PATH.'html'); // 先清除缓存, 否则不好预览
//        \think\Cache::clear();
            if ($r) {
                $this->success("操作成功", U('Admin/Ad/ruleList'));
            } else {
                $this->error("操作失败", $referurl);
            }

        }

    }


    /*
     *  将广告和规则告知设备
     * */
    public function api(){
        if(IS_POST){
            $machine_id = I("post.machine/a");//machine_id是数组
//$machine_id=[12];
            $rule_id = I('post.rule_ids');//hidden将rule_id传过来

            $rule = DB::name('ad_rule')->alias('t1')->field("t1.*,t2.ad_code,t2.media_type")->join("__AD__ t2","t1.ad_id = t2.ad_id","LEFT")->where("t1.id in ($rule_id)")->select();
            /*发送协议*/

            $adlist = [];
            foreach($rule as $k => $val){
                $adlist[$k]['adid'] = intval($val['ad_id']);
                $adlist[$k]['adurl'] = $val['ad_code'];
                $adlist[$k]['admd5'] = md5($val['ad_code']);
                if($val['time_type'] == 0){
                    $adlist[$k]['time_type'] = "a";
                }elseif($val['time_type'] == 1){
                    $adlist[$k]['time_type'] = "b";
                }else{
                    $adlist[$k]['time_type'] = "c";
                }
                if($val['media_type'] == 0){
                    $adlist[$k]['adtype'] = 1;
                }else{
                    $adlist[$k]['adtype'] = 2;
                }

                if(!is_null($val['repeattimes'])){
                    $adlist[$k]['repeattimes'] = intval($val['repeattimes']);
                }
                if(!is_null($val['daytimeperiod'])){
//                    $adlist[$k]['daytimeperiod'] = $val['daytimeperiod'];
                    $six = explode("-",$val['daytimeperiod']);
                    $start[0] = $six[0];
                    $start[1] = $six[1];
                    $start[2] = $six[2];
                    $start = implode('-',$start);
                    $start = strtotime($start);
                    $end[0] = $six[3];
                    $end[1] = $six[4];
                    $end[2] = $six[5];
                    $end = implode('-',$end);
                    $end = strtotime($end);
//                    $adlist[$k]['daytimeperiod'] = explode("-",$val['daytimeperiod']);
                    $adlist[$k]['daytimeperiod'] = $start . "-" . $end;
                }
                if(!is_null($val['datecycle'])){
                    $adlist[$k]['datecycle'] = explode(",",$val['datecycle']);
                }
                if(!is_null($val['daycycle'])){
//                    $adlist[$k]['daycycle'] = $val['daycycle'];
                    $six1 = explode("-",$val['daycycle']);
                    $start1[0] = $six1[0];
                    $start1[1] = $six1[1];
                    $start1[2] = $six1[2];
                    $start1 = implode('-',$start1);
                    $start1 = strtotime($start1);
                    $end1[0] = $six1[3];
                    $end1[1] = $six1[4];
                    $end1[2] = $six1[5];
                    $end1 = implode('-',$end1);
                    $end1 = strtotime($end1);
//                    $adlist[$k]['daytimeperiod'] = explode("-",$val['daytimeperiod']);
                    $adlist[$k]['daycycle'] = $start . "-" . $end;
                }
                if(!is_null($val['timecycle'])){
                    $adlist[$k]['timecycle'] = $val['timecycle'];
                }


                $adlist[$k]['monopoly'] = intval($val['monopoly']);
            }
//            halt($adlist);

            $add = array(
                'machine_id' => implode(',',$machine_id),
                'adlist' => serialize($adlist),
            );

            $adlistid = DB::name('adlist')->add($add);

            $json = array(
                'adlistid' => intval($adlistid),
                'adnumber' => intval(count($rule)),
                'adlist' => $adlist,
            );
            $txt = json_encode($json,JSON_UNESCAPED_UNICODE);
            $time = time();
            $filename = "./public/adjson/" . $time .".txt";
            file_put_contents($filename,$txt);
//            $post = array(
//                'msgtype' => 'ad_list',
//                'commandid' => "",
//                'adlistid' => $adlistid,
//                'adlisturl' => "http://www.goldenbrother.cn/public/adjson/".$time.".txt",
////
//            );


            foreach($machine_id as $v){
                $machinesn = DB::name('machine')->where(['machine_id'=>$v])->getField('sn');
                //发送给所有设备
                $add_command = array(
                    'machine_id' => $v,
                    'msgtype' => 'ad_list',
                    'send_time' => time(),
                    'content' => json_encode($json,JSON_UNESCAPED_UNICODE),
                );

                $number = DB::name('command')->add($add_command);

                $msg = array(
                    'msgtype' => 'ad_list',
                    'commandid' => intval($number),
                    'adlistid' => intval($adlistid),
//                    'adlisturl' => "http://www.goldenbrother.cn/public/adjson/".$time.".txt",
                    'adlisturl' => "http://192.168.1.144/public/adjson/".$time.".txt",
                );
                $post = array(
                    'msg'=>$msg,
                    'msgtype'=>'send_message',
                    'machinesn'=>intval($machinesn),
                );
                $url = 'https://www.goldenbrother.cn:23232/account_server';
                $res = post_curls($url,$post);
            }



        }else{
//            $id = I('get.id');
            $get = I('get.');
            $rule_ids = $get['ids'];
            if($rule_ids == ""){
                $rule_ids = $get['id'];
            }
            $rule = DB::name('ad_rule')->where("id in ($rule_ids)")->select();
//            halt($rule);
            $monopoly = DB::name('ad_rule')->where("id in ($rule_ids) and monopoly = 1")->select();
            $count = count($monopoly);
            if($count > 1){//独占大于两个要检查时间冲突
                $type2 = DB::name('ad_rule')->where("id in ($rule_ids) and monopoly = 1 and time_type = 2")->select();
                $type1 = DB::name('ad_rule')->where("id in ($rule_ids) and monopoly = 1 and time_type = 1")->select();
                $type0 = DB::name('ad_rule')->where("id in ($rule_ids) and monopoly = 1 and time_type = 0")->select();
                $error1 = "检测到".$count."个独占规则存在";
                $error2 = "其中连续日期时间段存在:".count($type0)."个";
                $error3 = "连续日期周期时间段:".count($type1)."个";
                $error4 = "特定日期周期时间段:".count($type2)."个";
//                $error = [$error1,$error2,$error3,$error4];


                if(count($type2) > 1){//检查同为执行规则c独占广告的星期是否重合
                    $week = [];
                    foreach ($type2 as $key => $value){
                        $week[$key] = explode(',',$value['datecycle']);

                    }



                }
            }
            foreach ($rule as $key => $value){
                if($value['monopoly'] == 1){//1独占  2非独占
                }
            }

            $machine = DB::name('machine')->where(['is_online'=>1])->select();
//
//            $rule = DB::name('ad_rule')->where(['id'=>$id])->find();

//            $time_type = $rule['time_type'];
//            if ($time_type == 0){
//                $daytimeperiod = explode('-',$rule['daytimeperiod']);
//                $start = array(
//                  $daytimeperiod[0],
//                  $daytimeperiod[1],
//                  $daytimeperiod[2],
//                );
//                $start = implode('-',$start);
//                $end = array(
//                    $daytimeperiod[3],
//                    $daytimeperiod[4],
//                    $daytimeperiod[5],
//                );
//                $end = implode('-',$end);
//                $start_time = strtotime($start);
//                $end_time = strtotime($end);//$start_time - $end_time 为此条规则的时间域
//
////                $test =$this->is_time_cross(1545843448,1545926248,1545879448,1545883048);//34在12区间内返回true
////                $test =$this->is_time_cross(1545843448,1545926248,NULL,NULL);//34不在12区间返回false
////                halt($test);
//                $domain = DB::name('ad_time_domain')->select();
//                foreach($domain as $key => $value){
////
//                        if($this->is_time_cross($start_time,$end_time,$value['doamin_start'],$value['domain_end'])===false){
//                            $machine_ids[$key] = $value['machine_id'];
//                        }
////
//                }
//                $machine_ids = implode(',',$machine_ids);
//                $machine = DB::name('machine')->field('machine_id,is_online')->where("machine_id in ($machine_ids)")->select();
//
//            }elseif($time_type == 1){
//
//            }else{
//
//            }









//            $where = array(
//                'is_online' => 1,
//            );
//            $machine = DB::name('machine')->where($where)->select();

            //每一条被分配的规则计入ad_time_domain
            $this->assign('error1',$error1);
            $this->assign('error2',$error2);
            $this->assign('error3',$error3);
            $this->assign('error4',$error4);
            $this->assign('rule_ids',$rule_ids);
            $this->assign('list',$machine);
            return $this->fetch();
        }


    }


    public function is_time_cross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '')
    {
        $status = $beginTime2 - $beginTime1;
        if ($status > 0)
        {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 >= 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function getRepeat($arr) {

    // 获取去掉重复数据的数组
    $unique_arr = array_unique ( $arr );
    return $unique_arr;
    // 获取重复数据的数组
    $repeat_arr = array_diff_assoc ( $arr, $unique_arr );

    return $repeat_arr;
}

public function score(){
//       $time = strtotime(date());
//    date_default_timezone_set('UTC');//'Asia/Shanghai' 亚洲/上海
        $time = strtotime("now");
        $date = gmdate("Y-m-d H:i:s",$time);
        halt($date);
        $time = $this->gmtime();
        halt($time);
}
public function gmtime(){
    return (time() - date('Z'));
}





}