<?php
/**
 * Created by PhpStorm.
 * User: LJF
 * Date: 2019/2/27
 * Time: 19:10
 */

namespace app\api\controller;

use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/html;charset=utf-8");
class Pc extends Controller
{
    public function machine_today()
    {
        if(IS_POST){
            $time = date('Y-m-d - H',time());

            $stat_period = $this->findNum($time);

            $params = file_get_contents('php://input');

            $params = json_decode($params,true);
//
            $machine_id = $params['machine_id'];
            if(!$machine_id){
                $result = array(
                    'errid' => 10001,
                    'errmsg' => "machine_id error",
                );
                return json($result);
            }

            $table = "machine_hour_stat_" . substr($stat_period,0,-4) . "_1";

            $data = DB::name($table)->where("machine_id = $machine_id and stat_period = $stat_period")->find();

            if($data['game_win'] == 0){
                $data['odds'] = 0;
            }else{
                $data['odds'] = $data['game_win'] / $data['game_count'];
            }

            $result = array(
                'total_income' => $data['total_income'],
                'game_count' => $data['game_count'],
                'game_income' => $data['game_income'],
                'game_win' => $data['game_win'],
                'sell_count' => $data['sell_count'],
                'sell_income' => $data['sell_income'],
                'odds' => $data['odds'],
            );

            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $morningTime= mktime(0,0,0,$m,$d,$y);


            $one_date = $morningTime - 60*60*24*1;
            $one = substr($this->findNum(date('Y-m-d - H',$one_date)),0,-2);

            $two_date = $morningTime - 60*60*24*2;
            $two = substr($this->findNum(date('Y-m-d - H',$two_date)),0,-2);

            $three_date = $morningTime - 60*60*24*3;
            $three = substr($this->findNum(date('Y-m-d - H',$three_date)),0,-2);

            $four_date = $morningTime - 60*60*24*4;
            $four = substr($this->findNum(date('Y-m-d - H',$four_date)),0,-2);

            $five_date = $morningTime - 60*60*24*5;
            $five = substr($this->findNum(date('Y-m-d - H',$five_date)),0,-2);

            $six_date = $morningTime - 60*60*24*6;
            $six = substr($this->findNum(date('Y-m-d - H',$six_date)),0,-2);


//            $one = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$one_date])->getField('rate');
            $one_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$one])->find();
            if($one_rate['game_win'] == 0){
                $one_rate = 0;
            }else{
                $one_rate = $one_rate['game_win'] / $one_rate['game_count'];
            }

//            $two = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$two_date])->getField('rate');
            $two_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$two])->find();
            if($two_rate['game_win'] == 0){
                $two_rate = 0;
            }else{
                $two_rate = $two_rate['game_win'] / $two_rate['game_count'];
            }



//            $three = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$three_date])->getField('rate');
            $three_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$three])->find();
            if($three_rate['game_win'] == 0){
                $three_rate = 0;
            }else{
                $three_rate = $three_rate['game_win'] / $three_rate['game_count'];
            }


//            $four = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$four_date])->getField('rate');
            $four_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$four])->find();
            if($four_rate['game_win'] == 0){
                $four_rate = 0;
            }else{
                $four_rate = $four_rate['game_win'] / $four_rate['game_count'];
            }


//            $five = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$five_date])->getField('rate');
            $five_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$five])->find();
            if($five_rate['game_win'] == 0){
                $five_rate = 0;
            }else{
                $five_rate = $one_rate['game_win'] / $five_rate['game_count'];
            }


//            $six = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$six_date])->getField('rate');
            $six_rate = DB::name('machine_day_stat_2019_1')->field("game_win,game_count")->where(['machine_id'=>$machine_id,'stat_period'=>$six])->find();
            if($six_rate['game_win'] == 0){
                $six_rate = 0;
            }else{
                $six_rate = $six_rate['game_win'] / $six_rate['game_count'];
            }


            if (is_null($one)) {
                $one_rate = 0;
            }if(is_null($two)){
                $two_rate = 0;
            }if(is_null($three)){
                $three_rate = 0;
            }if(is_null($four)){
                $four_rate = 0;
            }if(is_null($five)){
                $five_rate = 0;
            }if(is_null($six)){
                $six_rate = 0;
            }
