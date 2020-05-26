<?php

function get_user_info($openid, $type)
{
    $where  = ['openid' => $openid, 'type' => $type];
    $p_user = db_find_one(P_NAME, $where);
    if ($p_user) {
        $user = user_read($p_user['uid']);
        if ($user) {
            return array_merge((array)$p_user, $user);
        } else {
            db_delete(P_NAME, $where);
            return false;
        }
    }
    return $p_user;
}

/**
 * UID 已绑定微信
 */
function github_isbind_user_by_uid($uid)
{
    $arr = db_find_one('xiuno_github_login', array('uid' => $uid));
    if ($arr) {
        return $arr;
    }
    return false;
}

/**
 * 绑定社交账号
 */
function sso_bind_uid($uid, $openid, $type, $user_name, $avatar)
{
    $time = time();
    $bind = array(
        'uid'         => $uid,
        'openid'      => $openid,
        'sso_type'    => $type,
        'sso_name'    => $user_name,
        'avatar'      => $avatar,
        'create_date' => $time,
    );
    $r    = db_insert('xiuno_github_login', $bind);
    if (empty($r)) {
        return false;
    }
    return true;
}

/**
 * 解除GITHUB账号绑定
 */
function github_unbind_uid($uid)
{
    $r = db_delete('xiuno_github_login', array('uid' => $uid));
    if (empty($r)) {
        return false;
    }
    return true;
}

/**
 * 配置检测
 * @param $type string
 * @param $conf array
 * @return bool
 */
function option_check($type, $conf)
{
    $type = mb_strtolower($type);
    if ((!array_key_exists($type, $conf)) || $conf[$type]['on'] != 1) {
        message(1, "当前站点未启用该登陆方式!");
    }
}

function http_url_current_host()
{
    $port  = _SERVER('SERVER_PORT');
    $host  = _SERVER('HTTP_HOST');  // host 里包含 port
    $https = strtolower(_SERVER('HTTPS', 'off'));
    $proto = strtolower(_SERVER('HTTP_X_FORWARDED_PROTO'));
    $http  = (($port == 443) || $proto == 'https' || ($https && $https != 'off')) ? 'https' : 'http';
    return "$http://$host/";
}