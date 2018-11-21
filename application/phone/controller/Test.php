<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tpshop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 *
 */ 
namespace app\phone\controller; 
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Db;
use think\Cache;
use think\Lang;
class Test extends Controller {
    
    public function index(){      
	   $mid = 'hello'.date('H:i:s');
       //echo "测试分布式数据库$mid";
       //echo "<br/>";
       //echo $_GET['aaa'];       
       //  M('config')->master()->where("id",1)->value('value');
       //echo M('config')->cache(true)->where("id",1)->value('value');
       //echo M('config')->cache(false)->where("id",1)->value('name');
       echo $config = M('config')->cache(false)->where("id",1)->value('value');
        // $config = DB::name('config')->cache(true)->query("select * from __PREFIX__config where id = :id",['id'=>2]);
         print_r($config);
       /*
       //DB::name('member')->insert(['mid'=>$mid,'name'=>'hello5']);
       $member = DB::name('member')->master()->where('mid',$mid)->select();
	   echo "<br/>";
       print_r($member);
       $member = DB::name('member')->where('mid',$mid)->select();
	   echo "<br/>";
       print_r($member);
	*/   
//	   echo "<br/>";
//	   echo DB::name('member')->master()->where('mid','111')->value('name');
//	   echo "<br/>";
//	   echo DB::name('member')->where('mid','111')->value('name');
         echo C('cache.type');
    }  
    
    public function redis(){
        Cache::clear();
        $cache = ['type'=>'redis','host'=>'192.168.0.201'];        
        Cache::set('cache',$cache);
        $cache = Cache::get('cache');
        print_r($cache);         
        S('aaa','ccccccccccccccccccccccc');
        echo S('aaa');
    }
    
    public function table(){
        $t = Db::query("show tables like '%tp_goods_2017%'");
        print_r($t);
    }
    
        public function t(){
                
         //echo $queue = \think\Cache::get('queue');
         //\think\Cache::inc('queue',1);
         //\think\Cache::dec('queue');
        $res = DB::name('config')->cache(true)->find();
        print_r($res);
              DB::name('config')->update(['id'=>1,'name'=>'http://www.tp-shop.cn11111']);
        $res = DB::name('config')->cache(true)->find();
        print_r($res);
        
        
    }
    // 多语言测试
    public function lang(){
        header("Content-type: text/html; charset=utf-8");
        // 设置允许的语言
        //Lang::setAllowLangList(['zh-cn','en-us']);
        //echo $_GET['lang'];
        echo Lang::get('hello_TPshop');
        echo "<br/>";
        echo Lang::get('where');
        //{$Think.lang.where}
        //return $this->fetch();
    }

    public function create(){
      if (IS_POST) {
        $data = I('post.');

        $add['sn'] = $data['sn'];
        $error = DB::name('machine')->where(['sn'=>$data['sn']])->find();
        if ($error) {
          $this->error('重复添加');
        }elseif($data['sn']==""){
          $this->error("sn为空");
        }elseif($data['token'] == ""){
          $this->error("缺少参数");
        }
        $add['access_token'] = $data['token'];
        $add['version_id'] = $data['version'];
        $add['px'] = $data['bili'];
        $add['type_id'] = $data['type'];
        if($data['type'] == 1){
            $add['type_name'] = "口红机";
          }elseif($data['type'] == 2){
            $add['type_name'] = "福袋机";
          }elseif($data['type'] == 3){
            $add['type_name'] = "售币机";
          }elseif($data['type'] == 4){
            $add['type_name'] = "彩票机";
          }else{
            $add['type_name'] = "娃娃机";
          }
          $time = time();
          $add['machine_name'] = $add['type_name'];
          $add['uuid'] = md5($time . $add);
          // halt($add);
          $res = DB::name('machine')->add($add);
          if($res){
            
             QRcode($add['uuid']);
          }else{
            echo "error";
          }
      }else{
        return $this->fetch();
      }
   
    
  }

  public function access(){
    // {"msgtype":"login_auth","sn":123456789,"timestamp":1542696956,"poslong":"39.91488908","poslat":"116.40387397","version":"12345","signature":"FF369FACBAE7C57B8582C40AE3778F9106189A92"}
    $arr = array(
      "msgtype" => "login_auth",
      "sn" => "123456789",
      "timestamp" => 1542699016,
      // "poslong" => "39.91488908",
      // "poslat" => "116.40387397",
      // "version" => "12345",
      "access_token" => "12345",
      );
    $arr = json_encode($arr,true);
    dump($arr);
    $s = sha1($arr);
    halt($s);
  }

  public function shijian(){
      $y = date("Y");
          $m = date("m");
          $d = date("d");

      $start = mktime(0,0,0,$m,$d,$y);
      halt($start);
  }

  public function config(){
    $machine_id = 1;
    $x = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->delete();
    halt($x);
  }

  public function time(){
    $post = $GLOBALS['HTTP_RAW_POST_DATA'];
    dump($post);
    $time = time();
    halt($time);
  }
}