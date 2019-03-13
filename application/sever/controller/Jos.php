<?php
namespace app\sever\controller;
use think\Controller;
use think\console\Command;
use phpQuery;
use think\Db;
//use \phpQuery\phpQuery;
/**
 * Created by PhpStorm.
 * User: GoldenBrother
 * Date: 2019/2/25
 * Time: 16:42
 */
//ini_set('display_errors','on');
//error_reporting(E_ALL);
//ini_set(memory_limit, '512M');
ignore_user_abort(true);//浏览器无响应也继续执行
set_time_limit(0);
//error_reporting(E_ALL ^ E_NOTICE);
header("Content-type:text/html; charset=utf-8");
define('FFMPEG_PATH', '/usr/local/ffmpeg2/bin/ffmpeg -i "%s" 2>&1');
class Jos extends Controller
{
    public function getPage($url)
    {
//        $cnt = file_get_contents($url);
        $cnt = httpGet($url);
        return mb_convert_encoding($cnt ,"UTF-8","GBK");

    }

    public function index()
    {
        include 'vendor/phpQuery/phpQuery/phpQuery.php';


        $url = 'http://www.jd.com/allSort.aspx';
        $page = $this->getPage($url);
//        $phpQuery = new \phpQuery;
        phpQuery::newDocumentFile($url);
//halt($a);
        $firstCate = pq('#allsort .m');halt($firstCate);

       $topcate = pq($firstCate)->find(".mt a");
       halt($topcate);
       $id = 0;
        foreach($firstCate as $first){
            $id ++;
            $topcate = pq($first)->find(".mt a");
            //echo "**************************" . $topcate->text() . "**************************************</br>";
            echo $id . "#";
            foreach($topcate as $top){
                echo pq($top)->text() . "#" . "< a href='" .pq($top)->attr("href") . "' target='_blank'>" . pq($top)->text() ."< /a>、";
            }
            echo "#0#1</br>";
            $companies = pq($first)->find(".mc dl");
            $parent_id = $id;
            foreach($companies as $company)
            {
                $id++;
                $sparent_id = $id;
                echo "&nbsp;&nbsp;" . $id . "#" .pq($company)->find('dt')->text() . "#" .  "< a href='" . pq($company)->find('dt a')->attr("href") . "' target='_blank'>" . pq($company)->find('dt')->text() ."< /a>#" . $parent_id ."#2<br>";
                $cate = pq($company)->find('dd em a');
                foreach($cate as $detail) {
                    $id++;
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;" .  $id . "#" .pq($detail)->text() . "#" . "< a href='". pq($detail)->attr("href") . "' target='_blank'>" . pq($detail)->text() ."< /a>#" . $sparent_id . "#3<br>";
                }

            }
        }


        }

    public function goods()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://list.gome.com.cn/cat21445766.html?intcmp=sy-1000052188_0";
//        $page = $this->getPage($url);
//        $x = phpQuery::newDocumentFile(str_replace("BGK", "utf-8",$url));
        $x = phpQuery::newDocumentFile($url);
//        $x = pq(".p-price");
//        $hrefList = pq(".item-pic img");//选择器:图片类
//        $hrefList = pq(".product-item .item-link");//选择器:title
        $hrefList = pq(".price.asynPrice");//选择器:价格
//        echo $hrefList;die;
        $id = 0;
//       halt(1212);
        foreach($hrefList as $href){

//            echo $a->getAttribute('class'),"<br>";
//              echo $id++ . ":".$href->getAttribute('title')."<br>";
              echo $id++ . ":".$href->getAttribute('pid')."<br>";
//                echo pq($href)->text();
//                echo 111;

//            echo pq($href)->html(),"<br>";
        }

    }






    public function test()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        phpQuery::newDocumentFile('http://www.doutula.com/article/list/?page=1');
//        include 'phpQuery/phpQuery.php';
        $html     = phpQuery::newDocumentFile("https://segmentfault.com/tags");
        $hrefList = pq(".tag"); //获取标签为a的所有对象$(".tag")
