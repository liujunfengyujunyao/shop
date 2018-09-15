<?php
// $data = [
//            'openid'=>'123789456',
//            'device_id' => 123456,
//            'image_url' => 'http://wx.qlogo.cn/mmopen/dE3m8ib67IaesiaBvbKp93TGWZoGuxG0uFvibcM147Tg785b5fN5NUrJKx0ibRqoSHmuxx1wicKZ5iaH2JV1bZULCPQr3OwVn72bd7/0'

//         ];
// // $data = json_encode($data);
// //调用测肤APP接口，发送openid
//         $ch = curl_init();            
//         curl_setopt($ch, CURLOPT_URL, 'http://54.64.191.2/EZM_Work/web_api/RegisterOpenId.php');         
//         curl_setopt($ch, CURLOPT_POST, true);      
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);      
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
//         $ret= curl_exec($ch);      
//         curl_close($ch); 
//         $ret = json_decode($ret); 
            
//        var_dump($ret->success);die;  
// $data = [
//   // 'loginid' => 'ornmWwhmZxcvLYcgIrhmMHJbU9_I'
//   'index'=>1
// ] ; 
//         $ch = curl_init();            
//         curl_setopt($ch, CURLOPT_URL,'http://54.64.191.2/EZM_Work/web_api/GetRecordDetail.php');         
//         curl_setopt($ch, CURLOPT_POST, true);      
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);      
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
//         $ret= curl_exec($ch);      
//         curl_close($ch); 
//         $ret = json_decode($ret); 
//         print_r($ret);die;
       //  $d="2017-01-02 04:05:22";
       // echo date('Y-m-d H:i:s',strtotime("$d +8 hour")); 
       // $data = 'ghjhkdas_hjhk_ksl12';
       // $data = substr($data,0,strrpos($data,'_'));
       // var_dump($data);die;
       // $group = D('group')->where(['id'=>$group_id])->find();
       // $goods_names = D('Goods')->where("id in ({$group['goods_ids']})")->getField('goods_name',true);
       // $group['goods_names'] = implode($goods_names,',');
       // $manager_names = D('manager')->where("id in ({$group['manager_ids']})")->getField('nickname',true);
       // $group['manager_names'] = implode($manager_names,',');
       // $this->assign('group',$group);
       // $n = md5('kakaphp'.md5('123456'));
       // echo($n);
       // $partner_arr = [1,2,3,4]
       // $goods_arr = [1,2,3,5]
       
       // $default_arr = array_merge(array_diff($partner_stock,$goods_arr),array_diff($goods_arr,$partner_stock));//发生改变的goods_id  原始排在前 新更排在后
       // $default_key = array_search($v['goods_id'], $default_arr);//取出$v['goods_id']在 双双对应数组的位置
       // $del_goods_key = $this->arithmetic($count,$default_key);
       // $del_goods_value = $default_arr[$del_goods_key];  


       // $partner = array(
       //        'goods_id' => [1,2,3,4];
       //        );
       // $machine = array(
       //        'goods_id' => [1,2,3,5];
       //        );
       // foreach ($partner as $key => $value) {
       //        foreach ($machine as $k => $v) {
       //               if($value['goods_id'] !== $v['goods_id']){
                        
       //               }
       //        }
       // }
       $stock_arr = [1,2,3,4];
       $goods_arr = [1,2,3,5];
       $partner_stock = array(
              'goods_id' => [1,2,3,4],
              );
       
       $machine_stock = array(
              'goods_id' => [1,2,3,5],
              );
       foreach ($partner_stock as $key => $value) {
              foreach ($machine_stock as $k => $v) {
                     if ($value['goods_id'] !== $v['goods_id']) {
                            $default_arr = array_merge($stock_arr,$goods_arr);
                            $count = count($default_arr);
                            $default_key = array_search($v['goods_id'], $default_arr);
                            $del_goods_key = $default_key-$count/2;
                            $del_goods_value = $default_arr[$del_goods_key];
                            var_dump($del_goods_value);die;
                     }
              }
       }
     
  
