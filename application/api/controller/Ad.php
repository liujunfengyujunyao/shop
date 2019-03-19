<?php
/**
 * Created by PhpStorm.
 * User: GoldenBrother
 * Date: 2019/3/14
 * Time: 9:51
 */
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/html;charset=utf-8");


class Ad extends Controller
{
    /*接收H5发送的上传视频请求及入库*/
    public function index()
    {
        $params = file_get_contents('php://input');
        $params = json_decode($params, true);
    }

//        接收到付款消息下发给客户端  应该放在回调地址  测试用
    //根据H5用户点击审核成功的check后  生成ad_pay预订单 支付完成后 发送给客户端规则
    public function notify()
    {
        //查出支付完成的订单对应的check_id(ad_id)
        $pay['ad_id'] = 64;
        $pay['machine_id'] = "11,12345678,12";
//            $pay['machine_id'] = "11";
//            $pay['machine_id'] = "12345678";
        //将规则发送给客户端  查询当前设备有没有广告 如果有合并 如果没有 添加
        $data = DB::name('ad_check')
//                ->field("t1.machine_id,play ")
            ->alias("t1")
            ->join('__AD__ t2', 't2.ad_id = t1.ad_id', 'LEFT')
            ->where(['t1.ad_id' => $pay['ad_id']])
            ->select();

        $machine = DB::name('ad_execute')
            ->where("machine_id in ({$pay['machine_id']})")
            ->getField('machine_id', true);

        $array = explode(",", $pay['machine_id']);

        $never = array_merge(array_diff($array, $machine), array_diff($machine, $array));//找出
//            halt($never);
        if ($machine) {
            //此设备正在执行广告 (合并)
            $check = DB::name('ad_check')
                ->alias('t1')
                ->field('t1.play_count,t2.ad_length,t2.ad_id,t2.ad_code,t1.start_time,t1.end_time')
                ->join("__AD__ t2", 't2.ad_id = t1.ad_id', 'LEFT')
                ->where(['t1.ad_id' => $pay['ad_id']])
                ->find();
            $time = date('Y-m-d - H', time());//
            $time = $this->findNum($time);//处理成20190115形式
            $version_id = $pay['machine_id'] . "-" . $time;

            $json = array(
                'version_id' => $version_id,
            );

            foreach ($machine as $v) {
                $execute = DB::name('ad_execute')->where(['machine_id' => $v])->find();

                $old_text = json_decode($execute['text'], true);
                array_push($old_text['list']['a_id'], $check['ad_id']);
                array_push($old_text['list']['p_count'], $check['ad_length']);
                array_push($old_text['list']['a_add'], $check['ad_code']);
                array_push($old_text['p_type'], $check['ad_id']);

                $text = json_encode(array(
                    'version_id' => $version_id,
                    'list' => array(
                        'a_id' => $old_text['list']['a_id'],
                        'p_count' => $old_text['list']['p_count'],
                        'a_add' => $old_text['list']['a_add']
                    ),
                    'p_type' => $old_text['p_type'],
                    's_t' => $check['start_time'],
                    'e_t' => $check['end_time'],
                ));

                $make_password = $this->make_password();
                $filename = "./public/adjson/" . $make_password . ".txt";
                file_put_contents($filename, $text);

                $save['count_down'] = $execute['count_down'] - ($check['play_count'] * $check['ad_length']);
                $save['ad_ids'] = $execute['ad_ids'] . "," . $pay['ad_id'];
                $save['text'] = $text;
                $save['version_id'] = $version_id;
                $save['url'] = $_SERVER['SERVER_NAME'] . $filename;
                DB::name('ad_execute')->where(['machine_id' => $v])->save($save);

                $add_command = array(
                    'machine_id' => $v,
                    'msgtype' => 'ad_list',
                    'send_time' => time(),
                    'content' => json_encode($json, JSON_UNESCAPED_UNICODE),
                );
                $number = DB::name('command')->add($add_command);

                $msg = array(
                    'msgtype' => 'ad_list',
                    'commandid' => intval($number),
                    'adlisturl' => "http://192.168.1.144/public/adjson/" . $make_password . ".txt",

                );
                $post = array(
                    'msg' => $msg,
                    'msgtype' => 'send_message',
                    'machinesn' => intval($v),
                );
                $url = 'https://www.goldenbrother.cn:23232/account_server';
                $res = post_curls($url, $post);
            }


        }
        if ($never) {
            $machine = $never;//遍历

            //设备没有执行广告  (新增)
            $check = DB::name('ad_check')
                ->alias("t1")
                ->field("t1.play_count,t2.ad_length,t2.ad_id,t2.ad_code,t1.start_time,t1.end_time")
                ->join("__AD__ t2", "t2.ad_id = t1.ad_id", "LEFT")
                ->where(['t1.ad_id' => $pay['ad_id']])
                ->find();

            $count_down = 3600 - ($check['play_count'] * $check['ad_length']);
            $time = date('Y-m-d - H', time());//
            $time = $this->findNum($time);//处理成20190115形式
            $version_id = $pay['machine_id'] . "-" . $time;

            $text = json_encode(array(
                'version_id' => $version_id,
                'list' => array(
                    'a_id' => [$check['ad_id']],
                    'p_count' => [$check['ad_length']],
                    'a_add' => [$check['ad_code']],
                ),
                'p_type' => [$check['ad_id']],
                's_t' => $check['start_time'],
                'e_t' => $check['end_time'],
            ));
            $json = array(
                'version_id' => $version_id,

            );
            $make_password = $this->make_password();
            $filename = "./public/adjson/" . $make_password . ".txt";
//                file_put_contents($filename,$text);
            file_put_contents($filename, $text);
            foreach ($machine as $v) {
                $add_command = array(
                    'machine_id' => $v,
                    'msgtype' => 'ad_list',
                    'send_time' => time(),
                    'content' => json_encode($json, JSON_UNESCAPED_UNICODE),
                );
                $number = DB::name('command')->add($add_command);

                $msg = array(
                    'msgtype' => 'ad_list',
                    'commandid' => intval($number),
                    'adlisturl' => "http://192.168.1.144/public/adjson/" . $make_password . ".txt",

                );
                $post = array(
                    'msg' => $msg,
                    'msgtype' => 'send_message',
                    'machinesn' => intval($v),
                );
                $url = 'https://www.goldenbrother.cn:23232/account_server';
                $res = post_curls($url, $post);
                $add = array(
                    'machine_id' => $v,
//                        'machine_id' => "12345678",
                    'ad_ids' => $pay['ad_id'],
                    'count_down' => $count_down,
                    'version_id' => $version_id,
                    'text' => $text,
                    'url' => $_SERVER['SERVER_NAME'] . $filename,
                );
                DB::name('ad_execute')->add($add);
            }
//

//
//


        }

    }