//        halt($hrefList);
        foreach ($hrefList as $href) {
//            echo $href->getAttribute("data-original-title"),"<br>";
            echo $href->text($href),"<br>";
        }
    }

    public function test2(){
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $html = phpQuery::newDocumentFile("https://www.jd.com/allSort.aspx");
        $hrefList = pq(".ui-category-item");
        foreach ($hrefList as $href) {
            echo $href->getAttribute("data-idx"),"<br>";
        }
    }


    public function hao()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://list.gome.com.cn/cat21445766.html?intcmp=sy-1000052188_0";
        phpQuery::newDocumentFile($url);
//        echo pq('title')->text();
        echo pq(".product-left-list")->text();



    }

    public function spider($url)//爬取商品的详细链接  -> 爬此商品图片  -> 入库  (需要翻页)
    {
//        $params = file_get_contents('php://input');
//        $params = I("");
//        halt($params);
////        halt($url);
//
//
//        print_r($url);die;
        $url = "https://list.jd.com/list.html?cat=" . $url;
        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "https://list.jd.com/list.html?cat=670,716,722";//电脑办公->办公->投影仪
//        $url = "https://list.jd.com/list.html?cat=670,716,5010";//电脑办公->办公->投影配件
//        $url = "https://list.jd.com/list.html?cat=670,716,720";//电脑办公->办公->多功能一体机
//        $url = "https://list.jd.com/list.html?cat=670,716,717";//电脑办公->办公->打印机
//        $url = "https://list.jd.com/list.html?cat=670,716,725";//电脑办公->办公->验钞机
//        $url = "https://list.jd.com/list.html?cat=670,716,718";//电脑办公->办公->传真机
//        $url = "https://list.jd.com/list.html?cat=670,716,721";//电脑办公->办公->扫描设备
//        $url = "https://list.jd.com/list.html?cat=670,716,719";//电脑办公->办公->复印机
//        $url = "https://list.jd.com/list.html?cat=670,716,723";//电脑办公->办公->碎纸机
//        $url = "https://list.jd.com/list.html?cat=670,716,724";//电脑办公->办公->考勤机
//        $url = "https://list.jd.com/list.html?cat=670,716,7373";//电脑办公->办公->收银机
//        $url = "https://list.jd.com/list.html?cat=670,716,7375";//电脑办公->办公->会议音频视频
//        $url = "https://list.jd.com/list.html?cat=670,716,2601";//电脑办公->办公->保险柜/箱
//        $url = "https://list.jd.com/list.html?cat=670,716,4839";//电脑办公->办公->装订/封装机
//        $url = "https://list.jd.com/list.html?cat=670,716,7374";//电脑办公->办公->安防监控
//        $url = "https://list.jd.com/list.html?cat=670,716,727";//电脑办公->办公->白板
//        $url = "https://list.jd.com/list.html?cat=670,716,717&page=2&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
//        $url = "https://list.jd.com/list.html?cat=670,716,717&page=3&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
//        $x = phpQuery::newDocumentFile(str_replace("GBK", "utf-8",$url));
        phpQuery::newDocumentFile(str_replace("GBK", "utf-8",$url));
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
//            $add['byt'] = memory_get_usage();
//            $add['max'] = memory_get_peak_usage();
//            $sql = "INSERT INTO `tfs_jd_670` VALUES ($add['href'],$add['price'],$add['img'],$add['title'],$add['url'])";
//            $sql =  "INSERT INTO `tfs_jd_670` VALUES ($add)";
//            dump($sql);
//            $sql = "INSERT INFO `tfs_jd_670` VALUES (" . "'" . $add['href'] . "'" . "," . "'" . $add['price'] . "'" . "," . "'" . $add['img'] . "'" . ")";
//            $sql =  "INSERT INTO `tfs_jd_670` VALUES ('$add[price]'，'$add[href]'，'$add[url]')";

//             file_put_contents('./JD_log.sql', $sql.PHP_EOL, FILE_APPEND);

            DB::name('jd_test')->add($add);

//            unset($add);
            $add = null;
//            return;
//            echo $id++ . ":".$href->getAttribute('href')."<br>";
//            return;
            //商品链接 . 商品价格  . 商品图片
        }
        //翻页处理

