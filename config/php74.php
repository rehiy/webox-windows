<?php

$order = '52';
$service = 'WeBox-php74';

build_config(
    WB_CFG.'/php74',
    WB_ETC.'/php74',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);

merge_php_ini(WB_ETC.'/php74');

file_put_contents(WB_ETC.'/php74/xxfpm.ini', '127.0.0.1 9701 16');
