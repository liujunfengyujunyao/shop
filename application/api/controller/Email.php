<?php

namespace app\api\controller;

use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
set_time_limit(0);
ini_set('memory_limit', '128M');
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Email extends Controller{
    //批量生成二维码 根据传入的生成数量和所生成设备
    public function piliang()
    {

        $params = file_get_contents('php://input');
//        $newLog ='log_time:'.date('Y-m-d H:i:s').$params;
//        file_put_contents('./email.txt', $newLog.PHP_EOL, FILE_APPEND);
        $params = json_decode($params, true);
//        $email = $params['email'];
//        $client_id = $params['client_id'];
        $out_trade_no = $params['out_trade_no'];
        $timestamp = time();
        $push = DB::name('luckpay_log')->where(['out_trade_no'=>$out_trade_no])->getField('is_push');
        $info = DB::name('luckpay_log')->where(['out_trade_no'=>$out_trade_no])->find();

        DB::name('luckpay_log')->where(['out_trade_no'=>$out_trade_no])->save(['status'=>1]);//修改支付状态
        $email = $info['email'];
//        if ($push == 1){
//            //已经发送过email
//            return fasle;die;
//        }
//        $user_id = $user_id;//老板的ID
        /* $auth = DB::name('machine')->where(['client_id'=>$user_id,'type_id'=>2])->find();
         if (!$auth){
             $result = "luck_machine_null";//名下没有福袋机
             return json($result);
         }*/
//        $number = 1000;//生成二维码的数量
        $number = $params['number'];
//        $machine_id = 2;//生成二维码的机器  **必须已经被绑 定client_id**


//        $client_id = DB::name('machine')->where(['machine_id'=>$machine_id])->getField("client_id");
//        // halt($client_id);
//        if(is_null($client_id)){
//            $this->error('此设备尚未被绑定');
//        }
//        $res = DB::name('client_luck_key')->where(['client_id' => $client_id])->find();
        $res = DB::name('client_luck_key')->where(['client_id' => $info['client_id']])->find();

//        $phone = DB::name('admin')->where(['admin_id' => $client_id])->getField('phone');
        $phone = DB::name('admin')->where(['admin_id' => $info['client_id']])->getField('phone');


        if ($res) {
//            $start = DB::name('client_luck_key')->where(['client_id' => $client_id])->max('key_id');
            $start = DB::name('client_luck_key')->where(['client_id' => $info['client_id']])->max('key_id');
            $start = $start + 1;
        } else {
            $start = 1;//第一次插入
        }



        for ($i = $start; $i < $number + $start; $i++) {
    		$this->pin($phone,$i,$timestamp);//生成的编号和所属人电话
//            $this->pin($email, $i);//生成的编号和所属人邮箱
        }
        //调用完成 -> 压缩这个文件夹 $datalist  &   压缩后的放置路径$filename
//        $datalist = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/qr_code/' . date("Y-m-d ") . "-" . $phone . '/';
//        $datalist = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone . "/";
        $datalist = "./public/upload/qr_code/" . $timestamp . "-" . $phone . "/";
//        $filename = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone .".zip";
        $filename = "./public/upload/qr_code/" . $timestamp . "-" . $phone .".zip";
        $this->yasuo($datalist,$filename);//压缩这文件夹
//        $this->send_email('qukaliujun@163.com','测试邮件','抽奖二维码',$filename);//发送压缩包
        $email_result = $this->send_email($email,'测试邮件','抽奖二维码',$filename);//发送压缩包
        if($email_result['error'] == 0){
            delDirAndFile($datalist);//递归删除文件夹
            unlink($filename);//删除压缩文件
            DB::name('luckpay_log')->where(['out_trade_no'=>$out_trade_no])->save(['is_push'=>1]);
            //存入error表
            $add = array(
                'errid' => 3,
                'client_id' => $info['client_id'],
                'time' => time(),
                'errmsg' => "抽奖码邮件已发送",
            );
            DB::name('error')->add($add);
            echo "完成";
        }else{
            halt($email_result);
        }
//


    }

    /*拼接二维码*/
    public function pin($phone, $i,$timestamp)
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
//        $url = "http://www.12202.com.cn/tp/index.php/home/Luck/login?device_secret=" . $key;
        $url = "http://www.goldenbrother.cn/index.php/choujiang/Luck/login?device_secret=" . $key;

        $url = urldecode($url);

        //文件夹的存放地址
//        $qr_code_path = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/qr_code/' . date("Y-m-d ") ."-". $phone . '/';
        $qr_code_path = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/qr_code/' . $timestamp ."-". $phone . '/';


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
//        $font = ROOT_PATH.'public/static/fonts/MSYH.TTF'; // 字体文件
        $font = $_SERVER['DOCUMENT_ROOT'].'/public/static/fonts/MSYH.TTF'; // 字体文件
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

    public function send_email($address,$subject,$content,$zip){
//    public function send_email(){
        vendor('phpmailer.class#phpmailer');
        vendor('phpmailer.class#smtp');
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
//        halt($phpmailer);
//        $phpmailer->Addattachment('a.png','test.png');
//    halt($phpmailer->Timeout());
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
        $phpmailer->SMTPSecure = 'ssl';
//        $phpmailer->AddAttachment('D:\WWW\shop\application\api\code.rar','test.rar');
//        $phpmailer->AddAttachment('./public/upload./qr_code./2018-12-05-13683141819.zip','test.rar');
        $phpmailer->AddAttachment($zip,'qrcode.rar');
//        D:\WWW\shop\public\upload\qr_code\2018-12-05-13683141819.zip

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


    public function test_email(){
        $phone = 13683141819;
        $filename = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone .".zip";
        halt($filename);
//        $zip = "./public/upload./qr_code./2018-12-05-13683141819.zip";
        $res = $this->send_email('qukaliujun@163.com','测试压缩邮件','抽奖二维码',$filename);

        halt($res);

    }

    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
        }halt($filename);
        @closedir($path);
    }



