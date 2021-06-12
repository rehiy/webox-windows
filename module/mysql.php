<?php

$order = '31';
$srvname = 'WeBox-MySQL';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

build_config(
    WB_MOD . '/mysql/etc',
    WB_ETC . '/mysql',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.SQL}' => WB_DAT . '/mysql',
    )
);
