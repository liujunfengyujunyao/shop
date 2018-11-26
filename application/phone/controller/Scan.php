<?php

/*
机台扫码注册激活控制器
*/

namespace app\Phone\controller;
use think\Controller;
use app\common\logic\app\Jssdk;
use think\Page;
use think\Request;
use think\Db;
use think\Session;
class Scan extends Base{

	public function index(){
		if(IS_POST){
			$id = session('client_id');
			$data = I('post.');
			// dump($data);die;
			$machine = M('machine')->where(['uuid'=>$data['sn']])->find();
			// dump($machine);die;
			if(!$machine){
				return json(['info' => 'SN号不存在', 'error_code' => '1']);
			}else{
				if($machine['status'] == 0){
					return json(['info' => '机台已删除。', 'error_code' => '2']);
				}elseif($machine['client_id']  != 0){
					return json(['info' => '机台已注冊。', 'error_code' => '3']);
				
				}else{

					M('machine')->where(['uuid'=>$data['sn']])->save(['client_id'=>$id,'model'=>3]);

					//如果绑定的设备为福袋机 增加client_luck_conf数据
					if($machine['type_id'] == 2){
						$luck = DB::name('client_luck_conf')->where(['client_id'=>$id])->find();
						if ($luck) {
							return json(['info' => '注册成功。', 'error_code' => '4']);
						}
						$add = array(
							'client_id' => $id,
							);
						for ($i=0; $i <9 ; $i++) { 
							DB::name('client_luck_conf')->add($add);
						}
					}

					return json(['info' => '注册成功。', 'error_code' => '4']);
				}
			}
			// $this->success("机台上线成功!",U('Phone/Scan/index'));
		}else{
			return $this->fetch();
		}
	}

	public function add(){
		if(IS_POST){
			$id = $_SESSION['think']['client_id'];
		
			$data = I('post.');
			if($data['sn'] == NULL){
				$this->error('sn是机台识别不能为空');
			}elseif($data['machine_name']== NULL){
				$this->error('机台名称不能为空');
			}
			// elseif ($data['type_id'] == NULL) {
			// 	$this->error('请选择机台类型');
			// }
			$Machine = M('machine')->where(['sn'=>$data['sn']])->find();
			if(!$Machine){
				$this->error('请检查SN号！平台没有此机台。');
			}elseif ($Machine['status'] == 0) {
				$this->error('机台已删除。');
			}elseif($Machine['client_id'] != 0){
				$this->error('机台已注冊。');
			}else{
				halt($data);
				// dump('tiao');die;
				$data['addtime'] = time();
				$data['client_id'] =$id;
				$data['status'] = 1;
				$Machine_id = M('machine')->where(['machine_id'=>$Machine['machine_id']])->save($data);

			}
			$this->success("机台上线成功!",U('Phone/Scan/index'));
		}else{
			// $type = M('type')->select();
			// $this->assign('type',$type);
			return $this->fetch();
		}
		// // $params = $GLOBALS['HTTP_RAW_POST_DATA'];
		// $data = file_get_contents('php://input');
		// // $params = json_decode($params,true);
		// dump($data);die;
	}

	// public function index(){
	// 	dump(sdfsd);die;
 //      // $jssdk = new JSSDK("yourAppID", "yourAppSecret");
	// 		// $config = array(
	// 		// 	'appId'     =>  'wx028aebed482b9a07',
 //   //      		'appSecret' =>  'a6b4e68a7c52b2340518dcaea2fdd069'
	// 		// );
	//  // dump($config);die;
	// 	// require_once "jssdk.php";
	// 		$jssdk = new JSSDK("wx028aebed482b9a07", "a6b4e68a7c52b2340518dcaea2fdd069");
	// 		$signPackage = $jssdk->GetSignPackage();
	// 		dump($signPackage);die;
 //      // $obj = new Jssdk("wx7d93e0114cc3453a", "e64bda5d1006894a4f3cfb1b908dca19");
 //      // // dump($obj);die;
 //      // $signPackage = $obj->GetSignPackage(); 
 //      $this->assign('signPackage',$signPackage);
 //      return $this->fetch();
	// }
	// //未激活设备
	// public function machine(){
	// 	$conut = M("machine")->where("status = 0 || status =2")->count();
 //       	$Page = $pager = new Page($conut,10);
	// 	$machine = M('machine')->alias('t1')->field('t1.machine_id,t1.machine_name,t1.status,t2.type_name')->where("t1.status = 0 || t1.status = 2")->join('type t2','t2.type_id = t1.type_id')->order("t1.machine_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
	// 	$show  = $Page->show();
	// 	$jssdk = new JSSDK("wx028aebed482b9a07", "a6b4e68a7c52b2340518dcaea2fdd069");
	// 		$signPackage = $jssdk->GetSignPackage();
	// 		// dump($signPackage);die;
 //      // $obj = new Jssdk("wx7d93e0114cc3453a", "e64bda5d1006894a4f3cfb1b908dca19");
 //      // // dump($obj);die;
 //      // $signPackage = $obj->GetSignPackage(); 
 //      $this->assign('signPackage',$signPackage);
 //      // return $this->fetch();
	// 	$this->assign('pager',$pager);
 //        $this->assign('show',$show);
	// 	$this->assign('machine',$machine);
	// 	return $this->fetch('index');
	// }
	// public function get_code(){
	// 	$id = I("get.id");
	// 	$machine = M("machine")->where(['machine_id'=>$id])->find();
	// 	// $this->assign('machine',$machine);
	// 	// return $this->fetch();
	// 	dump($machine);die;
	// }
	// //手动添加机台
	// public function add(){
	// 	if(IS_POST){
	// 		$id = session('weixin_id');
	// 		$data = I('post.');
	// 		// dump($data);die;
	// 		if($data['sn'] == NULL){
	// 			$this->error('sn是机台识别不能为空');
	// 		}elseif($data['machine_name']== NULL){
	// 			$this->error('机台名称不能为空');
	// 		}elseif ($data['type_id'] == NULL) {
	// 			$this->error('请选择机台类型');
	// 		}
	// 		$Machine = M('machine')->where(['sn'=>$data['sn']])->find();
	// 		if(!$Machine){
	// 			$this->error('请检查SN号！平台没有此机台。');
	// 		}elseif($Machine['status'] == 1){
	// 			$this->error('机台已激活，无需重复添加。');
	// 		}elseif ($Machine['status'] == 3) {
	// 			$this->error('机台已删除。');
	// 		}else{
	// 			// dump('tiao');die;
	// 			$data['addtime'] = time();
	// 			$data['client_id'] =$id;
	// 			$data['status'] = 1;
	// 			$Machine_id = M('machine')->where(['machine_id'=>$Machine['machine_id']])->save($data);

	// 		}
	// 		// return $this->fetch();
	// 		 // $this->success("机台上线成功",U('Test/Machine/index'));
	// 		$this->success("机台上线成功!",U('Test/Machine/machine'));
	// 	}else{
	// 		$type = M('type')->select();
	// 		$this->assign('type',$type);
	// 		 return $this->fetch();
	// 	}
	// }

	// public function qrcode($url="www.baidu.com",$level=3,$size=4)
 //    {
 //              Vendor('phpqrcode.phpqrcode');
 //              $errorCorrectionLevel =intval($level) ;//容错级别 
 //              $matrixPointSize = intval($size);//生成图片大小 
 //             //生成二维码图片 
 //              $object = new \QRcode();
 //              $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   
 //          }

}