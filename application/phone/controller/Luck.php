<?php
namespace app\phone\controller;
use think\Image;
use think\Controller;
use think\Request; 
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Luck extends Controller
{

    // 文件上传表单
    public function index()
    {
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

    public function index1(){
        return $this->fetch();

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
