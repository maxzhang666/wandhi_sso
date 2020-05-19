<?php


class QQAuth extends BaseAuth implements Auth
{

    /**
     * QQAuth constructor.
     */
    public function __construct($app_key, $app_sec)
    {
        $this->app_key = $app_key;
        $this->app_sec = $app_sec;
        $this->scope   = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
    }



    /**
     * 获取授权链接
     * @param $callback string
     * @return string
     */
    public function get_jump_url($callback)
    {
        $callback = urlencode($callback);
        $state    = md5(uniqid(rand(), TRUE));
        return "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$this->app_key&redirect_uri=$callback&state=$state&scope=$this->scope";
    }

    /**
     * 获取授权信息
     * @param $code string
     * @return mixed User_Info
     */
    public function get_auth_info($code)
    {
        // TODO: Implement get_auth_info() method.
    }
}