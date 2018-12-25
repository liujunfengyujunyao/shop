<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Pc extends Controller
{
    public function index()
    {//被动请求的接口
        // $params = $GLOBALS['HTTP_RAW_POST_DATA'];
        $params = file_get_contents('php://input');
        $params = $this->trimall($params);
        //写入日志
        $newLog = 'log_time:' . date('Y-m-d H:i:s') . $params;
        file_put_contents('./liujiang_log.txt', $newLog . PHP_EOL, FILE_APPEND);

        $params = json_decode($params, true);


        if (is_null($params)) {
            $msg = array(
                'errid' => 10000,
                'errmsg' => 'Data is empty',
            );
            $data = array(
                'msg' => $msg,
                'machinesn' => intval($params['machinesn']),
            );

            return json($data);
        }

        $type = $params['msgtype'] ? $params['msgtype'] : "";

            switch ($type) {
                case 'top_five'://近30天top5设备
                    echo $this->top_five();
                    break;
                case 'deal_sum'://总交易额
                    echo $this->deal_sum();
                    break;
                case 'today_sum'://今日交易额
                    echo $this->today_sum();
                    break;
                case 'charts'://设备日均消费额
                    echo $this->charts();
                    break;
                case '设备数量'://
                    echo $this->top_five();
                    break;

            }

    }


    //近30天top5设备
    private function top_five()
    {
        $time = time();
        $start_time = $time-60*60*24*30;//money_count+game_count
        $data = DB::name('machine_day_statistics')
                ->alias('t1')
                ->field("sum(money_count) as count,t1.machine_id,t2.machine_name")
                ->where("t1.statistics_date > $start_time")
                ->join('__MACHINE__ t2',"t2.machine_id = t1.machine_id",'LEFT')
                ->order("count desc")
                ->group('t1.machine_id')
                ->limit(5)
                ->select();
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }

    //设备量
    public function machine_number()
    {

    }

    //总交易额
    private function deal_sum()
    {
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);//当天00:00点的时间戳
        $before = DB::name('machine_day_statistics')
                ->sum('money_count');

        $now = DB::name('sell_log')->where("sell_time > $morningTime")->sum('amount');
        $deal_count = DB::name('sell_log')->count();
        $data['deal_count'] = $deal_count;
        $data['deal_sum'] = $before + $now;
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }

    //当日交易额
    private function today_sum()
    {
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);//当天00:00点的时间戳
        $data['today_sum']= DB::name('sell_log')->where("sell_time > $morningTime")->sum('amount');
        $data['today_count'] = DB::name('sell_log')->where("sell_time > $morningTime")->count();
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }


    //设备日均消费额
    private function charts()
    {
        $manager_info['admin_id'] = 12;
        $checkdate = array(
            date("m-d",strtotime("-6 day")),
            date("m-d",strtotime("-5 day")),
            date("m-d",strtotime("-4 day")),
            date("m-d",strtotime("-3 day")),
            date("m-d",strtotime("-2 day")),
            date("m-d",strtotime("-1 day")),
        );
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);//当天00:00点的时间戳
        $one_date = $morningTime - 60*60*24*1;
        $two_date = $morningTime - 60*60*24*2;
        $three_date = $morningTime - 60*60*24*3;
        $four_date = $morningTime - 60*60*24*4;
        $five_date = $morningTime - 60*60*24*5;
        $six_date = $morningTime - 60*60*24*6;
        $one = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$one_date])->getField('money_count');
        $two = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$two_date])->getField('money_count');
        $three = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$three_date])->getField('money_count');
        $four = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$four_date])->getField('money_count');
        $five = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$five_date])->getField('money_count');
        $six = DB::name('client_day_statistics')->where(['client_id'=>$manager_info['admin_id'],'statistics_date'=>$six_date])->getField('money_count');
        if(is_null($one)){
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
            $money_count = array(
                $six,$five,$four,$three,$two,$one
            );

        $data = array(
            'checkdate' => $checkdate,
            'money_count' => $money_count,
        );


        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }


    public function trimall($str)//删除空格
    {
        $oldchar=array(" ","　","\t","\n","\r");
        $newchar=array("","","","","");
        return str_replace($oldchar,$newchar,$str);
    }


}