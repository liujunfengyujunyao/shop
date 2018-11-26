<?php
namespace app\admin\controller;
use app\admin\logic\GoodsLogic;
use app\admin\logic\SearchWordLogic;
use think\AjaxPage;
use think\Loader;
use think\Page;
use think\Db;

class Client extends Base {
	public function index(){
		$this->assign('province', $this->getRegion(0, 1));
		
		return $this->fetch();
	}


	public function getRegion($pid, $level)
    {
        $region = M('region')->where(array('parent_id' => $pid, 'level' => $level))->select();
        return $region;
    }

    public function ajaxClientList(){
  //   	$province_id = I('post.province_id');
		// $city_id = I('post.city_id');
		// $district_id = I('post.district_id');
		$key_word = I('post.key_word');
		$machine_where = array();

		// if (!empty($province_id)) {0
		// 	$machine_where['m.province'] = $province_id;
		// }
		// if (!empty($city_id)) {
		// 	$machine_wwhere['m.city'] = $city_id;
		// }
		// if (!empty($district_id)) {
		// 	$machine_where['m.district'] = $district_id;
		// }
		if (!empty($key_word)) {
			$machine_where['a.user_name'] = array('like', "%key_word");
		}
		$machine_where="a.admin_id != 1";
		// $machine_where['status'] = 1;

		$list = DB::name('admin')
				->alias('a')
				->field('a.user_name,b.user_name as staff_name,a.phone,a.admin_id')
				->join('__ADMIN__ b','a.belong_id = b.admin_id','LEFT')
				->where($machine_where)
				->select();
				// halt($list);
		$Page = new AjaxPage(count($list),10);
		$show = $Page->show();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('pager',$Page);
		return $this->fetch();
    }


    //权限列表
    public function role(){
    	$role = DB::name('role')->select();

    	return $this->fetch();
    }
}