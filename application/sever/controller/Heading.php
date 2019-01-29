<?php
namespace app\sever\controller;
use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/html;charset=utf-8");
//header('Content-Type: application/json');
//header('Authorization : Basic YWRtaW46d3d3LmhkMTIzLmNvbQ==');
class Heading extends Controller{
    public function adjustScore(){
        $data = "aaa";
        $url = "http://192.168.1.144";
        $res = json_curl($url,$data);halt($res);
    }
    //发送模版消息
    public function sendTemplateMessage()
    {
        //接受模板消息的用户openid
        $openid = 'otS2SwFP18EEUCsYKeOKQFUs_Eo0';//遍历发送给多人


        //模板消息id
        $template_id = 'V5kV2HP8mWuyRlsRlEjurbGZSh1RflzRw_WW6GvvF-E';
        //获取access_token，该access_token为基本接口使用的access_token
//        $access_token_arr = getAccessToken();halt($access_token_arr);
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx9e8c63f03cbd36aa&secret=aa30b7860f3247a789fff62b08681b7e";

        $res = httpRequest($url);
        $access_token_arr = json_decode($res,true);

        //设置模板消息
        $array = array();
        //设置接受消息用户的openid
        $array['touser'] = $openid;
        //设置模板消息id
        $array['template_id'] = $template_id;
        //设置点击模板消息跳转的url
        $array['url'] = 'http://www.goldenbrother.cn/index.php/Phone';
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
        $data['keyword2']['value'] = date('Y-m-d H:i:s',time());
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
//        dump($url);
//        halt(json_encode($array,JSON_UNESCAPED_UNICODE));
//        halt($array);
        //调用公共方法curl_post，发送模板消息
        $r = post_curls($url,$array);
        halt($r);
    }








