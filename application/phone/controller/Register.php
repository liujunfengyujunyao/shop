<?php

namespace app\phone\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
//[b]记得引入命名空间,如果这里不引入命名空间，那么方法中的实例化SendSms的格式应该为：$sms = new \alisms\SendSms()[/b]
use alisms\SendSms;
class Register extends Controller{
    //注册页
    //获取验证码
    // private static $smsTemplate = array("0","SMS_112865157","SMS_112865156","SMS_112865155","SMS_113455188");
    public function index()
    {

        if(IS_POST){
            $number = input('phone');//input助手函数	获取输入数据 支持默认值和过滤
            $phone = M('admin')->where(['phone'=>$number])->find();
            // halt(2113132);
            if(count($phone)>0){
                return json(['info' => '已注册', 'error_code' => '1']);//返回已注册手机
            }
            //获取对象，如果上面没有引入命名空间，可以这样实例化：$sms = new \alisms\SendSms()
            $sms = new SendSms();
            //设置关键的四个配置参数，其实配置参数应该写在公共或者模块下的config配置文件中，然后在获取使用，这里我就直接使用了。
            $sms->accessKeyId = 'LTAIetnFDud6qAhM';
            $sms->accessKeySecret = 'uIHPvwzBzDh4cht27LANlku7oP8ekt';
            $sms->signName = '旭日东升';
            $sms->templateCode = 'SMS_113455188';

            //$mobile为手机号
            $mobile = $number;
            //模板参数，自定义了随机数，你可以在这里保存在缓存或者cookie等设置有效期以便逻辑发送后用户使用后的逻辑处理
            $code = $this->random();
            session('code',$code);//用于比对
            $templateParam = array("code"=>$code);
            $m = $sms->send($mobile,$templateParam);
            //类中有说明，默认返回的数组格式，如果需要json，在自行修改类，或者在这里将$m转换后在输出
            // dump($m);
            if($m){
                return json(['info' => '发送成功', 'error_code' => '2']);
            }else{
                return json(['info' => '发送失败', 'error_code' => '3']);
            }
        }else{

            return $this->fetch('register');
        }
    }

    //生成所发送的验证码并返回
    public function random()
    {
        $length = 6;
        $char = '0123456789';
        $code = '';
        while(strlen($code) < $length){
            //截取字符串长度
            $code .= substr($char,(mt_rand()%strlen($char)),1);
        }
        return $code;
    }

    public function add(){
        $code = session('code');
        $data = I('post.');
        // dump($data );die;
        $admin = M('admin')->where(['phone'=>$data['tel']])->select();
        // dump($admin);die;
        if($admin['phone'] == $data['tel']){
            $this->error('手机已经注册,请登录!');
        }else{
            if($data['pass'] != $data['passQ']){
                $this->error('密码错误！不一致。');
            }
            if($data['validate'] != $code){
                $this->error("验证码错误");
            }




            $data['add_time'] = time();
            $data['role_new_id'] = 2;//暂时使用2来代表加盟商二级权限。
            $data['user_name'] = $data['name'];
            $data['password'] = md5($data['pass']);
            $data['phone'] = $data['tel'];
            $client = M('admin')->add($data);//默认第一次注册都是2

            $time = date('Y-m',time());
            $stat_period = intval($this->findNum($time));


            DB::name('client_month_stat_2019')->add(['stat_period'=>$stat_period,'client_id'=>$client]);


        }
        $this->success('注册成功！请登录。',U('Phone/logina/index'));
    }

    //忘记密码验证和修改
    public function verify(){
        if(IS_POST){
            $code = session('code');
            $data = I('post.');
            $admin = M('admin')->where(['phone'=>$data['tel']])->find();
            if(!$admin){
                $this->error('没有次账号！请注册');
            }else{
                if($data['tel'] == $admin['phone']){
                    if($data['validate'] != $code){
                        $this->error("验证码错误");
                    }elseif($data['pass'] != $data['passQ']){
                        $this->error('密码错误！不一致。');
                    }
                    $r['phone'] = $data['tel'];
                    $r['password'] = md5($data['pass']);
                    M('admin')->where(['phone'=>$data['tel']])->save($r);
                }
            }
            return $this->success("密码修改成功",U('Phone/logina/index'));
        }else{
            return $this->fetch();
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

    public function again()
    {


            $number = input('phone');//input助手函数	获取输入数据 支持默认值和过滤
            $phone = M('admin')->where(['phone'=>$number])->find();
            // halt(2113132);
            if(!$phone){
//                return json(['info' => '已注册', 'error_code' => '1']);//返回已注册手机
                return json(['info' => '此账号尚未注册','error_code' => '1']);
            }
            //获取对象，如果上面没有引入命名空间，可以这样实例化：$sms = new \alisms\SendSms()
            $sms = new SendSms();
            //设置关键的四个配置参数，其实配置参数应该写在公共或者模块下的config配置文件中，然后在获取使用，这里我就直接使用了。
            $sms->accessKeyId = 'LTAIetnFDud6qAhM';
            $sms->accessKeySecret = 'uIHPvwzBzDh4cht27LANlku7oP8ekt';
            $sms->signName = '旭日东升';
            $sms->templateCode = 'SMS_113455188';

            //$mobile为手机号
            $mobile = $number;
            //模板参数，自定义了随机数，你可以在这里保存在缓存或者cookie等设置有效期以便逻辑发送后用户使用后的逻辑处理
            $code = $this->random();
            session('code',$code);//用于比对
            $templateParam = array("code"=>$code);
            $m = $sms->send($mobile,$templateParam);
            //类中有说明，默认返回的数组格式，如果需要json，在自行修改类，或者在这里将$m转换后在输出
            // dump($m);
            if($m){
                return json(['info' => '发送成功', 'error_code' => '2']);
            }else{
                return json(['info' => '发送失败', 'error_code' => '3']);
            }
        }

}