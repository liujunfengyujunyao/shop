<?php

/**
 * 
 * ============================================================================
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * Author: Junfeng
 * Date: 2018/08/13
 */

namespace app\admin\controller;
use app\admin\logic\GoodsLogic;//商品逻辑
use app\admin\logic\OrderLogic;//订单逻辑
use think\AjaxPage;
use think\Page;
use think\Db;
use think\Loader;

class Machine extends Base
{

	public function index()
	{	
		// $partner_id = 104;
		// $storeList = DB::name('machine')
		// 			->alias('s')
		// 			->field("s.machine_id, s.machine_name, s.phone, FROM_UNIXTIME(s.addtime, '%Y-%m-%d') as addtime, u.nickname, st.type_name")
		// 			->join('__USERS__ u', 'u.user_id = s.user_id', 'LEFT')
		// 			->join('__MACHINE_TYPE__ st', 'st.id = s.type_id', 'LEFT')
		// 			->where(array('s.partner_id'=>$partner_id, 's.status'=>1))
		// 			->order('s.machine_id')
		// 			// ->limit($Page->firstRow . ',' . $Page->listRows)
		// 			->select();
		// halt($storeList);
		$this->assign('province', $this->getRegion(0, 1));
		
		return $this->fetch();
	}


	/**
     * 获取省、市、区列表
     * @param  int $pid 父级id
     * @param  int $level 地区等级  省,市,县,区
     * @return array      地区列表
     */
    public function getRegion($pid, $level)
    {
        $region = M('region')->where(array('parent_id' => $pid, 'level' => $level))->select();
        return $region;
    }


	 /**
     * 贩卖机列表
     * @author Junfeng
     * Date: 2018/08/13
     */

	public function ajaxMachineList(){
		$province_id = I('post.province_id');
		$city_id = I('post.city_id');
		$district_id = I('post.district_id');
		$key_word = I('post.key_word');
		$machine_where = array();

		if (!empty($province_id)) {
			$machine_where['m.province'] = $province_id;
		}
		if (!empty($city_id)) {
			$machine_wwhere['m.city'] = $city_id;
		}
		if (!empty($district_id)) {
			$machine_where['m.district'] = $district_id;
		}
		if (!empty($key_word)) {
			$machine_where['m.machine_name'] = array('like', "%key_word");
		}
		$machine_where['m.status'] = 1;

		$count = DB::name('machine')
				->alias('m')
				->join('__USERS__ u1','u1.user_id = m.user_id','LEFT')
				->where($machine_where)
				->count();
		// halt($count);
		$Page = new AjaxPage($count,10);
		$show = $Page->show();

		// $list = DB::name('machine')
		// 	->alias('m')
		// 	->field("m.*,mt.type_name")
		// 	->join('__USERS__ u1','u1.user_id = m.user_id','LEFT')
		// 	->join('__MACHINE_TYPE__ mt','mt.id = m.type_id','LEFT')
		// 	->where($machine_where)
		// 	->order('m.machine_id')
		// 	->limit($Page->firstRow . ',' . $Page->listRows)
		// 	->select();
		// $list = DB::name('machine')
		// ->alias('m')
		// // ->field("m.*,mt.*,")
		// ->join("__MACHINE_TYPE__ mt",'mt.id = m.type_id','LEFT')
		// // ->join("__USERS__ u1",'u1.user_id = m.machine_admin','LEFT')
		// // ->join("__USERS__ u2",'u2.user_id = m.machine_staff','LEFT')
		// ->where($machine_where)
		// ->select();
		// foreach ($list as $key => &$value) {
		// 	$value['phone'] = DB::name('users')->where(['user_id'=>$value['partner_id']])->getField("mobile");
		// 	//电话号码为机台管理员的号码
		// 	// $value['machine_admin'] = DB::name('users')->where(['user_id'=>$value['machine_admin']])->getField('nickname');
		// 	$value['partner_id'] = DB::name("users")->where(['user_id'=>$value['partner_id']])->getField('nickname');

		// }
		// halt($list);

		$list = DB::name('machine')
				->alias('m')
				->field("m.*, mt.type_name,u.nickname,u.mobile")
				->join('__PARTNER__ p','p.partner_id = m.partner_id', 'LEFT')
				->join('__MACHINE_TYPE__ mt','mt.id = m.type_id', 'LEFT')
				->join('__USERS__ u','u.user_id = p.user_id','LEFT')
				->where($machine_where)
				->order('m.machine_id')
				->limit($Page->firstRow . ',' . $Page->listRows)
				->select();
		

		$this->assign('list',$list);
		$this->assign('page',$show); //赋值 分页输出
		$this->assign('pager',$Page);

		return $this->fetch();
	}



