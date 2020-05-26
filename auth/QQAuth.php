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
        $this->api     = "https://graph.qq.com/";
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
     * @param $callback string
     * @return mixed User_Info
     */
    public function get_auth_info($code, $callback)
    {
        $access_token = $this->get_access_token($code, $callback);
        if ($access_token) {
            $openid = $this->get_openid($access_token);
            if ($openid) {
                //查询用户信息
                $user = get_user_info($openid, PLATFORM_QQ);
                if (!$user) {
                    //未查询到，请求用户信息
                    $user = $this->get_user_info($openid, $access_token);
                }
                return $user;
            }
        }

        xn_error(-1, '请求失败，请重试');
    }

    private function get_access_token($code, $callback)
    {
        $params = [
            'client_id'     => $this->app_key,
            'client_secret' => $this->app_sec,
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $callback,
        ];

        $data = $this->get('oauth2.0/token', [
            'query'   => $params,
            'headers' => ['Accept' => 'application/json'],
        ]);

        $res = [];
        parse_str($data, $res);

        return array_key_exists('access_token', $res) ? $res['access_token'] : "";
    }

    /**
     * 获取openid
     * @param $access_token
     */
    private function get_openid($access_token)
    {
        $params = [
            'access_token' => $access_token,
        ];
        $res    = $this->get('oauth2.0/me', ['query' => $params]);
        $data   = json_decode(trim(substr(trim($res), 9, -2)), true);

        return array_key_exists('openid', $data) ? $data['openid'] : "";
    }

    /**
     * 获取用户基础信息
     * @param $openid
     * @param $access_token
     * @return array
     */
    private function get_user_info($openid, $access_token)
    {
        $res = $this->get('user/get_user_info', [
            'query'   => [
                'access_token'       => $access_token,
                'oauth_consumer_key' => $this->app_key,
                'openid'             => $openid,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $user_info = [
            'avatar'    => $res['figureurl_qq_2'],
            'openid'   => $openid,
            'sso_name' => $res['nickname']
        ];

        return $user_info;
    }
}