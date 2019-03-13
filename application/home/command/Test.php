<?php
/**
 * Created by PhpStorm.
 * User: GoldenBrother
 * Date: 2019/3/5
 * Time: 11:38
 */

namespace app\home\command;
use think\console\Command;
//use phpQuery;
use think\console\Input;
use think\console\Output;
use think\Db;
//ini_set('display_errors','on');
//error_reporting(E_ALL);
////ini_set(memory_limit, '512M');
//ignore_user_abort(true);//浏览器无响应也继续执行
//set_time_limit(0);
////error_reporting(E_ALL ^ E_NOTICE);
//header("Content-type:text/html; charset=utf-8");
class Test extends Command
{
    //手动执行任务的类 ,在[项目根目录]下输入 php think Test
    protected function configure()
    {
        $this->setName('test')->setDescription('Here is the remark ');
    }

    protected function execute(Input $input, Output $output)
    {halt(2);
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://list.jd.com/list.html?cat=670,716,717";//电脑办公->办公->打印机
//        $url = "https://list.jd.com/list.html?cat=670,716,717&page=2&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
//        $url = "https://list.jd.com/list.html?cat=670,716,717&page=3&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
        $x = phpQuery::newDocumentFile(str_replace("GBK", "utf-8",$url));
//        $html = phpQuery::newDocumentFile($url);

//        $a = pq(".red_center");
//        $a = pq(".col-sm-2 img");
        $a = pq(".p-img a");
//        echo $a;
        $str = substr($url,strpos($url,"cat=")+4);
        $arr = explode(",",$str);
        $first = $arr[0];
        $second = $arr[1];
        $third = $arr[2];
        foreach($a as $href){

//            echo $id++ ."----" .$this->title("http:" . $href->getAttribute('href')). "-----".$href->getAttribute('href'). "----". $this->jiequ($href->getAttribute('href')) ."----".$this->detail("http:" . $href->getAttribute('href')) . "<br>";

            $add['href'] = $href->getAttribute('href');
            $add['price'] = $this->jiequ($href->getAttribute('href'));
            $add['img'] = $this->detail("http:" . $href->getAttribute('href'));
            $add['title'] = $this->title("http:" . $href->getAttribute('href'));
            $add['addtime'] = time();
            $add['first'] = $first;
            $add['second'] = $second;
            $add['third'] = $third;
            $add['url'] = $url;
            DB::name('jd_670')->add($add);
            unset($add);
//            return;
//            echo $id++ . ":".$href->getAttribute('href')."<br>";
//            return;
            //商品链接 . 商品价格  . 商品图片
        }
        //翻页处理

        $n = $this->page($url);

        for($page=2;$page<=$n;$page++){
//            if($page/8 == 1){
//                sleep(10);
//            }
//            $new_url = $url . "&page=".$page."&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
            $new_url = $url . "&page=".$page;
            $y = phpQuery::newDocumentFile(str_replace("GBK","utf-8",$new_url));
            $b = pq(".p-img a");
            foreach($b as $href2){
                $add['href'] = $href2->getAttribute('href');
                $add['price'] = $this->jiequ($href2->getAttribute('href'));
                $add['img'] = $this->detail("http:" . $href2->getAttribute('href'));
                $add['title'] = $this->title("http:" . $href2->getAttribute('href'));
                $add['addtime'] = time();
                $add['first'] = $first;
                $add['second'] = $second;
                $add['third'] = $third;
                $add['url'] = $new_url;
                DB::name('jd_670')->add($add);
                unset($add);
            }

        }

    }

    public function title($url)
    {
//        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "https://item.jd.com/1288838091.html";
        phpQuery::newDocumentFile(str_replace("GB2312", "utf-8",$url));
        $title = pq(".sku-name")->html();
        $title = iconv('GB2312', 'UTF-8', $title);
        return trim($title);
    }
    public function detail($url)
    {
//        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "http://item.jd.com/16625870348.html";
        $x = phpQuery::newDocumentFile(str_replace("GBK","utf8",$url));
        $a = pq("#spec-img");
//        echo $a;die;

        foreach($a as $href){
            return $href->getAttribute('data-origin');
        }
    }
    public function page($url)
    {
//        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "https://list.jd.com/list.html?cat=670,716,717";
        phpQuery::newDocumentFile(str_replace("GB2312","utf8",$url));
        $page = pq(".p-skip b")->html();
        return intval($page);
    }

    public function jiequ($redirectUrl)
    {
//        $redirectUrl = "//item.jd.com/536668.html";
        $sVid = $this->get_between($redirectUrl, "com/", ".html");
        $url = "https://p.3.cn/prices/mgets?callback=jQuery2007473&ext=11101000&pin=&type=1&area=1_72_2799_0&skuIds=J_".$sVid."&pdbp=0&pdtk=&pdpin=&pduid=15511518069012145038807&source=list_pc_front&_=".time()."987";
        $opts = array (
            'http' => array (
                'method' => 'GET',
                'header'=>
                    "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n" .
                    "Cookie:a=as; \r\n".
                    "Pragma:no-cache\r\n",
            )
        );
        $context = stream_context_create($opts);
//        $result = file_get_contents($url,false,$context);
        $result = @httpGet($url);
        $result = json_decode($this->get_between($result,"[","]"),true);
        //array(4) {
//        ["id"] => string(8) "J_536668"
//    ["m"] => string(7) "1099.00"
//    ["op"] => string(6) "849.00"
//    ["p"] => string(6) "829.00"
//}

        $price = $result['p'];//
        return $price;
    }
    public function get_between($input, $start, $end) {

        $substr = substr($input, strlen($start)+strpos($input, $start),(strlen($input) - strpos($input, $end))*(-1));

        return $substr;

    }

}