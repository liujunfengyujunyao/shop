<?php
namespace app\sever\controller;
use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/html;charset=utf-8");
class Ad extends Controller {
        public function check_timeout()
        {
            $ad_list = DB::name('adlist')->select();//查询
            $time = time();
            $rule = DB::name('ad_rule')->where("time_type != 2")->select();
            foreach ($rule as $key => &$value){
                $value['daycycle'] = strtotime(substr($value['daycycle'],20,19));
                if($value['daycycle'] < $time){
                    $value['del'] = 1;//已经过期
                }
            }

            $del_ids = array();
            foreach($rule as $k => $v){
                if($v['del']){
                    $del_ids[$k] = $v['id'];
                }
            }
            $del_ids = implode(',',$del_ids);//所有要删除的rule_id
halt($del_ids);
//            $del = DB::name('ad_rule')->where("id in ($del_ids)")->delete();

            //adlist 分成只有一条需要整条删除 /  包含多条需要删除其中N条   /  全部包含需要整条删除
            $one = DB::name('ad_list')->where("rule_id in ($del_ids)")->delete();
            foreach ($ad_list as $k1 => $v1){

            }
        }

}