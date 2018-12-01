<?php
namespace app\phone\controller;
use think\Image;
use think\Controller;
use think\Request; 
use think\Db;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Luck extends Base
{

    // 文件上传表单
    public function index()
    {
        if(IS_POST){
            $data = input('post.conf/a');
            foreach ($data as $k => $v) {
                $file = request()->file($k);
                $conf = array(
                    'goods_name'=>$data[$k]['goods_name'],
                    'odds'=>$data[$k]['odds']
                    );
                //halt($file);
                if(!empty($file)){
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'upload'. DS . 'luck_goods');
                    $conf['img'] = DS . 'public' .DS . 'upload' . DS . 'luck_goods'. DS . $info->getSaveName();
                }
                Db::name('client_luck_conf')->where(['id'=>$k])->save($conf);
            }
            return $this->success('配置成功');
        }else{
            $keywords = input('get.keywords');
            $status = array('0'=>'未激活','1'=>'已激活','2'=>'已中奖','3'=>'未中奖');
            $admin_id = $_SESSION['think']['client_id'];
            if(!$keywords){
                $keys = Db::name('client_luck_key')->where(['client_id'=>$admin_id])->field('id,key_id,status')->select();
            }else{
                $keys = Db::name('client_luck_key')->where(['client_id'=>$admin_id,'key_id'=>$keywords])->field('id,key_id,status')->select();
            }
            foreach ($keys as $k => $v) {
                $keys[$k]['status_name'] = $status[$v['status']];
            }
            $win = Db::name('client_luck_key')->where(['status'=>2])->field('id,name,address,phone')->select();
            $key_conf = Db::name('client_luck_conf')->where(['client_id'=>$admin_id])->field('id,img,goods_name,odds')->select();
            $this->assign('win',$win);
            $this->assign('conf',$key_conf);
            $this->assign('keys',$keys);
            return $this->fetch();
        }
        
    }

    //激活二维码
    public function jihuo(){
        $id = input('post.id');
        if(!$id||!is_numeric($id)){
            return json(['status'=>0,'msg'=>'参数错误']);
        }
        $status = Db::name('client_luck_key')->where(['id'=>$id])->getField('status');

        if($status === 0){
            $res = Db::name('client_luck_key')->where(['id'=>$id])->setField('status',1);
            return json(['status'=>1,'msg'=>'激活成功']);
        }else{
            return json(['status'=>0,'msg'=>'激活失败']);
        }
    }

    public function yulan(){
        $id = input('get.id');
        if(!$id||!is_numeric($id)){
            return json(['status'=>0,'msg'=>'参数错误']);
        }else{
            $secret = Db::name('client_luck_key')->where(['id'=>$id])->getField('device_secret');
            if(!$secret){
                return json(['status'=>0,'msg'=>'secret错误']);
            }else{
                qrcode('http://www.12202.com.cn/tp/index.php/home/luck/login?device_secret='.$secret);
            }
        }

    }

    public function detail(){

        $id = input('get.id');
        
        return $this->fetch();

    }


    // 图片上传处理
    public function picture2()
    {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
       
        //校验器，判断图片格式是否正确
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $this->error('请选择图像文件');
        } else {
            
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
           
            if ($info) {
                // 成功上传后 获取上传信息

                //存入相对路径/upload/日期/文件名
                $data = DS . 'public' .DS . 'uploads' . DS . $info->getSaveName();
                // $data =DS.'public'. $data;
                // halt($data);
                //模板变量赋值
                $this->assign('image', $data);
                return $this->fetch('index');
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }


    public function picture1(){
        if (request()->isPost()) {
            $files = request()->file('image');
            $data1 = '';//
        // halt($files);
            foreach ($files as $file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $data1 .= $info->getSaveName().",";
                }else{
                    return $this->error($file->getError());
                }
            }
        }

            $data2 = substr($data1,0,strlen($data1)-1);
            halt($data2);

    }

        //OK
        public function doUpload(){
     
            $files = request()->file('image');
     
            $info="";
          
     foreach ($files as $key => $picFile) {
    
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $picFile->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'images');
                
     
                /*获取存储路径，以便插入数据库*/
               $path[$key]= $_SEVER['HTTP_HOST'] . "/uploads/images/".$info->getSaveName();

     
            }
        

        if($info!==""){
            halt($path);
                return $this->success('上传成功！');
                // 成功上传后 获取上传信息
                // 输出 jpg
                /* echo $info->getExtension();*/
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                /*echo $info->getFilename();*/
            }else{
                // 上传失败获取错误信息
                /* echo $file->getError();*/
     
     
                return $this->error('上传失败！');
            }


}       
        

        public function confList(){
            
        }
}
