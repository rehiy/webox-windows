<?php

$order = '31';
$service = 'WeBox-MySQL';

build_config(
    WB_CFG.'/mysql',
    WB_ETC.'/mysql',
    array(
        '{WB.MOD}' => WB_MOD,
        '{WB.SQL}' => WB_DAT.'/mysql',
    )
);
