<?php

$order = '52';
$service = 'Webox-PHP71';

reConfig(
    XS_CFG.'/php71',
    XS_ETC.'/php71',
    array(
        '{XS.MOD}' => XS_MOD,
        '{XS.TMP}' => XS_DAT,
        '{XS.WEB}' => XS_WEB,
    )
);

php_ini_merge(XS_ETC.'/php71');

file_put_contents(XS_ETC.'/php71/xxfpm.ini', '127.0.0.1 9701 16');