//            $rate = array(
//                $six,$five,$four,$three,$two,$one
//            );

            $day = [intval(substr($six,-2)),intval(substr($five,-2)),intval(substr($four,-2)),intval(substr($three,-2)),intval(substr($two,-2)),intval(substr($one,-2))];

            $rate = array(
                $six_rate,
                $five_rate,
                $four_rate,
                $three_rate,
                $two_rate,
                $one_rate,
            );

            $rate = json_encode($rate,true);
            $result['day'] = $day;
            $result['rate'] = $rate;

            return json($result);

        }else{
            $result = array(
                'errid' => 10001,
                'errmsg' => "machine_id error",
            );
            return json($result);
        }
    }


    public function client_today()
    {
        if(IS_POST){
            $time = date('Y-m-d - H',time());

            $stat_period = $this->findNum($time);//2019022810

            $params = file_get_contents('php://input');

            $params = json_decode($params,true);
//
            $client_id = $params['client_id'];
            //判断为顶级admin_id or 有权限的管理员
            $is_belong = DB::name('admin')->where(['admin_id'=>$client_id])->getField('belong_id');
            if(is_null($is_belong)){
                true;
            }else{
                $client_id = $is_belong;
            }

            $machine_arr = DB::name('machine')->where(['client_id'=>$client_id])->getField("machine_id",true);

            if($client_id == 1){
                $machine_arr = DB::name('machine')->where("1=1")->getField('machine_id',true);
            }


            if(!$machine_arr){
                $result['total_income'] = 0;
                $result['sell_count'] = 0;
                $result['sell_income'] = 0;
                $result['game_count'] = 0;
                $result['game_win'] = 0;
                $result['odds'] = 0;
                return json($result);
            }
            $machine_ids = implode(",",$machine_arr);

            $table = "machine_hour_stat_" . substr($stat_period,0,-4) . "_1";//201902

            $total = DB::name($table)->where("machine_id in ($machine_ids) and stat_period = $stat_period")->select();
//          //总收入
            $result['total_income'] = (array_sum(array_map(create_function('$val', 'return $val["total_income"];'), $total)));
            //卖出数量
            $result['sell_count'] = (array_sum(array_map(create_function('$val', 'return $val["sell_count"];'), $total)));
            //卖出收入
            $result['sell_income'] = (array_sum(array_map(create_function('$val', 'return $val["sell_income"];'), $total)));
            //游戏次数
            $result['game_count'] = (array_sum(array_map(create_function('$val', 'return $val["game_count"];'), $total)));
            //游戏收入
            $result['game_income'] = (array_sum(array_map(create_function('$val', 'return $val["game_income"];'), $total)));
            //游戏成功次数
            $result['game_win'] = (array_sum(array_map(create_function('$val', 'return $val["game_win"];'), $total)));

            if($result['game_win'] == 0){
                $result['odds'] = 0;
            }else{
                $result['odds'] = $result['game_win'] / $result['game_count'];
            }

            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $morningTime= mktime(0,0,0,$m,$d,$y);


            $one_date = $morningTime - 60*60*24*1;
            $one = substr($this->findNum(date('Y-m-d - H',$one_date)),0,-2);

            $two_date = $morningTime - 60*60*24*2;
            $two = substr($this->findNum(date('Y-m-d - H',$two_date)),0,-2);

            $three_date = $morningTime - 60*60*24*3;
            $three = substr($this->findNum(date('Y-m-d - H',$three_date)),0,-2);

            $four_date = $morningTime - 60*60*24*4;
            $four = substr($this->findNum(date('Y-m-d - H',$four_date)),0,-2);

            $five_date = $morningTime - 60*60*24*5;
            $five = substr($this->findNum(date('Y-m-d - H',$five_date)),0,-2);

            $six_date = $morningTime - 60*60*24*6;
            $six = substr($this->findNum(date('Y-m-d - H',$six_date)),0,-2);


//            $one = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$one_date])->getField('rate');
            $one_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$one])->find();
            if($one_rate['game_win'] == 0){
                $one_rate = 0;
            }else{
                $one_rate = $one_rate['game_win'] / $one_rate['game_count'];
            }

