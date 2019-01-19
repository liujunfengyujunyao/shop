<?php

namespace app\phone\controller;
use think\Db;
use think\Image;
class Exchange extends Base{
        public function index(){
            $admin_id = $_SESSION['think']['client_id'];

            $machine_ids = DB::name('machine')->where(['client_id'=>$admin_id])->getField('sn',true);

            $machine_ids = implode(',',$machine_ids);

            $partner_pay_log = DB::name('partner_pay_log')->where("machinesn in ($machine_ids) && status = 1")->order("id desc")->select();

            foreach($partner_pay_log as $key => &$value){
                $value['time'] = date("Y-m-d : H:i:s",$value['time']);
                $value['rmb'] = floatval($value['amount'] / $value['rate']);
            }



            $this->assign('data',$partner_pay_log);

            return $this->fetch();

        }
}