<?php

$order = '31';
$service = 'Anrip-MySQL';

reConfig(
    XS_CFG.'/mysql',
    XS_ETC.'/mysql',
    array(
        '{XS.MOD}' => XS_MOD,
        '{XS.SQL}' => XS_DAT.'/mysql',
    )
);

?>