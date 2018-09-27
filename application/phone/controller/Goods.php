<?php
namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use Think\Db;

class Goods extends MobileBase{

	//配置商品(位置,名称,价格)
	public function index(){
		// $info = session('info');
		// $machine = DB::name('machine')->where(['client_id'=>$info['client_id']])->select();
		  	$number = DB::name('machine')
    			->alias('m')
    			->join("__MACHINE_TYPE__ t","m.type_id = t.id",'LEFT')
    			->count("t.location");
    		$data = array(
    			array(1,2,3),
    			array(1,2,3),
    			array(1,2,3),

    			);
    		halt(count($data));
    		
		return $this->fetch();
	}

	//礼品库存
	public function stock_index(){
		$id = 10;
		$count = DB::name('machine')->where(['client_id'=>$id])->count();
		
		$client_id = 10;
		$machine = DB::name('machine')
				->field("machine_name,province_id,city_id,machine_id,district_id")
		        ->where(['client_id'=>$client_id])
		        ->select();
		if ($machine) {
			//查询名下设备
			foreach ($machine as $key => &$value) {
			$province = DB::name('region')->where(['id'=>$value['province_id']])->getField('name');
			$city = DB::name('region')->where(['id'=>$value['city_id']])->getField('name');
			$district = DB::name('region')->where(['id'=>$value['district_id']])->getField('name');
			// $value['address'] = $province . $city . $district;
			$value['address'] = $province . $district;
			$goods = DB::name('client_machine_stock')
					->alias('ms')
					->field("ms.* , FROM_UNIXTIME(ms.edittime, '%Y-%m-%d %H:%i:%s') as edittime,mc.goods_price")
					->join("__CLIENT_MACHINE_CONF__ mc","mc.location = ms.location",'LEFT')
					->where(['ms.machine_id'=>$value['machine_id'],'mc.machine_id'=>$value['machine_id']])
					->select();
			$value['goods'] = $goods;
		}
		}

		$this->assign('machine',$machine);
		$this->assign('count',$count);
		return $this->fetch();
	}

	//礼品日志
	public function log(){
		return $this->fetch();
	}

	//补货
	public function stock_add(){
		$manager = session('manager_info');

		$stock = DB::name('client_machine_stock')->where(['machine_id'=>$machine_id])->select();

	}

	//一键补货
	public function ajax_add(){
		$machine_id = I('post.id');

		
		$data = M('client_machine_stock')->where(['machine_id'=>$machine_id])->find();
		if (is_null($data)) {
			$this->ajaxReturn(['status' => 2,'msg' => '未配置商品']);
		}
		$stock = DB::name('machine')
				->alias('m')
				->join("__MACHINE_TYPE__ t",'m.type_id = t.id','LEFT')
				->where(['m.machine_id'=>$machine_id])
				->getField('t.goods_num');
			
		$stock_id = DB::name('client_machine_stock')
				->alias('ms')
				->field('ms.goods_num,ms.machine_id,ms.location')
				->join("__CLIENT_MACHINE_CONF__ mc",'ms.location = mc.location','LEFT')
				->where(['ms.machine_id'=>$machine_id,'mc.machine_id'=>$machine_id,'mc.goods_price'=>array('gt',0)])
				->getField('ms.stock_id',true);
		$stock_id = implode(',',$stock_id);
		$r = DB::name('client_machine_stock')
				->where("stock_id in ({$stock_id})")
				->save(['goods_num'=>$stock,'edittime'=>time()]);
		if($r !== false){
			$this->ajaxReturn(['status' => 1,'msg' => '操作成功!']);
		}else{
			$this->ajaxReturn(['status' => 2,'msg' => '网络错误']);
		}
	}

	//一键清货
	public function ajax_clear(){
		
		$machine_id = I('post.id');
		$stock = DB::name('client_machine_stock')->where(['machine_id'=>$machine_id])->delete();
		$conf = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->delete();
		if ($stock && $conf !== false) {
			$this->ajaxReturn(['status' => 1,'msg' => '操作成功!']);
		}
		
	}
	

	public function ajax_list(){
		$machine_id = I('get.id');
		$data = DB::name('client_machine_stock')
				->where(['client_id'=>$machine_id])
				->select();
		$show = DB::name('client_machine_conf')
				->where(['client_id'=>$machine_id])
				->select();
	}

	//配置礼品(每个格子单独配置)
	public function goods_list(){

	}

	public function stock_log(){
		//补货,销货
		
	}
	
}