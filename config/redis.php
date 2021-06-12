<?php

$order = '35';
$service = 'WeBox-Redis';

build_config(
    WB_CFG . '/redis',
    WB_ETC . '/redis',
    array(
        '{WB.IPN}' => WB_LIP,
        '{WB.TMP}' => WB_DAT,
    )
);