    public function test(){
//        $json='{
//        "operCtx": {
//        "time": "2016-6-16T14:00:08.000+0800",
//        "operator": {
//            "namespace": "ceshi",
//            "id": "ceshi",
//            "fullName": "ceshi"
//        },
//        "terminalId": "ceshi",
//        "store": "900101"
//    },
//    "request": {
//        "tranId": "demo0004",
//        "xid": "demo0005",
//        "tranTime": "2018-03-29T14:00:08.000+0800",
//        "account": {
//            "type": "mobile",
//            "id": "13683141819"
//        },
//        "scoreRec": {
//            "scoreType": "-",
//            "scoreSubject": "调整",
//            "score": "2000"
//        },
//        "scoreSource": "调整",
//        "remark": "",
//        "action": "调整"
//    }
//}';
        $params = file_get_contents('php://input');

        $params = json_decode($params, true);
        $is_there = DB::name('cooperation_restrict')->where(['id'=>1])->find();
        //使用次数 == 使用上限
        if($is_there['use_number'] == $is_there['upper_number']){
            $result = array(
                'status' => 0,
                'msg' => "对不起,本月积分兑换次数已满",
            );
            return json($result);
        }

        $date = UnixToGmt("Y-m-d" ,time()).'T'.UnixToGmt("H:i:s.000 +0800" ,time()+60*60*8);
//        halt($date);
//        echo $data;die;
        $add = array(
            'partner_id' => 1,
            'account' => $params['account'],
            'amount' => $params['amount'],
            'machinesn' => $params['machinesn'],
            'time' => time(),
        );
        $machine_id = DB::name('machine')->where(['sn'=>$add['machinesn']])->getField("machine_id");
        if(!$machine_id){
            $result = array(
                'status' => 0,
                'msg' => "machinesn_error",
            );
            return json($result);
        }
        $tranId = DB::name('partner_pay_log')->add($add);
        $array = array(
            'operCtx' => array(
                'time' => "",
                'operator' => array(
                    'namespace' => "",
                    'id' => "ceshi",
                    'fullName' => "ceshi",//machinesn
                ),
//                'terminalId' => "0004",
                'terminalId' => $machine_id,
                'store' => "900102",
            ),
            'request' => array(
//                'tranId' => "0015",//万科会返回回来
                'tranId' => $tranId,//万科会返回回来
                'xid' => "0001",
//                'tranTime' => "2018-03-29T14:00:08.000+0800",
                'tranTime' => $date,
                'account' => array(
                  'type' => "mobile",
//                    'id' => "13683141819",//获取到的数据
                    'id' => $add['account'],//获取到的数据
                ),
                'scoreRec' => array(
                    'scoreType' => "-",
                    'scoreSubject' => "消费",
//
//                    'score' => "-11920",
//                    'score' => $add['amount'],
                    'score' => "-".$add['amount'],
                ),
                'scoreSource' => "消费",
                'remark' => "",
                'action' => "消费",
            ),
        );
        $array = json_encode($array,JSON_UNESCAPED_UNICODE);



        $header = array(
            'content-type:application/json',
            'Authorization: Basic '.base64_encode("admin:www.hd123.com"),
        );
        $url = "http://58.246.29.147:9002/jcrm-server-card/rest/score/adjustScore";//海鼎万科的地址
        $return = $this->curl_request($url,$array,$header);
        $return = json_decode($return,true);
        if($return['message'] == "ok"){//成功

            //一个月限制80个
            DB::name('cooperation_restrict')->where(['id'=>1])->setInc('use_number',1);

            $log_id = "";//发送给设备成功信息
            $result = array(
                'status' => 1,
                'msg' => "ok",
            );
            $save = array(
                'status' => 1,
            );
            $time = time();
            $command = array(
                'machine_id' => $machine_id,
                'msgtype' => "partner_pay_log",
                'send_time' => $time,
            );
            $commandid = DB::name('command')->add($command);


            $msg = array(
                'msgtype' => 'netpay_ack',
                'commandid' => intval($commandid),
                'amcount' => intval($add['amount'])/10,
                'paytype' => 10,
                'paysn' => GetRandStr(),
            );
            $post = array(
                'msg'=>$msg,
                'msgtype'=>'send_message',
                'machinesn'=>intval($add['machinesn']),
            );
            $url = 'https://www.goldenbrother.cn:23232/account_server';
            $res = post_curls($url,$post);






            for ($i=0; $i<3; $i++)
            {
                $receive = DB::name('command')->where(['commandid'=>$commandid])->find();
                if(!is_null($receive['receive_time'])){
                    DB::name('command')->where(['commandid'=>$commandid])->save(['status'=>1,'receive_time'=>time()]);
                    break;
                }elseif($i<2){
                    sleep(2);
                }else{
                    $result = array(
                        'status' => 0,
                        'msg' => "设备通讯失败",
                    );
                    $save = array(
                        'status' => 10,
                        'error_reason' => "通讯设备失败",
                    );
                    break;
//                    DB::name('partner_pay_log')->save($save);
//                    return json($result);
                }
            }
        }elseif($return['message'] == "扣减后账户总积分不能为负"){//失败
            $result = array(
                'status' => 0,
                'msg' => "余额不足",
            );
            $save = array(
                'status' => 2,
                'error_reason' => "余额不足",
            );
        }elseif($return['errorCode'] == 11002){
            $result = array(
                'status' => 0,
                'msg' => "订单号重复",
            );
            $save = array(
                'status' => 2,
                'error_reason' => "订单号重复",
            );
        }elseif($return['message'] == "根据帐户识别码未找到会员"){
            $result = array(
                'status' => 0,
                'msg' => "根据帐户识别码未找到会员",
            );
            $save = array(
                'status' => 2,
                'error_reason' => "根据帐户识别码未找到会员",
            );
        }else{
            $result = array(
                'status' => 0,
                'msg' => "Network communication failure",
            );
            $save = array(
                'status' => 2,
                'error_reason' => "失败",
            );
        }
        DB::name('partner_pay_log')->where(['id'=>$tranId])->save($save);
        return json($result);

    }
    /**
     * 发送curl请求的函数
     * @param string $url 请求地址
     * @param bool $post 是否post请求
     * @param array $data 请求参数
     * @param bool $https 是否https协议
     * @return array
     */
    function curl_request($url,  $data, $headers=array(),$post = true,$https = false){
        //初始化curl请求, 设置请求地址
        $ch = curl_init($url);

        //设置请求参数 针对post请求
        if($post){
            //发送post请求
            curl_setopt($ch, CURLOPT_POST, true);//设置请求方式为post
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置请求参数
        }
        //绕过https协议的证书校验
        if($https){
            //当前发送的是https协议的请求
            //禁用证书校验
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //发送请求
        //直接返回结果
        // curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);//成功 就是返回数据 失败 false
        // echo curl_getinfo($ch, CURLINFO_HEADER_OUT);//查看header头
        //关闭请求 释放请求资源
        curl_close($ch);
        //返回数据
        return $result;
    }


    public function refresh_use_number(){
        $data = DB::name('cooperation_restrict')->where("1=1")->save(['use_number'=>0]);
//        DB::name('cooperation_restrict')->where(['id'=>1])->setInc('use_number',1);
    }

    public function json(){
        $add = array(
            'partner_id' => 1,
            'account' => 13683141819,
            'amount' => 100,
            'machinesn' => 11,
        );
        $add = json_encode($add,JSON_UNESCAPED_UNICODE);
        halt($add);
    }


}