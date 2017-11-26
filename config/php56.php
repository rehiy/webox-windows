<?php

$order = '51';
$service = 'Webox-PHP56';

reConfig(
    XS_CFG.'/php56',
    XS_ETC.'/php56',
    array(
        '{XS.MOD}' => XS_MOD,
        '{XS.TMP}' => XS_DAT,
        '{XS.WEB}' => XS_WEB,
    )
);

php_ini_merge(XS_ETC.'/php56');

file_put_contents(XS_ETC.'/php56/xxfpm.ini', '127.0.0.1 9501 16');
