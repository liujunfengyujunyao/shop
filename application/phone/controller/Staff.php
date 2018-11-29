<?php

namespace app\phone\controller;
use think\Db;
header("content-type:text/html;charset=utf-8");

class Staff extends Base{

	//新建人员
	public function add_staff(){
		if(IS_POST){
			$user_name = trim(input('post.username'));//账户名称
			$phone = trim(input('post.phone'));//手机
			$pwd = trim(input('post.password'));
			$pwd1 = trim(input('post.password1'));//密码
			$power = input('post.power/a')?input('post.power/a'):array();//权限
			if(!$user_name || !$phone || !$pwd || !$pwd1){
				return $this->error('参数不全');
			}else{
				if($pwd == $pwd1){
					$id = Db::name('admin')->field('admin_id')->where(['phone'=>$phone])->getField('admin_id');
					if($id){
						return $this->error('该手机已被注册');
					}else{
						$data = array(
							'user_name'=>$user_name,
							'password'=>md5($pwd),
							'phone'=>$phone,
							'nav_list'=>implode(',',$power),
							'add_time'=>time(),
							'belong_id'=>$_SESSION['think']['client_id'],
							'machine_pwd'=>rand(1,9).substr(time(),2).rand(10,99),
							);
						$res = Db::name('admin')->add($data);
						if($res != false){
							return $this->success('新建成功,前往编辑权限',U('staff/edit_staff',array('user_id'=>$res)));
						}else{
							return $this->error('新建失败');
						}
					}
				}else{
					return $this->error('两次密码不匹配');
				}
			}
		}else{
			//权限列表
			// $p_power = DB::name('user_power')->field('id,name')->where(['pid'=>0])->select();
			$c_power = Db::name('user_power')->field('pid,name,path,id')->where('pid','neq',0)->select();
			// foreach ($p_power as $k => $v) {
			// 	foreach ($c_power as $kk => $vv) {
			// 		if($vv['pid'] == $v['id']){
			// 			$p_power[$k]['power'][]=$vv;
			// 		}
			// 	}
			// }
			$this->assign('power',$c_power);
			return $this->fetch();
		}
	}

	//人员列表
	public function staff_list(){
		$user_id = $_SESSION['think']['client_id'];
		$list  = Db::name('admin')->alias('a')->join('tfs_machine_group b','a.group_id=b.id','left')->where(['a.belong_id'=>$user_id])->field('a.user_name,a.admin_id,b.group_name')->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	//人员编辑
	public function edit_staff(){	
		$admin_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			$user_id = input('post.user_id');
			$username = input('post.username');//账户名称
			if(!$username){
				return $this->error('名称不可为空');
			}else{
			//$pwd = input('post.password');//密码
				$power = input('post.power/a')?input('post.power/a'):array();
				$str_power = implode(',',$power);
				$store = input('post.store/a')?input('post.store/a'):array();
				$str_store = implode(',',$store);
				$data = array(
					'user_name'=>$username,
					'nav_list'=>$str_power,
					'group_id'=>$str_store,
					);
				$res = Db::name('admin')->where(['admin_id'=>$user_id])->save($data);

				$password =  Db::name('admin')->where(['admin_id'=>$user_id])->getField('machine_pwd');
				$msg['msgtype'] = 'user_permissions';
				$msg['managers'] = array(
					'userid'=>$user_id,
					'permissions'=>$str_power,
					'password'=> $password
					);

				$machine_list = Db::name('machine')->where('group_id','in',$str_store)->field('machine_id')->select();
				foreach ($machine_list as $k => $v) {
					$commandid = $this->get_command('user_permissions',$v['machine_id']);
					$msg['commandid'] = intval($commandid);
					$this->post_to_server($msg,$v['machine_id']);
				}

				if($res !== false){
					return $this->success('修改成功',U('staff/staff_list'));
				}else{
					return $this->error('修改失败');
				}
			}
		}else{
			$user_id = input('get.user_id');
			$admin = Db::name('admin')->where(['admin_id'=>$user_id])->field('user_name,password,nav_list,group_id,admin_id')->find();
			$store = Db::name('machine_group')->where(['user_id'=>$admin_id])->field('group_name,id')->select();
			$power = Db::name('user_power')->field('pid,name,path,id')->where('pid','neq',0)->select();
			foreach ($power as $k => $v) { //权限path有多个的情况下，用第一个元素进行比对
				$a = explode(',',$v['path']);
				$power[$k]['check'] = $a[0];
			}
			$select_power = $admin['nav_list'];
			$this->assign('store',$store);
			$this->assign('power',$power);
			$this->assign('spower',$select_power);
			$this->assign('admin',$admin);
			return $this->fetch();
		}
	}

