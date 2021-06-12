<?php

$order = '52';
$srvname = 'WeBox-php74';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

create_config(
    $appname,
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);

merge_php_ini(WB_ETC . '/php74');

file_put_contents(WB_ETC . '/php74/fpm.ini', '9701 4+16');