public function test_yasuo(){
    $zip=new \ZipArchive();


    $ce = $zip->open('D:\WWW\shop\application\api\code\a.png', \ZipArchive::OVERWRITE);dump($ce);
    if ($ce === TRUE){
        halt(1);
    }else{
        halt(2);
    }
    if($zip->open('imagess.zip', \ZipArchive::OVERWRITE)=== TRUE){
       $x = $this->addFileToZip('code/', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法

        $zip->close(); //关闭处理的zip文件
    }
    halt($ce);
//    halt($zip->open('images.zip', \ZipArchive::OVERWRITE));
}


    //需要添加 需要压缩文件的路径$datalist  &   压缩后的放置路径$filename
    public function yasuo($datalist,$filename){
        //获取列表
        $datalist=list_dir($datalist);//需要压缩的文件夹路径
//        $filename = "./public/upload/qr_code/2018-12-05-13683141819.zip"; //最终生成的文件名（含路径）
        if(!file_exists($filename)){
            //重新生成文件
            $zip = new \ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            if ($zip->open($filename, \ZIPARCHIVE::CREATE)!==TRUE) {
                exit('无法打开文件，或者文件创建失败');
            }
            foreach( $datalist as $val){
                if(file_exists($val)){
                    $zip->addFile( $val, basename($val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                }
            }
            $zip->close();//关闭
        }
        if(!file_exists($filename)){
            exit("创建失败"); //即使创建，仍有可能失败。。。。
        }

    }

    //测试压缩文件
public function tuozhan(){
    $phone = 13683141819;
    $datalist = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone . "/";

    $filename = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone .".zip";
    $this->yasuo($datalist,$filename);
//    halt($data);
}

    //测试删除文件
public function tuozhan1(){
    $phone = 13683141819;
    $datalist = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone . "/";
//    $a = "./public/upload/qr_code/a";
//    delDirAndFile($a);die;
    $filename = "./public/upload/qr_code/" . date("Y-m-d ") . "-" . $phone .".zip";
//    $test = "./public/upload/qr_code/2018-12-08 -13683141819.zip";
    unlink($filename);
//    unlink($datalist);
//    delDirAndFile($datalist);
//    delDirAndFile($filename);

//    delFile($filename);
}

    public function sha(){
        $params = array(
            'machinesn' => 10004,

        );
        $msg = array(
            'type' => 2,
            'px' => 2,

        );
        $sn = sha1("sn=".$params['machinesn']."&type=".$msg['type']."&px=".$msg['px']);
        halt($sn);
    }

    public function j(){
        $data = array(
            'email' => 'qukalijun@163.com',
            'out_trade_no' => "abc",
            'client_id' => 11,
        );
        halt(json_encode($data,JSON_UNESCAPED_UNICODE));
    }
    public function font(){
        $msgtype = array(
            'msgtype' => "machine_mode error",
        );
        $msg = array(
            'msg' => $msgtype,
            'machinesn' => 666,
        );

halt(json_encode($msg,JSON_UNESCAPED_UNICODE));
    }

    public function add(){
        $data = array(
            'machine_id' => 13,
            'game_id' => 1,
            'start_time' => 1544846080,
            'end_time' => 1544846089,
            'result' => 0,
            'goods_name' => "",
            'location' => '18',
            'game_log_id' => 'fb337ec8',

        );
        M('game_log')->add($data);
    }
    public function sell_log(){

    }
    public function mb4(){
        $data = array(
            'openid' => "abcd",
            'nick' => base64_encode("哈哈哈哈哈hahahah"),
            'addtime' => time(),
        );
        $en = "D、";
        $en = base64_encode($en);
        $en = base64_decode($en);
        halt($en);
        $res = M('wx_luck_user')->find(6);
        $nick = base64_decode($res['nick']);
        halt($nick);
    }











}