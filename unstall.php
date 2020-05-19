<?php
include _include(APP_PATH . 'plugin/wandhi_sso/inc/core.php');

$tablepre   = $db->tablepre;
$table_name = P_NAME;
$sql        = "DROP TABLE IF EXISTS `{$tablepre}{$table_name}`";

db_exec($sql);