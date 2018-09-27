<?php
/**
 * 微信登录类
 */
namespace app\Common\logic;

class WxLogin {
    
    //appId
    private $appId = '';
    //appSecret
    private $appSecret = '';
    //redirect_uri
    private $redirect_uri = '';
    public function __construct($config = array())
    {
        $this->appId = $config['appId'];
        $this->appSecret = $config['appSecret'];
    }
    /**
     * 微信登录
     *
     * @param  $code          用户同意授权后，传回临时票据code参数;
     */
    public function wx_log ($code) {

        if (empty($code)) {
            return false;
        }
        $token = self::get_access_token($code);
       
        if (isset($token->errcode)) 
        {
            return false;
        }
        $access_token = self::get_refresh_token($token);
        if (isset($access_token->errcode)) 
        {
            return false;
        }
        $user_info = self::get_userinfo($access_token);
        if (isset($user_info->errcode)) {
            return false;
        }
        $info = $user_info;
        $info->nickname= str_replace("'" , "" ,$info->nickname); 
        $info->privilege = implode(',',$info->privilege);
        $info->username = $info->nickname;
       // var_dump($info);die;
        return $info;
    }
    /**
     * 微信开放平台授权网址
     * @param  $url 回调地址
     * @param  $state 参数  
     */
    public function get_authorize_url($url,$state)
    {   
        $this->redirect_uri = urlencode($url);
        $authorize_url = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$this->appId.'&redirect_uri='.$this->redirect_uri.'&response_type=code&scope=snsapi_login&state='.$state.'#wechat_redirect';
        return $authorize_url;
    }
    /**
     * 微信公众平台授权跳转
     * @param string $url 回调
     * @return string
     */
    public function getOauthurl($url,$state='',$scope='snsapi_userinfo'){
        $this->redirect_uri = urlencode($url);
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.$this->redirect_uri.'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
    }
    /**
     * 根据code获取授权toke
     *
     * @param  $parameters
     */
    public function get_access_token($code)
    {
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code';  
        $token = curl_request($token_url);
        return json_decode($token);
    }

    /**
     * 刷新access_token有效期
     *
     * @param $token 
     */
    public function get_refresh_token ($token) {
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appId.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
        $access_token = curl_request($access_token_url);
        return json_decode($access_token);
    }
    /**
     * 根据access_token获取用户信息
     *
     * @param  $access_token
     */
    public function get_userinfo($access_token)
    {
        $info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid;  
        $info = curl_request($info_url);
        return json_decode($info);
    }
    
   
}
