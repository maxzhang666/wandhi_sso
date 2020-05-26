<?php

include APP_PATH . 'plugin/wandhi_sso/inc/core.php';

$config = kv_get(P_NAME);


$auth = new BaseAuth();
$type = mb_strtolower(param(1));

switch ($type) {
    case PLATFORM_GITHUB:
        option_check(PLATFORM_GITHUB, $config);
        $auth = new GithubAuth($config[PLATFORM_GITHUB]['app_name'], $config[PLATFORM_GITHUB]['client_id'], $config[PLATFORM_GITHUB]['client_secret']);
        $type = PLATFORM_GITHUB;
        break;
    case PLATFORM_QQ:
        option_check(PLATFORM_QQ, $config);
        $auth = new QQAuth($config[PLATFORM_QQ]['client_id'], $config[PLATFORM_QQ]['client_secret']);
        $type = PLATFORM_QQ;
        break;
    case PLATFORM_WECHAT:
        option_check(PLATFORM_WECHAT, $config);
        $type = PLATFORM_WECHAT;
        break;
    case PLATFORM_SINA:
        option_check(PLATFORM_SINA, $config);
        $type = PLATFORM_SINA;
        break;
    default:
        message(1, "参数错误");
}

if (isset($_GET['code'])) {

    //部分应用需要回传回调地址
    $callback  = http_url_path() . url('sso_login_callback-' . $type);
    $user_info = $auth->get_auth_info(_GET('code'), $callback);

    if (empty($user)) {
        // 登录账号
        $tmp_user = get_user_info($user_info['openid'], $type);

        if (!empty($tmp_user)) {

            $last_login = array(
                'login_ip'   => $longip,
                'login_date' => $time,
                'logins+'    => 1,
            );

            $uid = $tmp_user['uid'];
            user_update($tmp_user['uid'], $last_login);
            $_SESSION['uid'] = $uid;
            user_token_set($uid);

            message(0, jump('登陆成功', '/my.htm', 3));
        } else {
            //跳出
            message(0, jump('用户不存在，请先绑定论坛账号', '/user-login.htm', 3));
        }
    } else {
        //绑定用户
        $tmp_user = get_user_info($user_info['openid'], $type);

        sso_bind_uid($user['uid'], $user_info['openid'], $type, $user_info['user_name'], $user_info['avatar']);

        message(0, jump('绑定成功', '/my.htm', 3));
    }
} else {
    message(1, '参数异常，请重试');
}
