<?php

$order = '51';
$service = 'WeBox-PHP56';

build_config(
    WB_CFG.'/php56',
    WB_ETC.'/php56',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);

merge_php_ini(WB_ETC.'/php56');

file_put_contents(WB_ETC.'/php56/xxfpm.ini', '127.0.0.1 9501 16');
