<?php

namespace app\phone\controller;
use think\Db;

class Group extends Base{

	//新建群组
	public function add_store(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			halt($_POST);		
			$group_name = input('post.group_name');//名称
			$game_price = input('post.game_price');//群组统一游戏价格
			$goods_price = input('post.goods_price');//群组统一商品价格
			$odds = input('post.odds');//群组统一赔率
			$machine_id = input('post.machine_id/a');
			if(!user_id || !$group_name || !$game_price || !$goods_price || !$odds){
				return $this->error('参数不全');
			}else{
				$data = array(
					'user_id'=>$user_id,
					'group_name'=>$group_name,
					'game_price'=>$game_price,
					'goods_price'=>$goods_price,
					'odds'=>$odds,
					'machine_id'=>implode(',',$machine_id),
					'create_time'=>time()
					);
				$res = DB::name('machine_group')->add($data);
				if($res != false){
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
			$machine_id = input('post.machine_id');
			$data = array();
			if($group_name){
				$data['group_name'] = $group_name;
			}
			if(game_price){
				$data['game_price'] = $game_price;
			}
			if(goods_price){
				$data['goods_price'] = $goods_price;
			}
			if($odds){
				$data['odds'] = $odds;
			}
			if($machine_id){
				$data['machine_id'] = $machine_id;
			}
			if(empty($data)){
				return $this->error('参数错误');
			}else{
				$res = Db::name('machine_group')->where(['id'=>$group_id])->save($data);
				if($res != false){
					return $this->success('修改成功');
				}else{
					return $this->error('修改失败');
				}
			}
		}else{ //页面信息
			$group_id = input('get.id');
			$group_info = Db::name('machine_group')->field('group_name,game_price,odds,goods_price,group_machine')->where(['id'=>$group_id])->find();
			$machine_list = Db::name('machine')->field('machine_id,machine_name')->where('machine_id','in',$group_info['group_machine'])->select();
			$this->assign('name',$group_info['group_name']);
			$this->assign('game_price',$group_info['game_price']);
			$this->assign('goods_price',$group_info['goods_price']);
			$this->assign('odds',$group_info['odds']);
			$this->assign('machine_list',$machine_list);
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