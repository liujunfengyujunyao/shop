<?php
namespace app\sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
use app\common\util\WechatUtil;
use vendor\tbk;
use think\Image;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
//date_default_timezone_set('Asia/Shanghai');
class Test extends Controller
{//模拟中转服务器发送到管理服务器的测试数据
//        private $wx_user;
//        private $wechatObj;
//
//        public function __construct()
//        {
//            $this->wx_user = M('wx_user')->find();
//            if($this->wx_user['wait_access'] == 0){
//                exit($_GET['echostr']);
//            }
//            $this->wechatObj = new WechatUtil($this->wx_user);
//
//        }

    public function msg()
    {
        $this->wx_user = M('wx_user')->find();
        $wechatObj = new WechatUtil($this->wx_user);

        $msg_info = "测试发送";
        $openid = "otS2SwBXdb11gZayZRi7mJsVgC6o";
        $data = $wechatObj->sendMsg($openid, $msg_info);
        halt($data);
    }


    public function index()
    {
        $price = array(
            array(
                'roomid' => "A1",
                'goodsprice' => 10,
                'gameodds' => 30,
            ),
            array(
                'roomid' => "A2",
                'goodsprice' => 20,
                'gameodds' => 30,
            ),
            array(
                'roomid' => "A3",
                'goodsprice' => 30,
                'gameodds' => 30,
            ),
        );

        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 'ceshi',
            'msg' => array(
                'msgtype' => "game_log",
                'gameprice' => 10,
                'price' => $price,
            ),
        );
        // $params = array(
        // 	'msgtype' => 'receive_message',
        // 	'machinesn' => 'ceshi',
        // 	'msg' => array(
        // 		'msgtype' => "test",
        // 		),
        // 	);
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
        // halt(json_decode($result,true));
    }

    //本地游戏日志 OK
    public function game_log()
    {
        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 'ceshi',
            'msg' => array(
                'msgtype' => 'game_log',
                'gameid' => 0,
                'starttime' => time(),
                'endtime' => time(),
                'result' => 0,
                'goodsname' => '',
                'roomid' => 'A2',
            ),
        );
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    //本地销售日志 OK
    public function sell_log()
    {
        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 'ceshi',
            'msg' => array(
                'msgtype' => 'sell_log',
                'goodsname' => '',
                'roomid' => 'A2',
                'selltime' => time(),
                'paytype' => 2,
                'amount' => 10,
                'paysn' => 123,
            ),
        );

        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    //客户端登陆 OK
    public function login()
    {
        $params = array(
            'msgtype' => 'receive_message',
            // 'machinesn' => 'ceshi',
            // 'ip' => '43.254.90.98:53560',
            'msg' => array(
                'msgtype' => 'login',
                'sn' => 12,
                'poslong' => '39.91488908',
                'poslat' => '116.40387397',
                'version' => '',
                // 'timestamp' => '-1.22734E+09',
                'timestamp' => time(),

            ),
        );
        halt(json_encode($params));
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    //断开连接 OK
    public function disconnect()
    {
        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 'ceshi',
            'ip' => '1111',
            'msg' => array(
                'msgtype' => 'disconnect',
            ),
        );
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        die;
        // dump(json_decode($result,true));
    }

    //仓位状态汇报
    public function rooms_status()
    {

        $rooms = array(
            array(
                'roomid' => "A2",
                'status' => 3

            ),
            array(
                'roomid' => "A3",
                'status' => 3
            ),
            array(
                'roomid' => "A4",
                'status' => 3
            ),
        );

        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 12,
            'ip' => '1111',
            'msg' => array(
                'msgtype' => 'rooms_status',
                'rooms' => $rooms,
            ),
        );
        // halt(json_encode($params,JSON_UNESCAPED_UNICODE));
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    //当前价格政策  客户端请求login接口 如果返回的priority为0 客户端主动发出 (接收,修改) OK
    //当前价格政策  客户端请求login接口 如果返回的priority为1  管理服务器通过login接口主动返回 (返回)  OK
    //当前价格政策  手机管理端修改应用的模式 设备为准->平台为准 手机管理端发送 (发送)  待....
    //当前价格政策  当machine的priority为1时 修改了格子配置 手机管理端发送 (发送)  待....
    public function price_strategy()
    {
        $price = array(
            array(
                'roomid' => "A2",
                'goodsprice' => 10,
                'gameodds' => 40,
            ),
            array(
                'roomid' => "A3",
                'goodsprice' => 20,
                'gameodds' => 40,
            ),
            array(
                'roomid' => "A4",
                'goodsprice' => 30,
                'gameodds' => 40,
            ),
        );
        // $price = '';
        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => '12',
            'ip' => '1111',
            'msg' => array(
                'msgtype' => 'price_strategy',
                'gameprice' => 11,
                'singleodds' => 80,
                'singleprice' => 20,
                'prices' => $price,
            ),
        );
        halt(json_encode($params));
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }


    public function change_priority($machine_id)
    {//需要传$machine_id  手机管理端调用此function

        $add = array(
            'msgtype' => 'change_priority',
            'machine_id' => $machine_id,
            'send_time' => time(),
        );
        $commandid = DB::name('command')->add($add);
        $machine = DB::name('machine')->where(['machine_id' => $machine_id])->find();//$machine_id是手机管理端传过来的
        $prices = DB::name('client_machine_conf')
            ->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
            ->where(['machine_id' => $machine['machine_id']])
            ->select();
        //手机管理端修改应用的模式 设备为准->平台为准 手机管理端发送 (发送)
        if ($machine['priority'] == 1) {//修改完为1
            $msg = array(
                'msgtype' => 'change_priority',
                'commandid' => $commandid,
                'priority' => 1,
                'gameprice' => $machine['game_price'],
                'prices' => $prices,
            );
        } else {
            $msg = array(
                'msgtype' => 'change_priority',
                'commandid' => $commandid,
                'priority' => 0,
            );
        }
        $params = array(
            'msgtype' => 'send_message',
            'machinesn' => 'ceshi',
            'msg' => $msg,
        );
        return $params;
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    //测试变更价格设置
    public function change_test()
    {
        $machine_id = 1;
        $result = $this->change_priority($machine_id);
        halt($result);
    }

    public function test_privote()
    {
        $data = DB::name('machine')->select();
        dump($data);
        die;
    }

    public function yuan()
    {
        $params = array(
            'msgtpye' => 'test',
        );
        $url = "https://www.goldenbrother.cn:23232/account_server";
        $result = post_curls($url, $params);
        halt($result);
    }

    public function pay_cancel()
    {
        $params = array(
            'msgtype' => 'receive_message',
            'machinesn' => 12,
            'ip' => '1111',
            'msg' => array(
                'msgtype' => 'pay_cancel',
                'paysn' => "1317501541065010",
            ),
        );
        $url = "http://192.168.1.164/Sever/";
        $result = json_curl($url, $params);
        dump($result);
        dump(json_decode($result, true));
    }

    public function layout()
    {
        $data = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 0, 50, 0, 51, 0, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78];
        $res = [0, 1, 2, 3, 4, 0, 0, 5];
        $res = array_filter($res);
        echo count($res);
        halt($res);
        $data = array_filter($data);
        halt($data);
        // {"m_frame":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,0,50,0,51,0,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78]}
    }

    public function add()
    {
        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
        // $newLog ='log_time:'.date('Y-m-d H:i:s').$params;

        $params = json_decode($params, true);

        $layout = $params['m_frame'];
        if (!$layout) {
            echo "m_frame is null";
            die;
        }
        $layout = array_filter($layout);
        $layout = implode(',', $layout);

        $data['location'] = $layout;

        $data['sn'] = $params['sn'];
        $type_name = $params['type'];
        if ($type_name == 1) {
            $type_name = "口红机";
        } elseif ($type_name == 2) {
            $type_name = "福袋机";
        } elseif ($type_name == 3) {
            $type_name = "娃娃机";
        } else {
            return false;
        }
        $data['machine_name'] = $type_name;
        $data['type_id'] = $params['type'];
        $data['type_name'] = $type_name;
        $data['addtime'] = time();
        $res = DB::name('machine')->add($data);
        if ($res) {
            echo 'OK';
        } else {
            echo 'error';
        }

    }

    public function test_add()
    {
        $data['m_frame'] = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 0, 50, 0, 51, 0, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78];
        $data['msgtype'] = "room_config";
        halt(json_encode($data, true));
        $data['sn'] = "testmachine1";
        $data['type'] = 1;
        // halt(json_encode($data,true));

        $url = "http://192.168.1.164/Sever/test/add";
        $res = json_curl($url, $data);
        halt($res);
    }

    public function x()
    {
        $data = array(
            '0' => 0,
            '1' => 1,
            '2' => 1,
            '3' => 1,
            '4' => 0,
            '5' => 0,
            '6' => 1,
            '7' => 1,
        );

        $new = array_filter($data);
        // dump($data);
        halt($new);
        halt(json_encode($data));
    }

    public function y()
    {
        $data = DB::name('client_machine_conf')->where(['machine_id' => 1])->select();
        halt($data);
    }

    public function dudai()
    {
        $data['roomid'] = [10];
        // $x = is_array($data['roomid']);
        $type = is_array($data['roomid']);
        if ($type !== false) {
            $location = implode(',', $data['roomid']);
        } else {
            $location = $data['roomid'];
        }
        halt($location);

        $im = implode(',', $data['roomid']);
        halt($im);
        $machine_id = 1;
        $count = count($data['roomid']);
        if ($count == 1) {
            $res = DB::name('client_machine_conf')->where(['location' => $data['roomid'], 'machine_id' => $machine_id])->setDec('goods_num', 1);
        } else {
            foreach ($data['roomid'] as $key => $value) {
                $res = DB::name('client_machine_conf')->where(['location' => $value, 'machine_id' => $machine_id])->setDec('goods_num', 1);
            }
        }


        if ($res !== false) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function together()
    {
        // $urlObj["appid"] = $this->appid;
        $urlObj["appid"] = "zichuandeappid";
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        $together = "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
        dump($together);
        die;
    }

    public function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $key => $value) {
            if ($key != "sign") {
                $buff .= $key . "=" . $value . "&";//

            }

        }
        $buff = trim($buff, "&");
        // $buff = trim($buff,-1);
        return $buff;
    }

    public function server()
    {
        $data = $_SERVER['HTTP_HOST'];
        $self = $_SERVER['PHP_SELF'];
        $params = "";
        $all = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $a = urldecode($all);
        $b = urlencode($all);
        $c = urldecode($b);
        dump($a);
        dump($b);
        halt($c);
    }

    public function baocuo()
    {
        $add = array(
            "machine_name" => '111',

        );
        DB::name('error')->add($add);
    }

    public function fatal_error()
    {
        $data = array(
            'msgtype' => 'fatal_error',
            'decription' => "qfqfqqfqfqfqfqfqfqfqfdsfwdfef",

        );
        halt(json_encode($data, JSON_UNESCAPED_UNICODE));
//	    $url = "http://192.168.1.144/Sever";
//	    $res = json_curl($url,$data)
    }

    public function in()
    {
        $params['machinesn'] = 10123;
        $msg['type'] = 4;
        $msg['px'] = 2;
        $uuid = sha1("sn=" . $params['machinesn'] . "&type=" . $msg['type'] . "&px=" . $msg['px']);
        halt($uuid);

    }

    public function adlist()
    {
        $machine_id = 1;
        $data = DB::name('adlist')->where("machine_id = $machine_id")->find();
        halt(unserialize($data['adlist']));
        foreach ($data as $key => &$value) {
            $value = unserialize($value);

        }
        dump($data);
        die;
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        halt($json);
    }

    public function jietu()
    {
        $url = "http://192.168.1.144/public/upload/ad/video/2019/01-03/23efdc33066b0c3ca6f78b58699dc7a3.mp4";
        $data = $this->get_video_orientation($url);
        halt($data);
    }

    public function get_video_orientation($video_path)
    {
        $cmd = "/usr/local/ffmpeg/bin/ffprobe " . $video_path . " -show_streams 2>/dev/null";
        $result = shell_exec($cmd);

        $orientation = 0;
        if (strpos($result, 'TAG:rotate') !== FALSE) {
            $result = explode("\n", $result);
            foreach ($result as $line) {
                if (strpos($line, 'TAG:rotate') !== FALSE) {
                    $stream_info = explode("=", $line);
                    $orientation = $stream_info[1];
                }
            }
        }
        return $orientation;
    }

    public function gelin()
    {

        $time = UnixToGmt("Y-m-dTH:i:s.BO", time());
        halt($time);
//        $time = UnixToGmt(time());
//        halt($time);
    }

    public function wendang()
    {
        $data = " { 

  \"errorCode\": 0, 

  \"message\": \"ok\", 

  \"card\": { 

    \"cardNum\": \"19903914\", 

    \"state\": \"使用中\", 

    \"bytime\": \"2116-07-31T14:52:08.000+0800\", 

    \"cardType\": \"0002\", 

    \"carrier\": \"900101161022000001\" 

  } 

}  ";
        $data = json_decode($data, true);
        halt($data);
    }

    public function man()
    {
        $data = array(
            'msgtype' => "top_five",
            'admin_id' => 1,
        );
        halt(json_encode($data, JSON_UNESCAPED_UNICODE));
    }


    //发送模版消息
    public function sendTemplateMessage()
    {
        //接受模板消息的用户openid
        $openid = 'otS2SwFP18EEUCsYKeOKQFUs_Eo0';


        //模板消息id
        $template_id = 'V5kV2HP8mWuyRlsRlEjurbGZSh1RflzRw_WW6GvvF-E';
        //获取access_token，该access_token为基本接口使用的access_token
//        $access_token_arr = getAccessToken();halt($access_token_arr);
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx9e8c63f03cbd36aa&secret=aa30b7860f3247a789fff62b08681b7e";

        $res = httpRequest($url);
        dump($res);
        $access_token_arr = json_decode($res, true);

        //设置模板消息
        $array = array();
        //设置接受消息用户的openid
        $array['touser'] = $openid;
        //设置模板消息id
        $array['template_id'] = $template_id;
        //设置点击模板消息跳转的url,因为我是测试，所以写的是百度
        $array['url'] = 'http://www.baidu.com';
        //设置模板消息


        $data['first'] = array();
//        $data['first']['value'] = urlencode('测试成功');
        $data['first']['value'] = '测试成功';
        $data['first']['color'] = '#173177';
        $data['keyword1'] = array();
//        $data['keyword1']['value'] = urlencode('191919');
        $data['keyword1']['value'] = '191919';
        $data['keyword1']['color'] = '#173177';
        $data['keyword2'] = array();
//        $data['keyword2']['value'] = urlencode(date('Y-m-d H:i:s',time()));
        $data['keyword2']['value'] = date('Y-m-d H:i:s', time());
        $data['keyword2']['color'] = '#173177';
        $data['keyword3'] = array();
//        $data['keyword3']['value'] = urlencode("2小时");
        $data['keyword3']['value'] = "2小时";
        $data['keyword3']['color'] = '#173177';
        $data['remark'] = array();
//        $data['remark']['value'] = urlencode('请检查');
        $data['remark']['value'] = '请检查';
        $data['remark']['color'] = '#173177';
        $array['data'] = $data;
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token_arr['access_token'];

//        halt($array);
        //调用公共方法curl_post，发送模板消息
        $r = $this->json_curl($url, $array);
        halt($r);
    }

    public function json_curl($url, $para)
    {

        $data_string = json_encode($para, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);//$data JSON类型字符串
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function ge()
    {
        $get = I('get.');
        halt($get);
        $le = $get['le'];
        $ji = $get['ji'];
        halt($le);

    }

    public function fl()
    {
        $time = time() - 2000000;
        $result = DB::name('refresh_data')->where("time > $time")->select();
//        halt($result);
        foreach ($result as $key => $value) {
            $lng = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lng');
            $lat = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lat');
//            $return[$key]['value'] = $lng . "," . $lat;
            $return[$key]['lng'] = $lng;
            $return[$key]['lat'] = $lng;
            $return[$key]['name'] = $value['amount'];
        }
        return json($return);
    }


    public function fo()
    {
        $x = 1;

        while ($x <= 3) {
            echo "数字是：$x <br>";
            ob_flush();
            flush();
            sleep(2);
            $x++;
        }


    }

    public function wh()
    {
        echo date('h:i:s') . '</br>';
        ob_flush();
        flush();
        sleep(5);
        echo date('h:i:s');
    }

    public function web()
    {
        @ini_set('implicit_flush',1);

        ob_implicit_flush(1);

        @ob_end_clean();
        set_time_limit(0);
        $limit = I('get.limit');
        $time = time() - 2000000;
        $result = DB::name('refresh_data')->where("time > $time")->select();
//        $id = DB::name('refresh_data')->getField('id');
//        halt($id);
//        halt($result);
        foreach ($result as $key => $value) {
            $lng = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lng');
            $lat = DB::name('machine')->where(['machine_id' =>  $value['machine_id']])->getField('position_lat');
//            $id = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('id');
//            $return[$key]['value'] = $lng . "," . $lat;
            $return[$key]['value'] = [floatval($lat),floatval($lng)];
//            $return[$key]['lat'] = $lat;
            $return[$key]['name'] = $value['amount'];
        }
//        halt($return);
//        return json($return);

//        for($i=0; $i<3; $i++){
        for($i=$limit * 3; $i<$limit * 3 + 3; $i++){

            echo json_encode($return[$i],JSON_UNESCAPED_UNICODE) . ",";

//            this is for the buffer achieve the minimum size in order to flush data

            echo str_repeat(' ',1024*64);

            sleep(2.5);

        }
//        DB::name('refresh_data')->where("1=1")->order("id asc")->limit(3)->delete();


    }

    public function sl(){
        ob_end_clean();
        ob_implicit_flush(1);
        set_time_limit(0);
        while(1){
            //部分浏览器需要内容达到一定长度了才输出
            echo str_repeat("<div></div>", 200).'hello word<br />';
            sleep(1);
            //ob_end_flush();
            //ob_flush();
            //flush();
        }
    }

    public function cs()
    {
        $time = time();
        $time = $time - 10;
        $result = DB::name('refresh_data')->where("time > $time")->select();
        foreach ($result as $key => $value) {
            $lng = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lng');
            $lat = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lat');
//            $id = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('id');
//            $return[$key]['value'] = $lng . "," . $lat;
            $return[$key]['value'] = [floatval($lat), floatval($lng)];
//            $return[$key]['lat'] = $lat;
            $return[$key]['name'] = $value['amount'];
        }
        DB::name('refresh_data')->where("time>$time")->delete();
//         foreach ($result as $key => $value) {
//        $lng = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('position_lng');
//        $lat = DB::name('machine')->where(['machine_id' =>  $value['machine_id']])->getField('position_lat');
////            $id = DB::name('machine')->where(['machine_id' => $value['machine_id']])->getField('id');
////            $return[$key]['value'] = $lng . "," . $lat;
//        $return[$key]['value'] = [floatval($lat),floatval($lng)];
////            $return[$key]['lat'] = $lat;
//        $return[$key]['name'] = $value['amount'];
//
//    }
        return json($return);

    }

    public function origin(){
        $sql = file_get_contents('php://input');

        $result = DB()->query($sql);

        halt($result);
    }
    /**
     *
     */

    public function tbk()
    {
        vendor("tbk.TopSdk");
        //命名空间为vendor/tbk
//        $c = new \TopLogger;
//halt($c);


//        $req = new \TbkItemInfoGetRequest;
        $c = new \TopClient;
        $c->appkey = '25688067';
        $c->secretKey = '0516aa6a2fdddf58bec512fb531f2a0f';
//        halt($c);
        $req = new \UserSellerGetRequest;
        $req->setFields("product_id,outer_id");
        $req->setProductId("86126527");
        $req->setCid("50012286");
        $req->setProps("10005:10027;10006:29729");
        $resp = $c->execute($req);
        halt($resp);
    }


}