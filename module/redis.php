<?php

$order = '35';
$srvname = 'WeBox-Redis';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

build_config(
    WB_MOD . '/redis/etc',
    WB_ETC . '/redis',
    array(
        '{WB.IPN}' => WB_LIP,
        '{WB.TMP}' => WB_DAT,
    )
);
