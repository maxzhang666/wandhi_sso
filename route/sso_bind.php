<?php
if (isset($_GET['unbind'])) {
    $unbind = github_unbind_uid($user['uid']);
    if ($unbind) {
        message(0, jump('解绑成功!', '/my.htm', 2));
    } else {
        message(0, jump('解绑失败!', '/my.htm', 2));
    }
    exit;
}

////        $bind_github = db_find_one('xiuno_github_login', array('openid' => $data['id']));
////
////        if (!empty($bind_github)) {
////            if ($bind_github['uid'] != $user['uid']) {
////                message(0, jump('其他账户已经绑定!', '/my.htm', 3));
////            } else {
////                message(0, jump('已经绑定!', '/my.htm', 3));
////            }
////            exit;
////        }
////
////        //已登录
////        $bind = github_bind_uid($user['uid'], $data['id']);
////        if ($bind) {
////            message(0, jump('绑定成功!', '/my.htm', 3));
////        } else {
////            message(0, jump('绑定失败!', '/my.htm', 3));
////        }