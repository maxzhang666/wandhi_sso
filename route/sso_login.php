<?php

!defined('DEBUG') and exit('Access Denied.');

include APP_PATH . 'plugin/wandhi_sso/inc/core.php';

$config = kv_get(P_NAME);


$auth = new BaseAuth();
$type = mb_strtolower(param(1));

switch ($type) {
    case PLATFORM_GITHUB:
        option_check(PLATFORM_GITHUB, $config);
        $auth = new GithubAuth($config[PLATFORM_GITHUB]['app_name'], $config[PLATFORM_GITHUB]['client_id'], $config[PLATFORM_GITHUB]['client_secret']);
        break;
    case PLATFORM_QQ:
        option_check(PLATFORM_QQ, $config);
        $auth = new QQAuth($config[PLATFORM_QQ]['client_id'], $config[PLATFORM_QQ]['client_secret']);
        break;
    case PLATFORM_WECHAT:
        option_check(PLATFORM_WECHAT, $config);
        break;
    case PLATFORM_SINA:
        option_check(PLATFORM_SINA, $config);
        break;
    default:
        message(1, "参数错误");
}
$state = $auth->getRandomStr();
//cross_site
cache_set(P_NAME . $state, true, 10);
$redirect_uri = http_url_current_host() . url('sso_login_callback-' . $type);
$jump         = $auth->get_jump_url($redirect_uri);

header('Location: ' . $jump);
