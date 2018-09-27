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
			$user_id = $data['user_id'];//用户ID all_user表
			$machine_id = $data['machine_id'];
			$location = $data['location'];
			$game_price = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('game_price');
			$member = DB::name('all_user')->where(['id'=>$user_id])->find();

			if ($member['gold'] - $game_price < 0) {
				$data = array(
					'msgtype' => 'error',

					);
			}else{
				$log_id = time().rand(100,999);//生成游戏流水号
				$data = array(
					'msgtype' => 'success',
					'gold' => $member - $game_price,
					'game_log_id' => $log_id,
					);
				//生成游戏记录 开始游戏insert  结束游戏update
				$log['game_log_id'] = $log_id;
				$log['user_id'] = $user_id;
				$log['machine_id'] = $machine_id;
				$log['location'] = $location;
				$log['start_time'] = time();
				DB::name('game_log')->add($log);
			}

			$result = json_encode($data,JSON_UNESCAPED_UNICODE);
			return $result;

		}

		//接收游戏结果
		public function game_result(){
			$data = $GLOBALS['HTTP_RAW_POST_DATA'];
			$data = json_encode($data);

			$end_time = $data['timestamp'];

			$game_log_id = $data['game_log_id'];

			$result = $data['result'];

			$user_id = $data['user_id'];

			$log = DB::name('game_log')->where(['game_log_id'=>$game_log_id])->find();
			if ($result == 1) {
				//游戏成功,生成游戏记录 包含:取货码 表白码
				$pick_code = encrypt_password($game_log_id);
				$express_code = md5($pick_code);
				//  1 : 添加表白码到express_code表
				$express = array(
					'express_code' => $express_code,
					);
				$express_id = DB::name('express_code')->add($express);

				//  2 : 添加提货码到pick_code表
				$pick = array(
					'pick_code' => $pick_code,
					'machine_id' => $log['machine_id'],
					'location' => $log['location'],
					'express_id' => $express_id, //提货码关联的表白码ID
					);
				$pick_up = DB::name('pick_code')->add($pick); 

				//  3 : 修改game_log表的这条数据  添加提货码 结束时间 
				$save = array(
					'end_time' => $end_time,
					'result' => 1,
					'pick_code' => $pick_code, //生成的提货码
					'pick_code_status' => 1,  //提货码的状态  1可使用   0不可用
					);
				$res = DB::name('game_log')->where(['game_log_id'=>$game_log_id])->save($save);
				
				if($res !== false){
					$result = array(
						'msgtype' => 'success',
						);
				}else{
					$result = array(
						'msgtype' => 'error',
						);
				}

			}else{
				//游戏失败  不生成激活码/表白码
				$res = DB::name('game_log')->where(['game_log_id'=>$game_log_id])->save(['end_time'=>$end_time]);
				if ($res !== false) {
					$result = array(
						'msgtype' => "success",
						);
				}else{
					$result = array(
						'msgtype' => "error",
						);
				}
			}

			$result = json_encode($result,JSON_UNESCAPED_UNICODE);
			return $result;

		}

		public function test(){
			
		}
}
	