	//删除人员
	public function delete_staff(){
		$request=  \think\Request::instance();
		dump(explode(',',$_SESSION['think']['manager_info']['nav_list']));
		dump($request->action());
		$nav = CONTROLLER_NAME.'-'.ACTION_NAME;
		dump($nav);
		if(in_array(strtolower($nav),explode(',',$_SESSION['think']['manager_info']['nav_list']))){
			echo "1";
		}
	}


	//人员管理
	public function staff_manage(){
		return $this->fetch();
	}

	//新建角色
	public function add_role(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			$role_name = trim(input('post.name'));
			$power = input('post.power/a')?input('post.power/a'):array();
			if(!$role_name){
				return $this->error('角色名称不能为空');
			}else{
				$name = Db::name('admin_role')->where(['role_name'=>$role_name,'user_id'=>$user_id])->getField('role_id');
				if(!empty($name)){
					return $this->error('角色名称已存在');
				}
				$data = array(
					'role_name'=>$role_name,
					'act_list'=>implode(',',$power),
					'user_id'=>$user_id
					);
				$res = Db::name('admin_role')->add($data);
				if($res != false){
					return $this->success('角色新建成功',U('staff/staff_manage'));
				}else{
					return $this->error('角色新建失败');
				}
			}
		}else{
			$power = Db::name('user_power')->field('pid,name,path,id')->where('pid','neq',0)->select();
			$this->assign('power',$power);
			return $this->fetch();
		}
	}

	public function edit_role(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			$role_name = trim(input('post.name'));
			$role_id = input('post.id');
			$power = input('post.power/a')?input('post.power/a'):array();
			if(!$role_name || !$role_id){
				return $this->error('参数错误');
			}else{
				$name = Db::name('admin_role')->where(['role_name'=>$role_name,'user_id'=>$user_id])->getField('role_id');
				if(!empty($name)){
					return $this->error('角色名称已存在');
				}
				$data = array(
					'role_name'=>$role_name,
					'act_list'=>implode(',',$power),
					);
				$res = Db::name('admin_role')->where(['user_id'=>$user_id,'role_id'=>$role_id])->save($data);
				if($res !== false){
					return $this->success('编辑成功',U('staff/staff_manage'));
				}else{
					return $this->error('编辑失败');
				}
			}
		}else{
			$role = Db::name('admin_role')->where('user_id=0 or user_id=:id')->bind(['id'=>$user_id])->field('role_id,role_name,act_list')->select();
			$power = Db::name('user_power')->field('pid,name,path,id')->where('pid','neq',0)->select();
			$this->assign('power',$power);
			$this->assign('role',$role);
			return $this->fetch();
		}
	}

	//向服务器发送数据
	public function post_to_server($msg,$machine_id){
		$machinesn = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('sn');
		$data = array(
    		'msg'=>$msg,
    		'msgtype'=>'send_message',
    		'machinesn'=>intval($machinesn),
    		);
		$url = 'https://www.goldenbrother.cn:23232/account_server';
		//halt($data);
		$res = post_curls($url,$data);
		return $res;
	}

	//生成command
	public function get_command($msgtype,$machine_id,$content=''){
		$change = array(
            'msgtype' => $msgtype,
            'machine_id' => $machine_id,
            'send_time' => time(),
            'content'=>$content
            );
        $commandid = DB::name('command')->add($change);
        if($commandid > 0){
        	return $commandid;
        }else{
        	return ['command生成失败'];
        }
	}


}

?>