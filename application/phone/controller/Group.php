<?php

namespace app\phone\controller;
use think\Db;

class Group extends Base{

	//新建群组
	public function add_store(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){		
			$group_name = input('post.group_name');//名称
			$game_price = input('post.game_price');//群组统一游戏价格
			$goods_price = input('post.goods_price');//群组统一商品价格
			$odds = input('post.odds');//群组统一赔率
			$machine_id = input('post.machine_id/a');
			if(!user_id || !$group_name || !$game_price || !$goods_price || !$odds){
				return $this->error('参数不全');
			}else{
				if(empty($machine_id)){
					$machine_id = array();
				}else{
					$machine_id = array_unique($machine_id);
				}
				$data = array(
					'user_id'=>$user_id,
					'group_name'=>$group_name,
					'game_price'=>$game_price,
					'goods_price'=>$goods_price,
					'odds'=>$odds,
					'group_machine'=>implode(',',$machine_id),
					'create_time'=>time()
					);		
				$res = DB::name('machine_group')->add($data);
				if($res != false){
					foreach ($machine_id as $k => $v) {
						Db::name('machine')->where(['machine_id'=>$v])->setField('group_id',$res);
					}
					return $this->success('新建成功',U('group/store_list'));
				}else{
					return $this->error('新建失败');
				}
			}
		}else{
			$machine_list = Db::name('machine')->where(['client_id'=>$user_id,'group_id'=>0])->field('machine_id,machine_name')->select();
			$this->assign('list',$machine_list);
			return $this->fetch();
		}
	}


	//编辑群组
	public function edit_store(){
		//提交修改信息
		if(IS_POST){
			$group_id = input('post.group_id');
			$group_name = input('post.group_name');//名称
			$game_price = input('post.game_price');//群组统一游戏价格
			$goods_price = input('post.goods_price');//群组统一商品价格
			$odds = input('post.odds');//群组统一赔率
			$machine_id = input('post.machine_id/a');
			
			$old_machine = explode(',',input('post.old_machine'));

			if(!$group_name || !$game_price || !$goods_price || !$odds){
				return $this->error('参数不全');
			}
			if(empty($machine_id)){
				$machine_id = array();
			}else{
				$machine_id = array_unique($machine_id);
			}
			$data = array();
			$data['group_name'] = $group_name;
			$data['game_price'] = $game_price;
			$data['goods_price'] = $goods_price;
			$data['odds'] = $odds;
			$data['group_machine'] = implode(',',$machine_id);
			//halt($data);
			if(empty($data)){
				return $this->error('参数错误');
			}else{
				$res = Db::name('machine_group')->where(['id'=>$group_id])->save($data);
				$admin = Db::name('admin')->where('FIND_IN_SET(:id,group_id)',['id'=>$group_id])->field('admin_id,nav_list,machine_pwd')->select();
				if($res !== false){
					foreach ($old_machine as $k => $v) {//删除的机器
						if(!in_array($v,$machine_id)){
							Db::name('machine')->where(['machine_id'=>$v])->setField('group_id',0);
							foreach ($admin as $kk => $vv) {
						        $commandid = $this->get_command('delete_user',$v,$vv['admin_id']);
								$msg = array(
									'msgtype'=>'delete_user',
									'commandid'=>intval($commandid),
									'user_id'=>$vv['admin_id']
									);
								$this->post_to_server($msg,$v);
							}
						}
					}
					foreach ($machine_id as $k => $v) {//新增的机器
						if(!in_array($v,$old_machine)){
							Db::name('machine')->where(['machine_id'=>$v])->setField('group_id',$group_id);
							foreach ($admin as $kk => $vv) {
						       	$msg['msgtype'] = 'user_permissions';
								$msg['managers'] = array(
									'userid'=>$vv['admin_id'],
									'permissions'=>$vv['nav_list'],
									'password'=> $vv['machine_pwd']
									);
								$commandid = $this->get_command('user_permissions',$v);
								$msg['commandid'] = intval($commandid);
								$this->post_to_server($msg,$v);
							}

						}
					}
					return $this->success('修改成功',U('group/store_list'));
				}else{
					return $this->error('修改失败');
				}
			}
		}else{ //页面信息
			$user_id = $_SESSION['think']['client_id'];
			$group_id = input('get.id');
			$group_info = Db::name('machine_group')->field('group_name,game_price,odds,goods_price,group_machine')->where(['id'=>$group_id])->find();
			//halt($group_info);
			$group_machine = Db::name('machine')->field('machine_id,machine_name')->where('machine_id','in',$group_info['group_machine'])->select();
			//halt($group_machine);
			$machine_list = Db::name('machine')->where(['client_id'=>$user_id,'group_id'=>0])->field('machine_id,machine_name')->select();
			$this->assign('group_id',$group_id);
			$this->assign('name',$group_info['group_name']);
			$this->assign('game_price',$group_info['game_price']);
			$this->assign('goods_price',$group_info['goods_price']);
			$this->assign('odds',$group_info['odds']);
			$this->assign('old_machine',$group_info['group_machine']);
			$this->assign('list',$machine_list);
			$this->assign('gmachine',$group_machine);
			return $this->fetch();
		}
	}

	//群组列表
	public function store_list(){
		$user_id = $_SESSION['think']['client_id'];
		$list = Db::name('machine_group')->where(['user_id'=>$user_id])->field('id,group_name')->select();
		$this->assign('list',$list);
		return $this->fetch();
	}



}

?>