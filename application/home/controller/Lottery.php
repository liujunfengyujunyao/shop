<?php

namespace app\home\controller; 
use think\Controller;
use think\Db;
class Lottery extends Controller{

	public function index(){
		//判断二维码是否有效，未激活，已激活，已使用
		$secret = input('get.device_secret');
		$key = Db::name('client_luck_key')->where(['device_secret'=>$secret])->field('status,client_id')->select();
		if($key['status'] != 1){
			return '该二维码不可使用';
		}else{
			$res = $this->choujiang($client_id);
		}
	}


	public function choujiang($id=12)	{
		//根据client_id查出对应奖品设置$prize_arr
		// $prize_arr = array(  
		//     0=>array( 'id'=>1,'v'=>1 ), //概率为1/200
		//     1=>array( 'id'=>2,'v'=>5 ),  
		//     2=>array( 'id'=>3,'v'=>10 ),
		//     3=>array( 'id'=>4,'v'=>24 ),
		//     4=>array( 'id'=>5,'v'=>60 ),
		//     5=>array( 'id'=>6,'v'=>100 )	 
	 	//    );
		$prize_arr = Db::name('client_luck_conf')->where(['client_id'=>$id])->field('id,goods_name,odds')->select();
	    $item = array();
		foreach ($prize_arr as $k => $v) {
			$item[$v['id']] = $v['odds'];
		}
		$goods_id = $this->get_rand($item);
		foreach ($prize_arr as $k => $v) {
			if($v['id'] == $goods_id){
				$goods_name = $v['goods_name'];
			}
		}
		return json(['msg'=>'恭喜您获得'.$goods_name,'status'=>1]);
	}


	public function get_rand($item){

	    $num = array_sum($item);//计算出分母200

	    foreach( $item as $k => $v ){
	     
		    $rand = mt_rand(1, $num);//概率区间(整数) 包括1和200

		    if( $rand <= $v ){
		        //循环遍历,当下标$k = 1的时候，只有$rand = 1 才能中奖 
		        $result = $k;           
		        //echo $rand.'--'.$v;
		        break;
		    }else{
		        //当下标$k=6的时候，如果$rand>100 必须$rand < = 100 才能中奖 ，那么前面5次循环之后$rand的概率区间= 200-1-5-10-24-60 （1,100） 必中1块钱
		        $num-=$v;
		        //echo '*'.$rand.'*'."&ensp;"."&ensp;"."&ensp;";
		    }
	    }	 
	    return $result;
 	}


}


?>