<?php
include _include(APP_PATH . 'plugin/wandhi_sso/inc/core.php');
$plugin_cates = [
    'github' => 'Github', 'qq' => '腾讯QQ', 'wechat' => '微信', 'sina' => '新浪微博'
];
$action       = mb_strtolower(param(3, 'GITHUB'));
$config       = kv_get(P_NAME);

if ($method == 'GET') {
    $cate_html = cate_active($action, $plugin_cates);

    $input = [
        'app_switch'    => form_radio_yes_no('app_switch', $config[$action]['on']),
        'app_name'      => form_text('app_name', $config[$action]['app_name']),
        'client_id'     => form_text('client_id', $config[$action]['client_id']),
        'client_secret' => form_text('client_secret', $config[$action]['client_secret'])
    ];

    include _include(APP_PATH . 'plugin/wandhi_sso/setting.htm');

} else {
    if (array_key_exists($action, $plugin_cates)) {
        $config[$action]['on']            = param('app_switch');
        $config[$action]['app_name']      = param('app_name', '');
        $config[$action]['client_id']     = param('client_id');
        $config[$action]['client_secret'] = param('client_secret');
        kv_set(P_NAME, $config);
        message(0, '修改成功');
    } else {
        message(1, '参数异常');
    }
}


/**
 * 导航tab
 * @param $action
 * @return string
 */
function cate_active($action, $plugin_cates)
{
    $html = '';
    foreach ($plugin_cates as $cate => $catename) {
        $url  = url("plugin-setting-" . P_NAME . "-$cate");
        $html .= '<a role="button" class="btn btn btn-secondary' . ($cate == $action ? ' active' : '') . '" href="' . $url . '">' . $catename . '</a>';
    }
    return $html;
}