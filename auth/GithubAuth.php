<?php


class GithubAuth extends BaseAuth implements Auth
{
    private $url = 'https://github.com';
    private $api_url = 'https://api.github.com';
    private $app_name = '';

    /**
     * GithubAuth constructor.
     * @param $app_name
     * @param $app_key
     * @param $app_sec
     */
    public function __construct($app_name, $app_key, $app_sec)
    {
        $this->app_key  = $app_key;
        $this->app_sec  = $app_sec;
        $this->app_name = $app_name;
        $this->scope    = 'repo, user';
    }


    /**
     * 获取授权链接
     * @param $callback string
     * @return string
     */
    public function get_jump_url($callback)
    {
        $code = $this->getRandomStr();
        $para = [
            "client_id"     => $this->app_key,
            "client_secret" => $this->app_sec,
            "code"          => $code,
            "redirect_uri"  => $callback,
            "scope"         => $this->scope
        ];
        return $this->url('/login/oauth/authorize') . "?" . http_build_query($para);
    }

    /**
     * 拼接路径
     * @param $address
     * @return string
     */
    public function url($address)
    {
        return $this->url . $address;
    }

    /**
     * 拼接路径
     * @param $address
     * @return string
     */
    public function api($address)
    {
        return $this->api_url . $address;
    }

    /**
     * 获取授权信息
     * @param $code string
     * @param $callback string
     * @return mixed User_Info
     */
    public function get_auth_info($code, $callback)
    {
        $access_token = $this->get_access_token($code);
        $user         = $this->get_user($access_token);
        return $user;
    }

    public function get_access_token($code)
    {
        $url  = $this->url('/login/oauth/access_token');
        $data = $this->post($url, [
            'form_params' => [
                'client_id'     => $this->app_key,
                'client_secret' => $this->app_sec,
                'code'          => $code
            ],
            'headers'     => [
                "Accept" => "application/json"
            ]
        ]);
        return $data['access_token'];
    }

    public function get_user($access_token)
    {
        $url  = $this->api('/user') . '?access_token=' . $access_token;
        $data = $this->get($url, ['headers' => ["User-Agent" => $this->app_name]]);
        return $this->format_user($data);
    }

    /**
     * 格式化用户信息
     * @param $github_user
     * @return array
     */
    public function format_user($github_user)
    {
        $user_info = [
            'openid'   => $github_user['id'],
            'sso_name' => $github_user['name'],
            'avatar'   => $github_user['avatar_url'],
            'email'    => $github_user['email'],
        ];
        return $user_info;
    }
}