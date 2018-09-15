<?php
/**
 * 后台设备模块
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
   		$Page = new Page($count, $pagesize);
      $show = $Page->show();
   		$list = DB::name('Machine_stock_log')
   				->alias('l')
          ->field('l.*, g.goods_name, l.machine_id,m.machine_name,g.goods_sn')
          ->join('__MACHINE__ m','m.machine_id = l.machine_id','LEFT')
          ->join('__GOODS__ g','g.goods_id = l.goods_id','LEFT')
          ->where($where)
          ->order('l.ctime')
          ->limit($Page->firstRow . ',' . $Page->listRows)
          ->select();



      $content['page'] = $show;
      $content['pager'] = $Page;
      $content['list'] = $list;
      return $content;
   	}


    /**
     * 设备入库货物统计
     * @param  array   $where   where条件
     * @param  integer $pagesize 每页条数（默认为20）
     * @return array 出入库货物统计列表
     */

    public function goodsReport($where, $pagesize=20) {
      $count = DB::name('delivery')
          ->alias('d')
          ->join('__DELIVERY_GOODS__ dg','d.id = dg.delivery_id','LEFT')
          ->join('__MACHINE__ m','m.partner_id = d.partner_id','LEFT')
          ->join($where)
          ->group('d.partner_id, dg.goods_id')
          ->count();
      $Page = new Page($count,$pagesize);
      $show = $Page->show();

      $list = DB::name('delivery')
          ->alias('d')
          ->field('m.machine_name, g.goods_name, g.goods_sn, sum(dg.goods_num) as stock, d.edituser')
          ->join('__DELIVERY_GOODS__ dg','d.id = dg.delivery_id','LEFT')
          ->join('__GOODS__ g','g.goods_id = dg.goods_id','LEFT')
          ->join('__MACHINE__ m','partner_id = d.partner_id','LEFT')
          ->where($where)
          ->group('d.partner_id,dg.goods_id')
          ->limit($Page->firstRow . ',' . $Page->listRows)
          ->cache(true, 3600)
          ->select();

      $content['page'] = $show;
      $content['pager'] = $Page;
      $content['list'] = $list;
      return $content;
    }


}