	public function addEditMachine(){
		$type_arr = Db::name("machine_type")->getField('id,type_name'); //所有贩卖机类型

		//获取所有配货员
		$partner_arr = DB::name('partner')
				->alias('p')
				->field('p.partner_id,u.nickname')
				->join('__USERS__ u','u.user_id = p.user_id','LEFT')
				->where("p.status=1")
				->order('p.partner_id')
				->select();
		


		if(IS_GET) {
			$act = I('get.act');
			if ($act == '_EDIT_') {
				$id = I('get.id');
				
				// $info = DB::name('machine')
				// 	->alias('m')
				// 	->field('m.*, u.nickname, u.province, u.city, u.district')
				// 	->join('__USERS__ u','u.user_id = m.user_id', 'LEFT')
				// 	->where(array('machine_id' => $id))
				// 	->find();
				$info = DB::name('machine')
					->where(['machine_id'=>$id])
					->find();
				//机台管理员
				// $machine_admin = DB::name('users')
				// 	->field("user_id,nickname")
				// 	->where(['level'=>9])
				// 	->select();
				$this->assign('partner_arr',$partner_arr);//配货人员
				// $this->assign('machine_admin',$machine_admin);//机台管理员	


			    $this->assign('province', $this->getRegion(0, 1)); 
                $this->assign('city', $this->getRegion($info['province_id'], 2));
                $this->assign('district', $this->getRegion($info['city_id'], 3));
                $this->assign('info', $info);
                $this->assign('type_arr', $type_arr);
                // $this->assign('partner_arr', $partner_arr);
                $this->assign('act', '_EDIT_');
					
			}

			if ($act == '_ADD_') {

				// $machine_admin = DB::name("users")
				//      ->field('user_id,nickname')
				//      ->where(['level'=>9])
				//      ->select();
				     // halt($machine_admin);
				$this->assign('partner_arr',$partner_arr);
				// $this->assign('machine_admin',$machine_admin);
				$this->assign('province',$this->getRegion(0, 1));
				$this->assign('type_arr',$type_arr);
				$this->assign('act','_ADD_');
			}


			return $this->fetch('_addEditMachine');
		}

		$act = I('post.act');
		$data = I('post.');
		if ($act == '_ADD_') {
			// $user = array(
			// 	'nickname' => $data['nickname'],
			// 	'password' => encrypt($data['password']),
			// 	'mobile' => $data['mobile'],//负责人手机号码
			// 	'mobile_validated' => 1,   //手机号已验证
			// 	'is_distribut' => 1, //是否为分销商
			// 	'reg_time' => time(),
			// 	'level' => 9,
			// 	'province' => $data['province'],
			// 	'city' => $data['city'],
			// 	'district' => $data['district'],


			// 	);
			// $user_count = DB::name("users")->where(array('mobile'=>$data['mobile']))->count();
			// if ($user_count == 0) {
			// 	//不存在于用户表中
			// 	$user_id = DB::name('users')->add($user);
				
			// }

			$info = array(
				'machine_name' => $data['machine_name'],
				'type_id' => intval($data['type_id']),
				'province_id' => $data['province'],
				'city_id' => $data['city'],
				'district_id' => $data['district'],
				// 'machine_admin' => $data['machine_admin'],//机台管理员
				'partner_id' => $data['partner_id'],//机台配货人员
				'addtime' => time(),
				);//贩卖机信息
			
			//根据类型将初始配置写入贩卖机库存
			$machine_conf = M('machine_type_conf')->field('goods_id,goods_num')->where('type_id',$info['type_id'])->select();
			// if (empty($machine_conf)) {
			
			// 	$this->error('尚未配置此类型');
			// }
			$r = DB::name('machine')->add($info);
			// $sql = "INSERT IGNORE INTO __PREFIX__machine_stock (`machine_id`,`goods_id`,`stock_num`) VALUES ";
			// //将记录存入库存表中
			// foreach ($machine_conf as $conf) {
			// 	$values[] = "(" . $r . ',' . $conf['goods_id'] . "," . $conf['goods_num'] . ")";
			// }

			// $value = implode(',', $values);
			// $sql_query = $sql . $value;
			
			// $res = DB::query($sql_query);
			
			// if ($res!==fasle) {
			// 	$this->success("操作成功", U('Admin/Machine/index'));
			// }else{
			// 	$this->error("链接服务器失败");
			// }
			// 
			if ($r) {
				$this->success("操作成功", U('Admin/Machine/index'));
			}else{
				$this->error("链接服务器失败");
			}
		}


		if ($act == '_EDIT_') {
			$info = array(
				'province_id' => $data['province'],
				'city_id' => $data['city'],
				'district_id' => $data['district'],
				'machine_name' => $data['machine_name'],
				'type_id' => $data['type_id'],
				'machine_admin' => $data['machine_admin'],
				'partner_id' => $data['partner_id'],
				);
			$r = DB::name('machine')->where(['machine_id'=>$data['machine_id']])->save($info);
			if ($r !== fasle) {
				$this->success("操作成功", U('Admin/Machine/index'));
			} else {
				$this->error('连接服务器失败');
			}
		}


		if ($act == '_DEL_') {
			$id = I('post.id');

			if (M('machine_stock')->where(['machine_id'=>$id])->sum('goods_num') > 0) {
				$this->ajaxReturn(['status' => 2, 'msg' => '此贩卖机下还有库存,无法删除!']);
			} else {
				$r = DB::name('machine')->where(['machine_id'=>$id])->setField('status', 0);//将status改为0 已删除
				if($r){
					$this->ajaxReturn(['status' => 1,'msg' => '操作成功!']);
				}
				if ($r) {
					$this->success("操作成功", U('Admin/Machine/index'));
				} else {
					$this->error("操作失败", U('Admin/Machine/index'));
				}

			}
		}


	}

