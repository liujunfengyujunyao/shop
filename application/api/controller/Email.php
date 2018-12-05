<?php

namespace app\api\controller;

use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;

header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Email extends Controller{
    //批量生成二维码 根据传入的生成数量和所生成设备
    public function piliang()
    {
        $params = file_get_contents('php://input');file_put_contents('email.txt',$params);

        $params = json_decode($params, true);

        $client_id = $params['client_id'];
//        $user_id = $user_id;//老板的ID
        /* $auth = DB::name('machine')->where(['client_id'=>$user_id,'type_id'=>2])->find();
         if (!$auth){
             $result = "luck_machine_null";//名下没有福袋机
             return json($result);
         }*/
        $number = 100;//生成二维码的数量
//        $machine_id = 2;//生成二维码的机器  **必须已经被绑定client_id**


//        $client_id = DB::name('machine')->where(['machine_id'=>$machine_id])->getField("client_id");
//        // halt($client_id);
//        if(is_null($client_id)){
//            $this->error('此设备尚未被绑定');
//        }
        $res = DB::name('client_luck_key')->where(['client_id' => $client_id])->find();
        $phone = DB::name('admin')->where(['admin_id' => $client_id])->getField('phone');


        if ($res) {
            $start = DB::name('client_luck_key')->where(['client_id' => $client_id])->max('key_id');
            $start = $start + 1;
        } else {
            $start = 1;//第一次插入
        }


        for ($i = $start; $i < $number + $start; $i++) {
    		$this->pin($phone,$i);//生成的编号和所属人电话
//            $this->pin($email, $i);//生成的编号和所属人邮箱
        }
        echo "完成";
        return json("ok");

    }

    public function pin($phone, $i)
    {
        //生成人员的账号
        vendor('topthink.think-image.src.Image');
        vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();

        error_reporting(E_ERROR);
        $key = md5(rand(1, 999999) . time());
        // halt($key);
        $vam = $i;
        // $url = "http://".$_SERVER['HTTP_HOST']."/home/luck/index?device_secret=".$key;
        // $url = "http://192.168.1.133/home/lottery/index?device_secret=".$key;
        $url = "http://www.12202.com.cn/tp/index.php/home/Luck/login?device_secret=" . $key;

        $url = urldecode($url);

        $qr_code_path = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/qr_code/' . date("Y-m-d") . "-" . $phone . '/';


        if (!file_exists($qr_code_path)) {

            mkdir($qr_code_path);
        }

        /* 生成二维码 */

        $qr_code_file = $qr_code_path . $vam . '_' . $phone . '.png';
        // $object->png($url, $qr_code_file,QR_ECLEVEL_M);
        $object->png($url, $qr_code_file, QR_ECLEVEL_M, 4, 8);
        //把生成的二维码存入数据库
        //  .........................根据自己需要存入...........................
        //
        $client_id = DB::name('admin')->where(['phone' => $phone])->getField('admin_id');
        $add = array(
            'device_secret' => $key,
            'client_id' => $client_id,
            'key_id' => $vam,
        );
        DB::name('client_luck_key')->add($add);

        // $image = $this->mark_photo('图片路径','要显示的名字','保存的路径');//在图片中添加文字
        $this->mark_photo($qr_code_file, $vam, $qr_code_file);//在图片中添加文字拼接图片

    }
    public function mark_photo($background,$text,$filename){
        vendor('topthink.think-image.src.Image');
        $image = imagecreatefrompng($background);
        /**/
        //       $info = getimagesize($background); // 获取图片信息
        // $type = image_type_to_extension($info[2],false); // 获取图片扩展名
        // $fun  = "imagecreatefrom{$type}"; // 构建处理图片方法名-关键是这里
        // $image = $fun($background); // 调用方法处理

        /**/
        $font = ROOT_PATH.'public/static/fonts/msyh.ttf'; // 字体文件
        $color = imagecolorallocate($image,0,0,0); // 文字颜色
        imagettftext($image, 15, 0, 85,212, $color, $font, $text); // 创建文字
        // imagettftext($image, 20, 0, 80, 292, $color, $font, $code); // 创建文字
        header("Content-Type:image/png");
        ImagePng($image, $filename);//保存新生成的
        imagedestroy($image);//删除原来的图片
        // imagepng($image);//输出图片
    }

    public function test(){
        $params = array(
            'out_trade_no' => "ababababab",
            'client_id' => 11,
        );
        $url ="http://192.168.1.144/api/email/piliang";
//        $url ="http://liujunfeng.imwork.net:41413/api/email/piliang";
        $result = json_curl($url,$params);
        dump($result);die;
    }

    public function email(){
        vendor('phpmailer.class.phpmailer.php');
        vendor('phpmailer.class.smtp.php');
        $email_smtp=C('EMAIL_SMTP');
        $email_username=C('EMAIL_USERNAME');
        $email_password=C('EMAIL_PASSWORD');
        $email_from_name=C('EMAIL_FROM_NAME');
        $email_smtp_secure=C('EMAIL_SMTP_SECURE');
        $email_port=C('EMAIL_PORT');
        if(empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)){
            return array("error"=>1,"message"=>'邮箱配置不完整');
        }
//        require_once './ThinkPHP/Library/Org/Nx/class.phpmailer.php';
//        require_once './ThinkPHP/Library/Org/Nx/class.smtp.php';
        $phpmailer=new \Phpmailer();
        halt($phpmailer);
        // 设置PHPMailer使用SMTP服务器发送Email
        $phpmailer->IsSMTP();
        // 设置设置smtp_secure
        $phpmailer->SMTPSecure=$email_smtp_secure;
        // 设置port
        $phpmailer->Port=$email_port;
        // 设置为html格式
        $phpmailer->IsHTML(true);
        // 设置邮件的字符编码'
        $phpmailer->CharSet='UTF-8';
        // 设置SMTP服务器。
        $phpmailer->Host=$email_smtp;
        // 设置为"需要验证"
        $phpmailer->SMTPAuth=true;
        // 设置用户名
        $phpmailer->Username=$email_username;
        // 设置密码
        $phpmailer->Password=$email_password;
        // 设置邮件头的From字段。
        $phpmailer->From=$email_username;
        // 设置发件人名字
        $phpmailer->FromName=$email_from_name;
        // 添加收件人地址，可以多次使用来添加多个收件人
        if(is_array($address)){
            foreach($address as $addressv){
                $phpmailer->AddAddress($addressv);
            }
        }else{
            $phpmailer->AddAddress($address);
        }
        // 设置邮件标题
        $phpmailer->Subject=$subject;
        // 设置邮件正文
        $phpmailer->Body=$content;
        // 发送邮件。
        if(!$phpmailer->Send()) {
            $phpmailererror=$phpmailer->ErrorInfo;
            return array("error"=>1,"message"=>$phpmailererror);
        }else{
            return array("error"=>0);
        }
    }
}