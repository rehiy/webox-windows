<?php

$order = '52';
$service = 'WeBox-PHP71';

reConfig(
    WB_CFG.'/php71',
    WB_ETC.'/php71',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);

php_ini_merge(WB_ETC.'/php71');

file_put_contents(WB_ETC.'/php71/xxfpm.ini', '127.0.0.1 9701 16');