//            $two = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$two_date])->getField('rate');
            $two_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$two])->find();
            if($two_rate['game_win'] == 0){
                $two_rate = 0;
            }else{
                $two_rate = $two_rate['game_win'] / $two_rate['game_count'];
            }



//            $three = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$three_date])->getField('rate');
            $three_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$three])->find();
            if($three_rate['game_win'] == 0){
                $three_rate = 0;
            }else{
                $three_rate = $three_rate['game_win'] / $three_rate['game_count'];
            }


//            $four = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$four_date])->getField('rate');
            $four_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$four])->find();
            if($four_rate['game_win'] == 0){
                $four_rate = 0;
            }else{
                $four_rate = $four_rate['game_win'] / $four_rate['game_count'];
            }


//            $five = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$five_date])->getField('rate');
            $five_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$five])->find();
            if($five_rate['game_win'] == 0){
                $five_rate = 0;
            }else{
                $five_rate = $one_rate['game_win'] / $five_rate['game_count'];
            }


//            $six = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$six_date])->getField('rate');
            $six_rate = DB::name('client_day_stat_2019_1')->field("game_win,game_count")->where(['client_id'=>$client_id,'stat_period'=>$six])->find();
            if($six_rate['game_win'] == 0){
                $six_rate = 0;
            }else{
                $six_rate = $six_rate['game_win'] / $six_rate['game_count'];
            }


            if (is_null($one)) {
                $one_rate = 0;
            }if(is_null($two)){
                $two_rate = 0;
            }if(is_null($three)){
                $three_rate = 0;
            }if(is_null($four)){
                $four_rate = 0;
            }if(is_null($five)){
                $five_rate = 0;
            }if(is_null($six)){
                $six_rate = 0;
            }
//            $rate = array(
//                $six,$five,$four,$three,$two,$one
//            );
//            $rate = array(
//                $six => $six_rate,
//                $five => $five_rate,
//                $four => $four_rate,
//                $three => $three_rate,
//                $two => $two_rate,
//                $one => $one_rate,
//
//            );
            $day = [intval(substr($six,-2)),intval(substr($five,-2)),intval(substr($four,-2)),intval(substr($three,-2)),intval(substr($two,-2)),intval(substr($one,-2))];

            $rate = array(
                $six_rate,
                $five_rate,
                $four_rate,
                $three_rate,
                $two_rate,
                $one_rate,
            );



            $rate = json_encode($rate,true);
            $result['rate'] = $rate;
            $result['day'] = $day;

            return json($result);

        }else{
            $result = array(
                'errid' => 10002,
                'errmsg' => "client_id error",
            );
            return json($result);
        }
    }

    public function machine_history()
    {
        if(IS_POST){
            $time = date('Y-m-d - H',time());

            $stat_period = $this->findNum($time);

            $params = file_get_contents('php://input');

            $params = json_decode($params,true);
//
            $machine_id = $params['machine_id'];
            if(!$machine_id){
                $result = array(
                    'errid' => 10001,
                    'errmsg' => "machine_id error",
                );
                return json($result);
            }

            $stat_period = substr($stat_period,0,-2);
            DB::name("machine_day_stat_2019_1")
                ->field("total_income,stat_period")
                ->where(['machine_id'=>$machine_id])
                ->order('stat_period desc')
                ->select();

        }else{
            $result = array(
                'errid' => 10001,
                'errmsg' => 'machine_id error',
            );
            return json($result);
        }
    }

    public function client_history()
    {
        if(IS_POST){

        }else{
            $result = array(
                'errid' => 10002,
                'errmsg' => 'client_id error',
            );
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

    public function a()
    {
        $client = $_SERVER['REMOTE_ADDR'];
        $server = $_SERVER['SERVER_ADDR'];
//        $a = [1,2,3,4,5,6,1];
//        $a = array_flip($a);
//        halt($a);
//        $a = array_flip($a);
        $a = 'PHP';
        $b = 'MYSQL';
        list($b,$a)=array($a,$b);
        halt($a.$b);

//        $c = array_merge($a,$b);

//
    }

}