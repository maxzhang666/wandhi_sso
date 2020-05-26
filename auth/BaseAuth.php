<?php


use GuzzleHttp\Client;

class BaseAuth
{
    protected $app_key;
    protected $app_sec;
    protected $scope;
    protected $_client;
    protected $api;

    /**
     * @desc 获取GuzzleHttp的Client实例
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        !$this->_client && $this->_client = new Client(['base_uri' => $this->api, 'verify' => false]);

        return $this->_client;
    }

    /**
     * get方式调用接口
     *
     * @param $url
     * @param $params
     *
     * @throws \Namet\Socialite\SocialiteException
     */
    protected function get()
    {
        return $this->_request('get', func_get_args());
    }

    /**
     * post方式调用接口
     *
     * @param $url
     * @param $params
     *
     */
    protected function post()
    {
        return $this->_request('post', func_get_args());
    }

    /**
     * 利用http client发送请求
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    private function _request($method, $arguments)
    {
        try {
            $response = $this->getClient()->$method($arguments[0], $arguments[1]);
            $res      = (string)$response->getBody();
            $data     = json_decode($res, true) ?: $res;

            return $data;
        } catch (\Exception $e) {
            return "";
        }
    }

    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    public function getRandomStr()
    {

        $str     = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max     = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }
}