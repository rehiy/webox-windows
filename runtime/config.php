<?php

define('TIME', time());
define('ROOT', dirname(__DIR__));

define('WB_DIR', str_replace('\\', '/', ROOT));
define('WB_LIP', gethostbyname($_SERVER['SERVER_NAME']));

define('WB_ETC', WB_DIR . '/config');
define('WB_MOD', WB_DIR . '/module');

define('WB_OVE', WB_DIR . '/overlay');
define('WB_DAT', WB_DIR . '/storage');
define('WB_WEB', WB_DIR . '/webroot');

//////////////////////////////////////Functions//////////

// 备份配置文件
function move_config($src, $dst)
{
    $src = str_replace('/', '\\', $src);
    $dst = str_replace('/', '\\', $dst);
    if (is_dir($src)) {
        exec("move /y {$src} {$dst}\config-" . TIME);
    }
}

// 创建配置文件
function create_config($app, $vars = array())
{
    $dst = WB_ETC . '/'. $app;
    deep_copy(WB_MOD . '/' . $app . '/etc', $dst);
    if(is_dir(WB_OVE . '/' . $app)) {
        deep_copy(WB_OVE . '/' . $app, $dst);
    }
    foreach (deep_glob($dst) as $file) {
        $content = file_get_contents($file);
        file_put_contents($file, strtr($content, $vars));
    }
}

// PHP配置文件合并工具
function merge_php_ini($path)
{
    $string = '';
    $target = $path . '/php.ini';
    foreach (glob($path . '/php*.ini') as $ini) {
        $string .= file_get_contents($ini) . "\r\n";
        unlink($ini);
    }
    $string = preg_replace('/[\r\n]+;.*/', '', $string);
    file_put_contents($target, $string);
    return $target;
}

// 递归复制目录
function deep_copy($src, $dst)
{
    if ($src == $dst || !is_dir($src)) {
        return false;
    }
    $src = str_replace('/', '\\', $src);
    $dst = str_replace('/', '\\', $dst);
    exec("xcopy {$src} {$dst} /e /i /r /y /q", $a, $r);
    return !$r;
}

// 递归获取文件
function deep_glob($path, $mark = '*', $full = false)
{
    $files = array();
    //获取根目录文件
    if ($result = glob($path . $mark, GLOB_MARK | GLOB_BRACE)) {
        $result = str_replace('\\', '/', $result);
        foreach ($result as $file) {
            substr($file, -1, 1) == '/' || $files[] = $file;
        }
    }
    //获取子目录文件
    if ($result = glob($path . '*', GLOB_MARK | GLOB_ONLYDIR)) {
        $result = str_replace('\\', '/', $result);
        foreach ($result as $path) {
            $full && $files[] = $path;
            $files = array_merge($files, deep_glob($path, $mark, $full));
        }
    }
    return $files;
}

//////////////////////////////////////reConfig//////////

echo "备份配置文件...\n\n";

move_config(WB_ETC, WB_DAT);

echo "重建配置文件...\n";

$module = array();
foreach (glob(WB_MOD . '/*.php') as $mfile) {
    include($mfile);
}

asort($module);
file_put_contents(
    WB_ETC . '/module.ini',
    implode(PHP_EOL, array_keys($module))
);
