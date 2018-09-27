<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Game extends Controller {
		//接收
		public function machine_list(){
			//where()条件里面添加底层传过来的经纬度 找出最近的
			$machine = DB::name('machine')->field('machine_id,machine_name,address')->select();

			$machine = json_encode($machine,JSON_UNESCAPED_UNICODE);
		
			return $machine;
		}

		//接收设备ID 返回商品状态
		public function goods_list(){
			$data = $GLOBALS['HTTP_RAW_POST_DATA'];

			$data = json_decode($data,true);
			
			$machine_id = $data['machine_id'];

			$goods_list = DB::name('client_machine_stock')->where(['machine_id'=>$machine_id])->select();
			foreach ($goods_list as $key => $value) {
				$result[$key]['goods_name'] = $value['goods_name'];
				if ($value['goods_num'] == 0 && $value['lock_num'] == 0) {
					$result[$key]['status'] = 2;//售罄
				}elseif($value['goods_num'] - $value['lock_num'] == 0){
					$result[$key]['status'] = 3;//锁定
				}else{
					$result[$key]['status'] = 1;//可以购买
				}
				$result[$key]['location'] = $value['location'];

			}
			$result = json_encode($result,JSON_UNESCAPED_UNICODE);
			return $result;

		}

		//判断金额是否可以进行游戏
		public function game_start(){
			$data = $GLOBALS['HTTP_RAW_POST_DATA'];
			$data = json_encode($data);
			$member_id = $data['user_id'];//用户ID all_user表
			$machine_id = $data['machine_id'];
			$game_price = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('game_price');
			$member = DB::name('all_user')->where(['id'=>$member_id])->find();

			if ($member['gold'] - $game_price < 0) {
				$data = array(
					'msgtype' => 'error',

					);
			}else{
				$data = array(
					'msgtype' => 'success',
					'gold' => $member - $game_price,
					'game_log_id' => time().rand(100,999),
					);
			}

			$result = json_encode($data,JSON_UNESCAPED_UNICODE);
			return $result;

		}

		//接收游戏结果
		public function game_result(){
			$data = $GLOBALS['HTTP_RAW_POST_DATA'];
			$data = json_encode($data);
			$game_log_id = $data['game_log_id'];
			$result = $data['result'];
			if ($result == 1) {
				//游戏成功,生成取货码
				
			}

		}

		public function test(){
			
		}
}
	