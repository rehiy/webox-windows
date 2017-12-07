<?php

$order = '51';
$service = 'WeBox-PHP56';

reConfig(
    WB_CFG.'/php56',
    WB_ETC.'/php56',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);

php_ini_merge(WB_ETC.'/php56');

file_put_contents(WB_ETC.'/php56/xxfpm.ini', '127.0.0.1 9501 16');
