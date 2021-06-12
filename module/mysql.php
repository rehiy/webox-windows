<?php

$order = '31';
$srvname = 'WeBox-MySQL';
$appname = basename(__FILE__, '.php');

$module[$appname . ' ' . $srvname] = $order;

//////////////////////////////////////Config////////////

create_config(
    $appname,
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.SQL}' => WB_DAT . '/mysql',
    )
);
