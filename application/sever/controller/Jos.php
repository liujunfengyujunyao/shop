<?php
namespace app\sever\controller;
use think\Controller;
use phpQuery;
use think\Db;
//use \phpQuery\phpQuery;
/**
 * Created by PhpStorm.
 * User: GoldenBrother
 * Date: 2019/2/25
 * Time: 16:42
 */
ini_set('display_errors','on');
error_reporting(E_ALL);
ignore_user_abort(true);//浏览器无响应也继续执行
set_time_limit(0);
header("Content-type:text/html; charset=utf-8");
class Jos extends Controller
{
    public function getPage($url)
    {
        $cnt = file_get_contents($url);
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

    public function tai()//爬取商品的详细链接  -> 爬此商品图片  -> 入库  (需要翻页)
    {
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
            $new_url = $url . "&page=".$page."&sort=sort_totalsales15_desc&trans=1&JL=6_0_0#J_main";
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
        $result = file_get_contents($url,false,$context);
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
        $url = "http://192.168.1.144/sever/jos/tai";
        httpGet($url);
    }


}