	/**
     * 贩卖机种类
     * @author Junfeng
     * Date: 2018/08/14
     */

	public function typeList(){
		$list = DB::name("machine_type")->select();
		// $list = DB::name("store_type")->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	/**
     * 贩卖机分类添加/修改
     * @author Junfeng
     * Date: 2018/08/14
     */
	public function addEditType(){
		$machine_type_db = M('machine_type');

		if(IS_GET){//action
			$act = I('get.act');
			if ($act == '_EDIT_') {
				$id = I("get.id");
				$info = $machine_type_db->where("id = $id")->find();
				
				$this->assign('info',$info);
				$this->assign('act','_EDIT_');
			}

			if ($act == '_ADD_') {
				$this->assign('act', '_ADD_');
			}
			return $this->fetch('_addEditType');
		}

		$data['type_name'] = I('get.type_name','','htmlspecialchars');//种类名称
		// $data['count_value'] = I('get.count_value','','htmlspecialchars');//总价值
		$data['brief'] = I('get.brief','','htmlspecialchars');//备注
		$data['goods_count'] = I('get.goods_count','','htmlspecialchars');//固定存储种类数量
		$data['addtime'] = time();

		$act = I('post.act');

		if ($act == '_ADD_') {
				// halt(I('post.'));
			$post = I('post.');
			$allSelect = implode(',',$post['allSelect']);
			$data['goods_count'] = count($post['allSelect']);
			$data['count_value'] = $post['count_value'];
			$data['location'] = $allSelect;
			$data['goods_num'] = $post['goods_num'];
			// halt($data);
			$r = $machine_type_db->add($data);
			if ($r !== false) {
				$this->success("添加成功",U('Admin/Machine/typeList'));
			} else {
				$this->error("连接服务器失败");
			}
		}

		if ($act == '_EDIT_') {
			$id = I('post.id');
			$post = I("post.");
			$data['type_name'] = I('post.type_name');//种类名称
			$data['count_value'] = I('post.count_value');//总价值
			// $data['goods_count'] = I('post.goods_count');//固定存储种类数量
			$allSelect = implode(',',$post['allSelect']);
			$data['goods_count'] = count($post['allSelect']);//固定存储种类数量
			$data['goods_value'] = I('post.goods_value');
			$data['location'] = $allSelect;
			$r = $machine_type_db->where("id = $id")->save($data);
			if ($r !== false) {
				$this->success("操作成功",U('Admin/Machine/typeList'));
			} else {
				$this->error("连接服务器失败");
			}
		}

		//ajax删除
		if ($act == "_DEL_") {
			$id = I('post.id');
			$r = $machine_type_db->where("id = $id")->delete();
			if ($r) {
				$this->ajaxReturn(1);
			} else {
				$this->ajaxReturn("error");
			}
		}
	} 


	

	/* 
	贩卖机类型配货
	@author Junfeng
	Date 2018-8-15
	*/

	// public function delivery()
	// {
	// 	$type_id = I('get.id/d');
	// 	if (IS_POST) {
	// 		if ($type_id) {
	// 			$data = I('post.');
	// 			$r = DB::name("machine_type_conf")->where(array('type_id' => $type_id))->find();

	// 			if ($r !== NULL) {
	// 				DB::name("machine_type_conf")->where(array('type_id' => $type_id))->delete();
	// 			}
	// 			foreach ($data['goods'] as $key => $value) {
	// 				$goods['goods_id'] = $value['goods_id'];
	// 				$goods['goods_num'] = $value['number'];
	// 				$goods['price'] = DB::name('goods')->where(array('goods_id' => $value['goods_id']))->getField('shop_price');
	// 				$goods['type_id'] = $type_id;
	// 				$goods['admin_id'] = $_SESSION['admin_id'];
	// 				$goods['location'] = $value['location'];
	// 				$goods['addtime'] = time();
	// 				$goods['edittime'] = time();

	// 				DB::name('machine_type_conf')->add($goods);
	// 			}

	// 			DB::name('machine_type')->where(array('id' => $type_id))->save(array('goods_value' => $data['goods_value']));
	// 			$this->ajaxReturn(array('status' => 1, 'msg' => '操作成功'));

	// 		} else {
	// 			$this->ajaxReturn(array('status' => 0, 'msg' => '操作失败'));
	// 		}
	// 	} else {
	// 			//读取商品配置清单
	// 			$info = DB::name('machine_type_conf')
	// 					->alias('mtc')
	// 					->field('mtc.goods_id, mtc.goods_num, g.shop_price,mtc.location')
	// 					->join('__GOODS__ g', 'g.goods_id = mtc.goods_id')
	// 					->where(array('type_id'=>$type_id))
	// 					->select();

	// 			$list = DB::name('goods')
	// 				->field('goods_id,goods_name,shop_price')
	// 				->where(array('is_on_sale' => 1))//是否上架
	// 				->select();
	// 			$count_value = DB::name('machine_type')->where(array('id' => $type_id))->getField('count_value');//查询设置的最大限额

	// 			//对应位置
	// 			$location = DB::name('machine_type')
	// 				->where(['id'=>$type_id])
	// 				->getField('location');
	// 			// $location['location'] = explode(',',$location['location']);
	// 			$location = explode(',',$location);

	
	// 			$this->assign('location',$location);
	// 			$this->assign('list',$list);
	// 			$this->assign('info',$info);
	// 			$this->assign('count_value',$count_value);
	// 			$this->assign('type_id',$type_id);
	// 			$this->assign('count',count($list));
	// 			return $this->fetch();

	// 	}
	// }

	/*
	库存管理
	@author Junfeng
	Date 2018-8-18
	*/
	public function stockList()
	{
		$data = I('post.');
		$where = array();

		if (!empty($data['province_id'])) {
			$where['m.province_id'] = $data['province_id'];
		}
		if (!empty($data['city_id'])) {
			$where['m.city_id'] = $data['city_id'];
		}
		if (!empty($data['district_id'])) {
			$where['m.district_id'] = $data['district_id'];
		}
		if (!empty($data['machine_name'])) {
			$where['m.machine_name'] = array('like', '%' . $data['machine_name'] . '%');
		}
		$where['m.status'] = 1;//未被删除
		// $count = DB::name('machine')
		// 		->alias('m')
		// 		->where($where)
		// 		->join('__USERS__ u', 'u.user_id = m.machine_admin', 'LEFT')//user_id为负责人
		// 		->count();

		$count = DB::name('machine')
				->alias('m')
				->where($where)
				->join('__PARTNER__ p','p.partner_id = m.partner_id','LEFT')
				->count();


		$Page = new Page($count, 10);
		$show = $Page->show();

		$list = DB::name('machine')
				->alias('m')
				->field('m.machine_id, m.machine_name, m.province_id, m.city_id, m.district_id, u.mobile')
				->join('__USERS__ u', 'u.user_id = m.partner_id','LEFT')//配货员的电话
				->where($where)
				->limit($Page->firstRow . ',' .$Page->listRows)
				->select();

		foreach ($list as $key => &$value) {
			$province = DB::name('region')->where(array('id' => $value['province_id']))->getField('name');
			$city = DB::name('region')->where(array('id' => $value['city_id']))->getField('name');
			$district = DB::name('region')->where(array('id' => $value['district_id']))->getField('name');
			$value['address'] = $province . $city . $district;//收货地址
			$goods = DB::name('machine_stock')
				->alias('m_s')
				->field("m_s.* , g.goods_name, FROM_UNIXTIME(m_s.edittime, '%Y-%m-%d %H:%i:%s') as edittime")
				->join("__GOODS__ g", 'g.goods_id = m_s.goods_id' , 'LEFT')
				->where(array('m_s.machine_id' => $value['machine_id']))
				->order('m_s.edittime desc, m_s.stock_id, m_s.goods_id desc')
				->select();
				$value['goods'] = $goods;
		}
		// halt($list);
		$this->assign('page', $show);
		$this->assign('pager', $Page);
		$this->assign('list', $list);
		$this->assign('province', $this->getRegion(0, 1));
		// $this->assign('s')库存预警............................
		return $this->fetch();

	}


	/*
	库存管理
	@author Junfeng
	Date 2018-8-20
	*/

	public function stocklog()
	{
		$data = I('post.');
		$where = array();
		//筛选条件
		if (!empty($data['ctime'])) {
			$create_time2 = explode(',', $data['ctime']);
			$where['l.ctime'] = array(array('egt', strtotime($create_time2[0])), array('elt', strtotime($create_time2[1])), 'AND');
			$this->assign('strart_time', $create_time2[0]);
			$this->assign('end_time', $create_time2[1]);
		}
		if(!empty($data['mobile'])) {
			$where['s.phone'] = array('like', '%' . $data['mobile'] . '%');
		}

		$content = D('machine')->getStockLog($where);
		return $this->fetch();
	}

	public function optionMachine()
	{
		if (IS_POST) {
		
		$data = I('post.');
		
		//获取版本下载地址
		$dladdr = DB::name('version')
				->where(['id'=>$data['version']])
				->getField('dladdr');
		$save = array(
			'odds' => $data['odds'],
			'version_id' => $data['version'],
			);
		//修改游戏的版本编号
		$r = DB::name('machine')
				->where(['machine_id'=>$data['machine_id']])
				// ->save(['version_id'=>intval($data['version'])]);
				->save($save);
			if ($r !== false) {
				$this->success("操作成功",U('Admin/Machine/index'));
			} else {
				$this->error("连接服务器失败");
			}
		} else {


		//显示机器名称,当前游戏版本,赔率	
		$id = I("get.id");

		// $type_id = DB::name('machine')->where(['machine_id'=>$id])->getField('type_id');
		// $r = M('Goods')
		// 	->alias('g')
		// 	->join('__MACHINE_TYPE_CONF__ m',"g.goods_id = m.goods_id",'LEFT')
		// 	->where(['m.type_id'=>$type_id])
		// 	->select();

		// halt($r);
		$data = DB::name('machine')
				->alias('m')
				->where(['machine_id'=>$id])
				->join('__VERSION__ v','m.version_id = v.id','LEFT')
				->find();
		$version = DB::name('version')
				->field("id,version,brief")
				->select();
		$odds = DB::name('machine')
				->where(['machine_id'=>$id])
				->find();
				// halt($data);
		
		//查询这个贩卖机下的商品分类
	    $GoodsLogic = new GoodsLogic();        
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        $this->assign('categoryList',$categoryList);
        $this->assign('brandList',$brandList);



		$this->assign('version',$version);
		$this->assign('data',$data);
		return $this->fetch();
		}
		

	}

	// public function ajaxGoodsList()
	// {

	// 	$key_word = I('post.key_word');
	// 	$goods_where = array();
	// 	$machine_id = I('post.machine_id');
	// 	$type_id = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('type_id');

	// 	if (!empty($key_word)) {
	// 		$goods_where['g.goods_name'] = array('lick' , "%key_word");
	// 	}
	// 	$count = DB::name('goods')
	// 			->alias('g')
	// 			->join('__MACHINE_TYPE_CONF__ m','g.goods_id = m.goods_id','LEFT')
	// 			->where($goods_where)
	// 			->where(['m.type_id'=>$type_id])
	// 			->count();
	// 	$Page = new AjaxPage($count,10);
	// 	$show = $Page->show();

	// 	$goodsList = DB::name('goods')
	// 			->alias('g')
	// 			// ->field("g.goods_name,g.goods_sn,goods_remark,g.original_img,g.shop_price")
	// 			->field("g.*")
	// 			->join("__MACHINE_TYPE_CONF__ m",'g.goods_id = m.goods_id','LEFT')
	// 			->where(['m.type_id'=>$type_id])
	// 			->where($where)
	// 			->limit($Page->firstRow.','.$Page->listRows)
	// 			->select();
	// 			// halt($goodsList);
	// 	$catList = D('goods_category')->select();
	// 	$catList = convert_arr_key($catList, 'id');
		
	// 	$this->assign('catList',$catList);
 //        $this->assign('goodsList',$goodsList);
 //        $this->assign('page',$show);// 赋值分页输出
	// 	return $this->fetch();


	// }
	 public function ajaxGoodsList(){            
        $machine_id = I('post.machine_id');
        $type_id = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('type_id');

        // halt($type_id);
        $where = ' 1 = 1 '; // 搜索条件                
        I('intro')    && $where = "$where and ".I('intro')." = 1" ;        
        I('brand_id') && $where = "$where and brand_id = ".I('brand_id') ;
        (I('is_on_sale') !== '') && $where = "$where and is_on_sale = ".I('is_on_sale') ;                
        $cat_id = I('cat_id');
        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
        
        $count = M('Goods')->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        
        // $goodsList = M('Goods')
        // 		->alias('g')
        // 		->field("g.goods_id,g.goods_name,g.goods_sn,g.cat_id,g.shop_price,s.goods_num,m.location")
        // 		->join('__MACHINE_TYPE_CONF__ m','g.goods_id = m.goods_id','LEFT')
        // 		->join('__MACHINE_STOCK__ s',"s.goods_id = m.goods_id",'LEFT')
        //         ->where($where)
        //         ->where(['m.type_id'=>$type_id])
        //         ->where(['s.machine_id'=>$machine_id])
        //         // ->order($order_str)
        //         ->limit($Page->firstRow.','.$Page->listRows)
        //         ->select();

 		// $goodsList = M('Goods')
 		// 		->alias('g')
 		// 		->field("g.goods_id,g.goods_name,g.goods_sn,g.cat_id,g.shop_price,s.goods_num,m.location")
 		// 		->join('__MACHINE_CONF__ m','g.goods_id = m.goods_id','LEFT')
 		// 		->join('__MACHINE_STOCK__ s','s.goods_id = m.goods_id','LEFT')
 		// 		->where($where)
 		// 		->where(['m.machine_id'=>$machine_id])
 		// 		// ->where(['s.machine_id'=>$machine_id])
 		// 		->limit($Page->firstRow.','.$Page->listRows)
   //              ->select();
        $goodsList = M('Machine_stock')
 				->alias('s')
 				->field("g.goods_id,g.goods_name,g.goods_sn,g.cat_id,g.shop_price,s.goods_num,s.machine_id,m.location")
 				->join('__GOODS__ g','s.goods_id = g.goods_id','LEFT')
 				->join('__MACHINE_CONF__ m','s.goods_id = m.goods_id','LEFT')
 				->where($where)
 				->where(['s.machine_id'=>$machine_id,'m.machine_id'=>$machine_id])
 				// ->where(['s.machine_id'=>$machine_id])
 				->limit($Page->firstRow.','.$Page->listRows)
                ->select();

        $catList = DB::name('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');

        //本机库存
       	// $store = DB::name('machine_stock')
       	// 		->where(['machine_id'=>$machine_id])
       	// 		->select();
       	// 		halt($store);

        $this->assign('catList',$catList);
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }



    public function delivery()
    {
    	$machine_id = I('get.id/d');

    	if (IS_POST) {
    		if ($machine_id) {

    			$data = I('post.');
    			
    			$r = DB::name('machine_conf')->where(['machine_id' => $machine_id])->find();
    			if ($r !== NULL) {
    				DB::name("machine_conf")->where(['machine_id' => $machine_id])->delete();
    			}

    			foreach ($data['goods'] as $key => $value) {
    				$goods['goods_id'] = $value['goods_id'];
    				$goods['goods_num'] = $value['number'];
    				$goods['price'] = DB::name('goods')->where(['goods_id'=>$value['goods_id']])->getField('shop_price');
    				$goods['machine_id'] = $machine_id;
    				$goods['admin_id'] = $_SESSION['admin_id'];
    				$goods['location'] = $value['location'];
    				$goods['addtime'] = time();
    				$goods['edittime'] = time();

    				DB::name('machine_conf')->add($goods);
    			}

    		$machine_conf = M('machine_conf')->field('goods_id,goods_num')->where('machine_id',$machine_id)->select();
    		$sql = "INSERT IGNORE INTO __PREFIX__machine_stock (`machine_id`,`goods_id`,`stock_num`) VALUES ";
			//将记录存入库存表中
			foreach ($machine_conf as $conf) {
				$values[] = "(" . $machine_id . ',' . $conf['goods_id'] . "," . $conf['goods_num'] . ")";
			}

			$value = implode(',', $values);
			$sql_query = $sql . $value;
			
			$res = DB::query($sql_query);

    		
    			$this->ajaxReturn(array('status' => 1,'msg' => '操作成功'));
    		} else {
    			$this->ajaxReturn(array('status' => 0,'msg' => '操作失败'));
    		}

    	} else {
    		//读取商品配置清单
    	

    		$type_id = DB::name('machine')
    			->where(['machine_id' => $machine_id])
    			->getField('type_id');
    		

    		$info = DB::name('machine_conf')
    			->alias('mc')
    			->field('mc.goods_id, mc.goods_num, g.shop_price, mc.location')
    			->join('__GOODS__ g', 'g.goods_id = mc.goods_id')
    			->where(['mc.machine_id'=>$machine_id])
    			->select();


    		$list = DB::name('goods')
    			->field('goods_id,goods_name,shop_price')
    			->where(['is_on_sale' => 1])//是否上架
    			->select();

    		$location = DB::name('machine_type')
    			->where(['id' => $type_id])
    			->getField('location');
    		
    		$location = explode(',',$location);
    		
    		$count_value = DB::name('machine_type')->where(['id' => $type_id])->getField('count_value');

    		$goods_num = DB::name('machine_type')->where(['id'=>$type_id])->getField("goods_num");
    		
    		$this->assign('goods_num',$goods_num);
    		$this->assign('info',$info);
    		$this->assign('machine_id',$machine_id);
    		$this->assign('count_value',$count_value);
    		$this->assign('location',$location);
    		$this->assign('list',$list);
    		$this->assign('count',count($list));
    		return $this->fetch();
    	}
    }
}