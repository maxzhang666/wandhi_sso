<?php

interface Auth
{

    /**
     * 获取授权信息
     * @param $code string
     * @param $callback string
     * @return mixed User_Info
     */
    public function get_auth_info($code, $callback);

    /**
     * 获取授权链接
     * @param $callback string
     * @return string
     */
    public function get_jump_url($callback);

}