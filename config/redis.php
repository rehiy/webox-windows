<?php

$order = '35';
$service = 'WeBox-Redis';

reConfig(
    WB_CFG.'/redis',
    WB_ETC.'/redis',
    array(
        '{WB.IPN}' => WB_IPN,
        '{WB.TMP}' => WB_DAT,
    )
);
