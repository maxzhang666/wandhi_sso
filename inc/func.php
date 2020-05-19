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
 * GITHUB账号绑定
 */
function github_bind_uid($uid, $openid)
{
    $time = time();
    $bind = array(
        'uid'         => $uid,
        'openid'      => $openid,
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