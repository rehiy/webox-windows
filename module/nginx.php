<?php

$order = '99';
$srvname = 'WeBox-Nginx';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

create_config(
    $appname,
    array(
        '{WB.IPN}' => WB_LIP,
        '{WB.TMP}' => WB_DAT,
        '{WB.WEB}' => WB_WEB,
    )
);
