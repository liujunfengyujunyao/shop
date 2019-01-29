<?php
namespace app\Sever\Controller;
use Workerman\Worker;
/**
 * 用户信息查询
 */
class Workerman{
    /**
     * 用户信息查询
     */
    public function index(){
        halt(IS_CLI);
        if(!IS_CLI){
            die("access illegal");
        }
        require_once APP_PATH.'Workerman/Autoloader.php';
        define('MAX_REQUEST', 1000);// 每个进程最多执行1000个请求
//        Worker::$daemonize = true;//以守护进程运行
        Worker::$pidFile = '/data/wwwlogs/Worker/workerman.pid';//方便监控WorkerMan进程状态
        Worker::$stdoutFile = '/data/wwwlogs/Worker/stdout.log';//输出日志, 如echo，var_dump等
        Worker::$logFile = '/data/wwwlogs/Worker/workerman.log';//workerman自身相关的日志，包括启动、停止等,不包含任何业务日志
        $worker = new \Worker('text://192.168.1.144:8080');//此处我使用内网ip

        $worker->name = 'Worker';
        $worker->count = 2;
        //$worker->transport = 'udp';// 使用udp协议，默认TCP
        $worker->onWorkerStart = function($worker){
            echo "Worker starting...\n";
        };
        $worker->onMessage = function($connection, $data){
            static $request_count = 0;// 已经处理请求数
            //$_rs=D("Article")->gettest();
            $_articleObj=A("article");
            $_rs=$_articleObj->gettest();
            var_dump($_rs);
            $connection->send("hello");
            /*
             * 退出当前进程，主进程会立刻重新启动一个全新进程补充上来，从而完成进程重启
             */
            if(++$request_count >= MAX_REQUEST){// 如果请求数达到1000
                Worker::stopAll();
            }
        };
        $worker->onBufferFull = function($connection){
            echo "bufferFull and do not send again\n";
        };
        $worker->onBufferDrain = function($connection){
            echo "buffer drain and continue send\n";
        };
        $worker->onWorkerStop = function($worker){
            echo "Worker stopping...\n";
        };
        $worker->onError = function($connection, $code, $msg){
            echo "error $code $msg\n";
        };
        // 运行worker
        Worker::runAll();
    }
}