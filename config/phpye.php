<?php

$order = '51';
$service = 'Anrip-PHPye';

reConfig(
    XS_CFG.'/phpye',
    XS_ETC.'/phpye',
    array(
        '{XS.IPN}' => XS_IPN,
        '{XS.TMP}' => XS_DAT,
        '{XS.WEB}' => XS_WEB,
    )
);

foreach(glob(XS_CFG.'/*/php.ini') as $ini) {
    $src = dirname($ini);
    $dst = XS_ETC.'/'.basename($src);
    reConfig(
        $src,
        $dst,
        array(
            '{XS.MOD}' => XS_MOD,
            '{XS.TMP}' => XS_DAT,
            '{XS.WEB}' => XS_WEB,
        )
    );
    php_ini_merge($dst);
}

//////////////////////////////////////Functions//////////

//PHP配置文件合并工具
function php_ini_merge($path) {
    $string = '';
    $target = $path.'/php.ini';
    foreach(glob($path.'/*.ini') as $ini) {
        $string .= file_get_contents($ini)."\r\n";
        unlink($ini);
    }
    $string = preg_replace('/[\r\n]+;.*/', '', $string);
    file_put_contents($target, $string);
    return $target;
}

?>