//        $n = $this->page($url);
        $n = 10;
        for($page=2;$page<=$n;$page++){
//            if($page/8 == 1){
//                sleep(10);
//            }
//            $new_url = $url . "&page=".$page."&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
            $new_url = $url . "&page=".$page;
//            $y = phpQuery::newDocumentFile(str_replace("GBK","utf-8",$new_url));
            phpQuery::newDocumentFile(str_replace("GBK","utf-8",$new_url));
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
//                $add['byt'] = memory_get_usage();
//                $add['max'] = memory_get_peak_usage();
//                $sql = "INSERT INTO `tfs_jd_670` VALUES ($add['href'],  $add['price'],  $add['img'], $add['title'],$add['url'])";
                DB::name('jd_test')->add($add);
//                $sql = "INSERT INFO `tfs_jd_670` VALUES (" . "'" . $add['href'] . "'" . "," . "'" . $add['price'] . "'" . "," . "'" . $add['img'] . "'" . ")";
//                $sql =  "INSERT INTO `tfs_jd_670` VALUES ('$add[price]'，'$add[href]'，'$add[url]')";
//                file_put_contents('./JD_log.sql', $sql.PHP_EOL, FILE_APPEND);
                $add = null;
            }

        }




    }

    public function title($url)
    {
//        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "https://item.jd.com/1288838091.html";
        phpQuery::newDocumentFile(str_replace("GB2312", "utf-8",$url));
        $title = pq(".sku-name")->html();
        $title = iconv('GBK', 'UTF-8', $title);
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

//    public function title2()
//    {
//        include "vendor/phpQuery/phpQuery/phpQuery.php";
//        $url = "https://item.jd.com/536668.html";
//        phpQuery::newDocumentFile(str_replace("GB2312", "utf-8",$url));
//        $title = pq(".sku-name")->html();
//        $title = iconv('GB2312', 'UTF-8', $title);
//        return $title;
//    }


    public function jiage()//爬取商品的详细链接  -> 爬此商品图片  -> 入库  (需要翻页)
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://item.jd.com/536668.html";
        $x = phpQuery::newDocumentFile(str_replace("GBK", "utf-8",$url));
//        $html = phpQuery::newDocumentFile($url);

//        $a = pq(".red_center");
//        $a = pq(".col-sm-2 img");
        $a = pq(".price.J-p-536668");
      print_r($a);die;
        echo $a;die;
        $id = 0;
        foreach($a as $href){

            echo $id++ . "-----".$href->getAttribute('href'). "<br>";
//            echo $id++ . ":".$href->getAttribute('href')."<br>";
//            if($id = 2);return;

        }
    }








    public function bu(){
        $result = $this->detail("http://item.jd.com/16625870348.html");
        echo $result;
    }



    public function detail2(){
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "http://item.jd.com/16625870348.html";
        $x = phpQuery::newDocumentFile(str_replace("GBK","utf8",$url));
        $a = pq("#spec-img");


        foreach($a as $href){
            return $href->getAttribute('data-origin');
        }
    }

    public function price()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://list.jd.com/list.html?cat=670,716,718&page=2&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
        $x = phpQuery::newDocumentFile(str_replace("GBK","utf8",$url));
        $a = pq(".gl-i-wrap.j-sku-item .J_price");
        print_r($a);die;
        foreach($a as $href){
            echo pq($href)->find("i")->html();
        }
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


    public function add()
    {
        $data = array(
            'title' => "爱普生（EPSON） 平推票据针式打印机 税务发票打印机 套装：615KII标配+色带一支 标配",
            'addtime' => time(),
        );
        $add = DB::name('jd_670')->add($data);
        halt($add);
    }

    public function cmd()
    {
        $url = "https://list.jd.com/list.html?cat=670,716,717";
        $opts = array (
            'http' => array (
                'method' => 'GET',
                'header'=>
                    "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n" .
                    "Cookie:a=as; \r\n".
                    "Pragma:no-cache\r\n",
            )
        );
//        $context = stream_context_create($opts);
//        ($url, $method="GET", $postfields = null, $headers = array(), $debug = false)
//        $url = httpRequest($url,"GET",null,$opts,fasle);
//        $url = httpGet($url);
        $url = fopen($url,"r");
        halt($url);
    }


    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    public function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }

    public function banz()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = "https://list.jd.com/list.html?cat=670,716,717";
        phpQuery::newDocumentFile(str_replace("GBK","utf8",$url));
        $a = pq(".p-img a");
        $str = substr($url,strpos($url,"cat=")+4);
        $arr = explode(",",$str);
        $first = $arr[0];
        $second = $arr[1];
        $third = $arr[2];
        foreach($a as $href) {
            $add['href'] = $href->getAttribute("href");
            $substr = substr($add['href'], strlen("com/") + strpos($add['href'], "com/"), (strlen($add['href']) - strpos($add['href'], ".html")) * (-1));
            $ch_url = "https://p.3.cn/prices/mgets?callback=jQuery2007473&ext=11101000&pin=&type=1&area=1_72_2799_0&skuIds=J_" . $substr . "&pdbp=0&pdtk=&pdpin=&pduid=15511518069012145038807&source=list_pc_front&_=" . time() . "987";
//            $context = stream_context_create($ch_url);
            $result = httpGet($ch_url);
            $result = json_decode($this->get_between($result, "[", "]"), true);
            $add['price'] = $result['p'];
            $add['addtime'] = time();
//            $add['first'] = $first;
            $add['first'] = 1;
            $add['second'] = $second;
            $add['third'] = $third;
            $add['url'] = $url;
//            $add['byt'] = memory_get_usage();
            $add['byt'] = $first;
            DB::name('jd_670')->add($add);
        }
            $n = 200;
            for($page=2;$page<=$n;$page++){
//            if($page/8 == 1){
//                sleep(10);
//            }
                $new_url = $url . "&page=".$page."&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
                $y = phpQuery::newDocumentFile(str_replace("GBK","utf-8",$new_url));
                $b = pq(".p-img a");
                foreach($b as $href2){
                    $add['href'] = $href2->getAttribute('href');
                    $substr = substr($add['href'], strlen("com/")+strpos($add['href'], "com/"),(strlen($add['href']) - strpos($add['href'], ".html"))*(-1));
                    $ch_url = "https://p.3.cn/prices/mgets?callback=jQuery2007473&ext=11101000&pin=&type=1&area=1_72_2799_0&skuIds=J_".$substr."&pdbp=0&pdtk=&pdpin=&pduid=15511518069012145038807&source=list_pc_front&_=".time()."987";
//                    $context = stream_context_create($ch_url);
                    $result = httpGet($url);
                    $result = json_decode($this->get_between($result,"[","]"),true);
                    $add['price'] = $result['p'];

                    $add['addtime'] = time();
                    $add['first'] = $first;
                    $add['second'] = $second;
                    $add['third'] = $third;
                    $add['url'] = $new_url;
                    $add['byt'] = memory_get_usage();
                    DB::name('jd_670')->add($add);
                }

            }
        }

        public function cli()
        {
            echo "hello world";
        }


        public function byt()
        {
            echo "初始: ".memory_get_usage()." 字节 \n";
            for ($i = 0; $i < 1001700; $i++) {
                $array []= md5($i);
            }
            for ($i = 0; $i < 1001700; $i++) {
                unset($array[$i]);
            }
            echo "最终: ".memory_get_usage()." 字节 \n";
            echo "内存总量: ".memory_get_peak_usage()." 字节 \n";
        }
    public function aa()
    {
        $a = memory_get_usage();
        halt($a);
    }

    public function update_price()
    {

        $stat_time = time();
        $data = DB::name('jd_test')->getField("href",true);
//        $p = DB::name('jd_test')->field("href,price")->select();
        $arr = [];
        foreach($data as $key => $value){
            $sVid = $this->get_between($value,"com/",".html");
            $url = "https://p.3.cn/prices/mgets?callback=jQuery2007473&ext=11101000&pin=&type=1&area=1_72_2799_0&skuIds=J_".$sVid."&pdbp=0&pdtk=&pdpin=&pduid=15511518069012145038807&source=list_pc_front&_=".time()."987";
            $result = httpGet($url);
            $result = json_decode($this->get_between($result,"[","]"),true);
            $price = $result['p'];

//            array_push($arr,[$value=>$price]);
            DB::name('jd_test')->where(['href'=>$value])->save(['price'=>$price]);
        }
$end_time = time();
echo $stat_time . "---" . $end_time;die;
//        halt($arr);

    }

    public function up()
    {
        DB::name('jd_tes t')->where("1=1")->save(['price'=>1]);
        echo "OK";
    }

    public function exception()
    {
        include "vendor/phpQuery/phpQuery/phpQuery.php";
        $url = DB::name('jd_test')->getField("href",true);
        foreach($url as $key => $value){
            phpQuery::newDocumentFile(str_replace("GBK", "utf-8",$url));
        }
    }

    public function haoshi()
    {
//        halt((1552015674-1552011873)/60);
        $a = [];
        array_push($a,['href'=>1]);
        array_push($a,"2");
        halt($a);
    }

    public function bmw()
    {
        $fangzu = 1500;
        $shouru = DB::name("quanguo")->where(['people_id'=>$_SESSION['people_id']])->find();
    }

