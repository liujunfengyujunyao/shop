<?php

namespace app\phone\controller;
use think\Db;
header("content-type:text/html;charset=utf-8");

class Staff extends Base{

	//新建人员
	public function add_staff(){
		if(IS_POST){
			$user_name = '12号小弟';//input('post.user_name');//账户名称
			$phone = '110';//input('post.phone');//手机
			$pwd = '123456';//input('post.pwd');//密码
			$power = array('machine-addscore_list','machine-unbind','group-create');//input('post.power')?input('post.power'):array();//权限
			if(!$user_name || !$phone || !$pwd){
				$error = array(
					'status'=>0,
					'msg'=>'参数不全'
				);
				return json($error);
			}else{
				$data = array(
					'user_name'=>$user_name,
					'password'=>md5($pwd),
					'phone'=>$phone,
					'nav_list'=>implode(',',$power),
					'add_time'=>time(),
					'belong_id'=>$_SESSION['think']['client_id'],
					);
				$res = Db::name('admin')->add($data);
				if($res != false){
					$suc = array(
						'status'=>1,
						'msg'=>'新建成功',
						);
					return json($suc);
				}else{
					$error = array(
						'status'=>0,
						'msg'=>'新建失败'
					);
					return json($error);
				}
			}
		}else{
			//权限列表
			$p_power = DB::name('user_power')->field('id,name')->where(['pid'=>0])->select();
			$c_power = Db::name('user_power')->field('pid,name,path')->where('pid','neq',0)->select();
			foreach ($p_power as $k => $v) {
				foreach ($c_power as $kk => $vv) {
					if($vv['pid'] == $v['id']){
						$p_power[$k]['power'][]=$vv;
					}
				}
			}
			halt($p_power);
			$this->assign('power',$p_power);
			return $this->fetch();
		}
	}

	//人员列表
	public function staff_list(){
		$user_id = $_SESSION['think']['client_id'];
		$list  = Db::name('admin')->where(['belong_id'=>$user_id])->field('user_name,admin_id')->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	//人员编辑
	public function edit_staff(){
		$a = DB::name('machine')->select();
		halt($a);
	}

	//删除人员
	public function delete_staff(){

	}


}

?>