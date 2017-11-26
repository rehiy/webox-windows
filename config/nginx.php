<?php

$order = '99';
$service = 'Webox-Nginx';

reConfig(
    XS_CFG.'/nginx',
    XS_ETC.'/nginx',
    array(
        '{XS.IPN}' => XS_IPN,
        '{XS.TMP}' => XS_DAT,
        '{XS.WEB}' => XS_WEB,
    )
);
