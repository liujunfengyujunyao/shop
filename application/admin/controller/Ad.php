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
//            $machine_id = I("post.machine");
//            $rule_id = I('post.rule_ids');//hidden将rule_id传过来
            halt(I('post.'));
            $rule = DB::name('ad_rule')->alias('t1')->field("t1.*,t2.ad_code,t2.media_type")->join("__AD__ t2","t1.ad_id = t2.ad_id","LEFT")->where("t1.id in ($rule_id)")->select();
            /*发送协议*/
halt($rule);
            $adlist = [];
            foreach($rule as $k => $val){
                $adlist[$k]['adid'] = $val['ad_id'];
                $adlist[$k]['adurl'] = $val['ad_code'];
                $adlist[$k]['admd5'] = md5($val['ad_code']);
                $adlist[$k]['adtype'] = $val['media_type'];
                $adlist[$k]['repeattimes'] = $val['repeattimes'];
                $adlist[$k]['daytimeperiod'] = $val['daytimeperiod'];
                $adlist[$k]['datecycle'] = $val['datecycle'];
                $adlist[$k]['daycycle'] = $val['daycycle'];
                $adlist[$k]['timecycle'] = $val['timecycle'];
                $adlist[$k]['monopoly'] = $val['monopoly'];
            }



            $post = array(
                'msgtype' => 'ad_list',
                'commandid' => 8,
                'adlistid' => 1,
//                'adlisturl' => ,
            );
//            if ($rule['time_type'] == 0){//a
//
//                $add = array(
//                    'machine_id' => $machine_id,
////                    'domain_start' => $rule['']
//                );
//                DB::name('time_domain')->add();
//            }

        }else{
//            $id = I('get.id');
            $get = I('get.');
            $machine = DB::name('machine')->select();
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

            $this->assign('rule_ids',$get['ids']);
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


}