//  25H+8M+5Y = 38  - 24 = 14

    public  function sql()
    {
        static $id = 0;

        $id++;
        echo $id;

    }

    public function t()
    {
        echo $this->sql();
        echo $this->sql();
        echo $this->sql();
    }

    public function t2()
    {
        echo $this->sql();
    }



    public function read_all ($dir){
        if(!is_dir($dir)) return false;
        $handle = opendir($dir);
        if($handle){
            while(($fl = readdir($handle)) !== false){//条件为真
                $temp = $dir.DIRECTORY_SEPARATOR.$fl; //linux不识别\ DIRECTORY_SEPARATOR用来代替
//                return $fl;
                //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
                if(is_dir($temp) && $fl!='.' && $fl != '..'){
                    echo '目录：'.$temp.'<br>';
                    $this->read_all($temp);
                }else{
                    if($fl!='.' && $fl != '..'){

                        echo '文件：'.$temp.'<br>';
                    }
                }
            }
        }
    }

    public function digui()
    {
//        halt($this->read_all('D:\WWW\machine'));
        $arr = array(23,15,43,25,54,2,6,82,11,5,21,32,65);
        for($a = 0;$a<count($arr); $a++){//该层循环控制 需要冒泡的轮数
            for($b = $a+1;$b<count($arr);$b++){//该层循环控制 需要冒泡的轮数
                if($arr[$a] < $arr[$b]){
                    $t = $arr[$b];
                    $arr[$b] = $arr[$a];
                    $arr[$a] = $t;

                }
            }


        }
        halt($arr);

    }



       public function selectSort($arr) {
            //双重循环完成，外层控制轮数，内层控制比较次数
            $len=count($arr);//9
            for($i=0; $i<$len-1; $i++) {//9次
                //先假设最小的值的位置
                $p = $i;
                for($j=$i+1; $j<$len; $j++) {//8次
                    //$arr[$p] 是当前已知的最小值
                    if($arr[$p] > $arr[$j]) {
                        //比较，发现更小的,记录下最小值的位置；并且在下次比较时采用已知的最小值进行比较。
                        $p = $j;
                    }
                }
                //已经确定了当前的最小值的位置，保存到$p中。如果发现最小值的位置与当前假设的位置$i不同，则位置互换即可。
                if($p != $i) {
                    $tmp = $arr[$p];
                    $arr[$p] = $arr[$i];
                    $arr[$i] = $tmp;
                }
            }
            //返回最终结果
            return $arr;
        }


        public function test1()
        {
            $arr = [10,22,3,4,5,6,7,8,11];
            halt($this->test5($arr));
        }
        public function test3($number)
        {
            $count = count($number);
            for($i = 0;$i<$count-1;$i++){
                $p = $i;
                for($j = $i+1;$j<$count;$j++){
                    if($number[$p] > $number[$j])
                        $p = $j;
                }

                if($p != $i){
                    $temp = $number[$p];
                    $number[$p] = $number[$i];
                    $number[$i] = $temp;
                }

            }
            return $number;
        }

        public function test4($number)
        {
            for($i = 0;$i<count($number);$i++){
                for($j = $i+1;$j<count($number);$j++){
                    if($number[$i] < $number[$j]){
                        $temp = $number[$i];
                        $number[$i] = $number[$j];
                        $number[$j] = $temp;
                    }
                }

            }

            return $number;
        }

        public function test5($number)
        {
            $count = count($number);
            for($i=0;$i<$count-1;$i++){
                $p = $i;
                for($j=$i+1;$j<$count;$j++){
                    if($number[$p]>$number[$j]){
                        $p = $j;
                    }
                }
                if($p != $i){
                    $temp = $number[$p];
                    $number[$p] = $number[$i];
                    $number[$i] = $temp;
                }

            }
            return $number;
        }

        public function test6()
        {
            $number = array(23,15,43,25,54,2,6,82,11,5,21,32,65);
            for($i=0;$i<count($number);$i++){
                for($j=$i+1;$j<count($number);$j++){
                    if($number[$i]<$number[$j]){
                        $temp = $number[$i];
                        $number[$i] = $number[$j];
                        $number[$j] = $temp;

                    }
                }
            }
            halt($number);
        }


    public function fbnq($n){
        if($n <= 0) return 0;
        if($n == 1 || $n == 2) return 1;
        return $this->fbnq($n - 1) + $this->fbnq($n - 2);
    }
    public function testfbnq()
    {
        for($i = 1;$i<50;$i++)
        {
            echo $this->fbnq($i). ",";
        }
    }



    public function getVideoInfo() {
        $file = "http://192.168.1.144/public/upload/ad/video/2019/01-03/23efdc33066b0c3ca6f78b58699dc7a3.mp4";
        echo filesize(iconv('UTF-8','GB2312','D:/WWW/shop/public/upload/ad/video/2019/01-03/01-03/23efdc33066b0c3ca6f78b58699dc7a3.mp4'));die;
        $command = sprintf(FFMPEG_PATH, $file);

        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();

        $data = array();
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
            $data['duration'] = $match[1]; //播放时间
            $arr_duration = explode(':', $match[1]);
            $data['seconds'] = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
            $data['start'] = $match[2]; //开始时间
            $data['bitrate'] = $match[3]; //码率(kb)
        }
        if (preg_match("/Video: (.*?), (.*?), (.*?)[,\s]/", $info, $match)) {
            $data['vcodec'] = $match[1]; //视频编码格式
            $data['vformat'] = $match[2]; //视频格式
            $data['resolution'] = $match[3]; //视频分辨率
            $arr_resolution = explode('x', $match[3]);
            $data['width'] = $arr_resolution[0];
            $data['height'] = $arr_resolution[1];
        }
        if (preg_match("/Audio: (\w*), (\d*) Hz/", $info, $match)) {
            $data['acodec'] = $match[1]; //音频编码
            $data['asamplerate'] = $match[2]; //音频采样频率
        }
        if (isset($data['seconds']) && isset($data['start'])) {
            $data['play_time'] = $data['seconds'] + $data['start']; //实际播放时间
        }
        $data['size'] = filesize($file); //文件大小
        return $data;
    }


}