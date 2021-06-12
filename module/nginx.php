<?php

$order = '99';
$srvname = 'WeBox-Nginx';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

build_config(
    WB_MOD . '/nginx/etc',
    WB_ETC . '/nginx',
    array(
        '{WB.IPN}' => WB_LIP,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);
