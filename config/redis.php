<?php

$order = '35';
$service = 'Webox-Redis';

reConfig(
    XS_CFG.'/redis',
    XS_ETC.'/redis',
    array(
        '{XS.IPN}' => XS_IPN,
        '{XS.TMP}' => XS_DAT,
    )
);