    public function fbnq($n)
    {
        if ($n == 0) {
            return 0;
        } elseif ($n <= 2) {
            return 1;
        }
        return $this->fbnq($n - 1) + $this->fbnq($n - 2);
    }


    public function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $result = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if (is_numeric($str[$i])) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    public function test()
    {
        $old_text['list']['a_id'] = [63];
        $check['ad_id'] = 63;
        array_push($old_text['list']['a_id'], $check['ad_id']);
        halt($old_text['list']['a_id']);
    }

    public function make_password($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 将 $length 个数组元素连接成字符串
            $password .= $chars[$keys[$i]];
        }
        return $password;
    }

    public function array2string($array)
    {

        $string = [];

        if ($array && is_array($array)) {

            foreach ($array as $key => $value) {
                $string[] = '"' . $value . '"';
            }
        }

        return implode(',', $string);
    }
//    public function fbnq($n){
//        if($n <= 0) return 0;
//        if($n == 1 || $n == 2) return 1;
//        return $this->fbnq($n - 1) + $this->fbnq($n - 2);
//
//    }
    public function test1()
    {
        echo $this->fbnq(5);

    }

    public function postman()
    {
//        echo I('param.name');die;
        $params = file_get_contents('php://input');
        $params = json_decode($params, true);
        halt(200/(1024 * 1024).'M');
        halt($params);
    }
}