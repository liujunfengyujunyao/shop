<?php
/**
 * 后台工厂店模块
 * Author: Junfeng
 * Date: 2018/08/20
 */


namespace app\admin\model;

use think\Model;
use think\Db;
use think\Page;
use think\AjaxPage;

class Machine extends Model{
	   /**
     * 获取贩卖机出入库记录
     * @param  array $where where条件
     * @param  int $pagesize 每页条数（默认为20）
     * @return array 库存日志列表
     */
    
   	public function getStockLog($where,$pagesize=10) {
   		$count = DB::name('Machine_stock_log')
   				->alias('l')
   				->join('__MACHINE__ m','m.machine_id = l.machine_id','LEFT')
   				->where($where)
   				->count();
   		$Page = new Page->show();

   		$list = DB::name('Machine_stock_log')
   				->alias('l')
   				
   	}
}