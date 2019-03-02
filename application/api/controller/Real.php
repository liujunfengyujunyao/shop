<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Real extends Controller{

    /*
     * 设备实时统计(小时 add)
     * 2019-1-10
     * 月初0点会自动生成tfs_machine_hour_stat_201902_1表
     * */
    //        $crontab = "0 */1 * * * wget --spider http://www.goldenbrother.cn/index.php/api/real/machine_hour_stat";每小时执行一次
    public function machine_hour_stat(){

        $time = date('Y-m-d - H',time());
//        $time = date('Y-m-d - H',1548242493);

        $stat_period = $this->findNum($time);//处理成2019011017这种形式

        $data_table = substr($stat_period,0,-4);//处理成201901这种形式

        $data_table_name = "machine_hour_stat_".$data_table."_1";

        /*
         * 检查是否存在此数据库 如果不存在运行存储过程
         * */
        $name = "tfs_" . $data_table_name;

        $isTable = DB()->query("SHOW TABLES LIKE '$name'");

        if( !$isTable ){

            $sql = "CREATE TABLE `$name` (
                  `id` int(11) unsigned NOT NULL auto_increment,
                  `machine_id` int(11) NOT NULL COMMENT '设备ID',
                  `stat_period` int(11) NOT NULL COMMENT '统计时间区间',
                  `total_income` int(11) NOT NULL default '0' COMMENT '总收入',
                  `game_count` int(11) default '0' COMMENT '游戏次数',
                  `game_income` int(11) default '0' COMMENT '游戏收入',
                  `game_win` int(11) default '0' COMMENT '游戏成功次数',
                  `sell_count` int(11) default '0' COMMENT '销售次数',
//                  `sell_number` int(11) default '0' COMMENT '销售数量',
                  `sell_income` int(11) default '0' COMMENT '销售收入',
                  `refund` int(11) default '0' COMMENT '退款金额',
                  PRIMARY KEY  (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;";
            DB()->query($sql);

        }

        $is_there = DB::name($data_table_name)->where("stat_period = $stat_period")->find();//是否存在这个时间段的数据

        if(is_null($is_there)){//无数据 插入

            $machine = DB::name('machine')->getField('machine_id',true);
            $last = date('Y-m-d - H',time()-60*60);
//            $last = date('Y-m-d - H',1548242493-60*60);

            $last = $this->findNum($last);//处理成2019011017这种形式

            $last_data = DB::name($data_table_name)->where(['stat_period'=>$last])->find();

            if(substr($stat_period, -2) == "00" || !$last_data){//如果是0点不继承 || 未找到数据?   500错判或者第一次启用这个表
                //新增的设备会在转天开始进行实时统计
                $machine = DB::name('machine')->getField('machine_id',true);
                foreach($machine as $k => $v){
                    DB::name($data_table_name)->add(['stat_period'=>$stat_period,'machine_id'=>$v]);
                }
                exit();
            }

            $before_data = DB::name($data_table_name)->where(['stat_period'=>$last])->select();

            foreach($before_data as $key => $value){
                /*
                 * 插入设备ID和统计时间区间
                 * 查出上一个小时的数据 修改stat_period 其余数据插入遍历插入
                */
                $value['stat_period'] = intval($stat_period);
                $value['id'] = "";
                DB::name($data_table_name)->add($value);
            }
        }

    }
    /*
     *基于设备实时统计 machine_hour_stat所生成的 设备日统计表
     *

     * */
    //$crontab = "0 2 * * * wget --spider http://www.goldenbrother.cn/index.php/api/real/machine_day_stat"; 每天凌晨2点执行一次
    public function machine_day_stat(){
        $time = date('Y-m-d',time()-60*60*24);//执行时间为凌晨  算出执行前一天的时间
//        $time = date('Y-m-d',1548266400-60*60*24);//执行时间为凌晨  算出执行前一天的时间
        $stat_period = $this->findNum($time);//处理成20190115形式

        $last_stat_period = intval($stat_period . 23);//2019011523
//halt($last_stat_period);

        $data_table_name = substr($stat_period,0,-2);//201901  (如果执行时间为2月1号需要查询 201901表)
        $data_table_name = "machine_hour_stat_".$data_table_name."_1";//基于tfs_machine_hour_stat_201901_1表查询数据


        $machine = DB::name($data_table_name)->where(['stat_period'=>$last_stat_period])->select();

        foreach($machine as $key => $value){
            $value['stat_period'] = intval($stat_period);
            $value['id'] = "";
            DB::name('machine_day_stat_2019_1')->add($value);
        }

        //修改machine_month_stat的数据



        $month_time = substr($stat_period,0,-2);
        $month = DB::name('machine_month_stat_2019')->where(['stat_period'=>$month_time])->select();

        if($month){
            foreach($month as $key => $value){
                foreach($machine as $k => $v)
                    if($value['machine_id'] == $v['machine_id']){
                        $save = array(
                            'total_income' => intval($value['total_income'] + $v['total_income']),
                            'game_count' => intval($value['game_count'] + $v['game_count']),
                            'game_income' => intval($value['game_income'] + $v['game_income']),
                            'game_win' => intval($value['game_win'] + $v['game_win']),
                            'sell_count' => intval($value['sell_count'] + $v['sell_count']),
//                            'sell_number' => intval($value['sell_number'] + $v['sell_number']),
                            'sell_income' => intval($value['sell_income'] + $v['sell_income']),
                            'refund' => intval($value['refund'] + $v['refund']),
                        );
                        DB::name('machine_month_stat_2019')->where(['stat_period'=>$month_time,'machine_id'=>$v['machine_id']])->save($save);
                    }

            }
        }

    }

    /*
     *基于设备日统计 machine_day_stat所生成的 设备月统计表
     *

     * */
    //$crontab = "0 2 1 * *  wget --spider http://www.goldenbrother.cn/index.php/api/real/machine_month_stat"; 每月1号 2点执行
    public function machine_month_stat2(){
        $time = date("Y-m-d",time());
//        $time = date("Y-m-d",1548950400);
        $stat_period = intval($this->findNum($time));
        $add_time = date("Y-m",time()-60*60*24);
//        $add_time = date("Y-m",1548950400-60*60*24);
        $add_time = intval($this->findNum($add_time));

        $machine = DB::name('machine')->getField('machine_id',true);
        foreach ($machine as $key => $value){
            $add[$key] = DB::name('machine_day_stat_2019_1')
                        ->field("sum(total_income) as total_income,sum(sell_count) as sell_count,sum(sell_income) as sell_income,sum(game_count) as game_count,sum(game_income) as game_income,sum(game_win) as game_win,sum(refund) as refund")
//                        ->field("sum(total_income) as total_income,sum(sell_count) as sell_count,sum(sell_number) as sell_number,sum(sell_income) as sell_income,sum(game_count) as game_count,sum(game_income) as game_income,sum(game_win) as game_win,sum(refund) as refund")
                        ->where("stat_period < $stat_period && machine_id = $value")//缺少>条件
                        ->find();
            $add[$key]['machine_id'] = $value;
            $add[$key]['stat_period'] = $add_time;
        }

        foreach ($add as $k => &$v){
            $v['total_income'] = intval($v['total_income']);
            $v['sell_count'] = intval($v['sell_count']);
//            $v['sell_number'] = intval($v['sell_number']);
            $v['sell_income'] = intval($v['sell_income']);
            $v['game_count'] = intval($v['game_count']);
            $v['game_income'] = intval($v['game_income']);
            $v['game_win'] = intval($v['game_win']);
            $v['refund'] = intval($v['refund']);
        }

        DB::name('machine_month_stat_2019')->insertAll($add);

    }

    public function machine_month_stat()
    {
        $time = date('Y-m', time());
        $stat_period = intval($this->findNum($time));

        $machine = DB::name('machine')->getField('machine_id', true);
        foreach ($machine as $key => $value) {
            DB::name('machine_month_stat_2019')->add(['stat_period'=>$stat_period,'machine_id'=>$value]);
        }
    }
    /*
    商户日统计
    日统计表实时更新
    */
    //$crontab = "30 2 * * * wget --spider http://www.goldenbrother.cn/index.php/api/real/client_day_stat"; 每天凌晨2点30执行一次
    public function client_day_stat(){
//        $client = DB::name('machine')->getField('client_id',true);
//        $client =  array_filter(array_unique($client));//删除重复元素和NULL
//        $time = date("Y-m-d",time());
//        $stat_period = intval($this->findNum($time));
//
//
//        $machine_time = date('Y-m-d',time()-60*60*24);//执行时间为凌晨  算出执行前一天的时间
////        $time = date('Y-m-d',1547658036-60*60*24);//执行时间为凌晨  算出执行前一天的时间
//        $machine_stat_period = $this->findNum($machine_time);//处理成20190115形式
//
//        $last_stat_period = intval($machine_stat_period . 23);//昨天晚上23点的设备实时统计
//        halt($last_stat_period);
//
//        $table_time = date('Y-m-d - H',time());
////        $time = date('Y-m-d - H',1546279200);
//
//        $table_stat_period = $this->findNum($table_time);//处理成2019011017这种形式
//
//        $data_table = substr($table_stat_period,0,-4);//处理成201901这种形式
//
//        $data_table_name = "machine_hour_stat_".$data_table."_1";
////        halt($data_table_name);
//        foreach($client as $key => $value){
//            $data[$key] = DB::name($data_table_name)->alias("t1")
//                ->field("t1.total_income,t1.game_count,t1.game_income,t1.game_income,t1.game_win,t1.sell_count,t1.sell_income,t1.refund,t2.client_id,t2.machine_id")
//                ->join("__MACHINE__ t2","t2.machine_id = t1.machine_id","LEFT")
//                ->where(['t2.client_id'=>$value,'t1.stat_period'=>$last_stat_period])
//                ->select();
//        }
//        halt($data);

        $client = DB::name('admin')->where("belong_id is null")->getField('admin_id',true);//顶级权限
        $client_ids = implode(',',$client);
        $time = date('Y-m-d',time()-60*60*24);
//        $time = date('Y-m-d',1548266400-60*60*24);
        $stat_period = intval($this->findNum($time));//被统计的那天

//halt($stat_period);

        $data = DB::name('machine_day_stat_2019_1')
                ->alias('t1')
                ->field('sum(t1.total_income) as total_income,sum(t1.game_count) as game_count,sum(t1.game_income) as game_income,sum(t1.game_win) as game_win,sum(t1.sell_count) as sell_count,sum(t1.sell_income) as sell_income,sum(t1.refund) as refund,t2.client_id,t1.stat_period')
//                ->field('sum(t1.total_income) as total_income,sum(t1.game_count) as game_count,sum(t1.game_income) as game_income,sum(t1.game_win) as game_win,sum(t1.sell_count) as sell_count,sum(t1.sell_number) as sell_number,sum(t1.sell_income) as sell_income,sum(t1.refund) as refund,t2.client_id,t1.stat_period')
                ->join("__MACHINE__ t2","t2.machine_id = t1.machine_id")
                ->where("t2.client_id in ($client_ids) && stat_period = $stat_period")
                ->group("t2.client_id")
                ->select();
//dump($data);

        $add = DB::name('client_day_stat_2019_1')->insertAll($data);

        //修改商户的月统计

        $month_time = substr($stat_period,0,-2);
        $month = DB::name('client_month_stat_2019')->where(['stat_period'=>$month_time])->select();

        if($month){
            foreach($month as $key => $value){
                foreach($data as $k => $v)
                if($value['client_id'] == $v['client_id']){
                    $save = array(
                        'total_income' => intval($value['total_income'] + $v['total_income']),
                        'game_count' => intval($value['game_count'] + $v['game_count']),
                        'game_income' => intval($value['game_income'] + $v['game_income']),
                        'game_win' => intval($value['game_win'] + $v['game_win']),
                        'sell_count' => intval($value['sell_count'] + $v['sell_count']),
//                        'sell_number' => intval($value['sell_number'] + $v['sell_number']),
                        'sell_income' => intval($value['sell_income'] + $v['sell_income']),
                        'refund' => intval($value['refund'] + $v['refund']),
                    );
                    DB::name('client_month_stat_2019')->where(['stat_period'=>$month_time,'client_id'=>$v['client_id']])->save($save);
                }

            }
        }


    }

    /*
     *  月初创建商户月统计数据
     *
     * */
    //$crontab = "2 0 1 * * wget --spider http://www.goldenbrother.cn/index.php/api/real/create_client_month"; 每月1号0点2分执行一次
    public function create_client_month(){
        $time = date('Y-m',time());
        $stat_period = intval($this->findNum($time));
        $client = DB::name('admin')->where("belong_id is null")->getField('admin_id',true);
        foreach($client as $key => $value){
            DB::name('client_month_stat_2019')->add(['stat_period'=>$stat_period,'client_id'=>$value]);
        }
    }




    public function findNum($str=''){
        $str=trim($str);
        if(empty($str)){return '';}
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(is_numeric($str[$i])){
                $result.=$str[$i];
            }
        }
        return $result;
    }

    //表名  条件   操作
    public function sql(){
        $params = file_get_contents('php://input');
        $params = json_decode($params,true);
        $table_name = $params['table_name'];
        $where = $params['where'];
        $op = $params['op'];
//        halt($op);
        $data = DB::name($table_name)->where($where)->$op();
        halt($data);
    }





}