<?php
include _include(APP_PATH . 'plugin/xn_wandhi_sso/inc/core.php');

$tablepre   = $db->tablepre;
$table_name = P_NAME;
$sql        = "
CREATE TABLE IF NOT EXISTS `{$tablepre}{$table_name}` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
    `uid` int(11) NOT NULL COMMENT '用户ID',
    `openid` varchar(64) NOT NULL COMMENT 'openid',
    `sso_type` varchar(32) NOT NULL COMMENT '授权类型：1、Github;2、QQ;3、微信;4、新浪微博;',
    `sso_name` varchar (32) COMMENT '三方用户名',
    `avatar` varchar(500) COMMENT '三方头像',
    `create_date` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Wandhi认证中心'";
$conn       = db_exec($sql);

// 初始化
$config = kv_get(P_NAME);
if (empty($config)) {
    $config = [
        'github' => [
            'on'                   => 0,
            'github_client_id'     => '',
            'github_client_secret' => ''
        ],
        'qq'     => [
            'on'               => 0,
            'qq_client_id'     => '',
            'qq_client_secret' => ''
        ],
        'wechat' => [
            'on'                   => 0,
            'wechat_client_id'     => '',
            'wechat_client_secret' => ''
        ],
        'sina'   => [
            'on'                 => 0,
            'sina_client_id'     => '',
            'sina_client_secret' => '',
        ]
    ];
    kv_set(P_NAME, $config);
}
