<?php

//系统参数定义
define('TIME', time());
define('ROOT', getcwd());
define('XS_DIR', str_replace('\\', '/', ROOT));
define('XS_IPN', gethostbyname($_SERVER['SERVER_NAME']));

//用户参数定义
define('XS_CFG', XS_DIR.'/config');//配置文件根目录
define('XS_ETC', XS_DIR.'/deploy');//配置文件根目录
define('XS_MOD', XS_DIR.'/module');//应用模块根目录
define('XS_DAT', XS_DIR.'/storage');//数据文件根目录
define('XS_WEB', XS_DIR.'/webroot');//站点文件根目录

//////////////////////////////////////reConfig//////////

echo "备份配置文件...\n\n";

mvConfig(XS_ETC, XS_DAT);

echo "重建配置文件...\n";

$module = array();
foreach(glob(XS_CFG.'/*.php') as $php) {
    $order = $php; $service = ''; include($php);
    $module[$order] = basename($php, '.php').' '.$service;
}

ksort($module);
$module = implode(PHP_EOL, $module);
file_put_contents(XS_ETC.'/module.ini', $module);

//////////////////////////////////////Functions//////////

//备份配置文件
function mvConfig($src, $dst) {
    $src = str_replace('/', '\\', $src);
    $dst = str_replace('/', '\\', $dst);
    if(is_dir($src)) {
        exec("move /y {$src} {$dst}\deploy-".TIME);
    }
}

//重建配置文件
function reConfig($src, $dst, $rep = array()) {
    if(is_file($src)) {//文件
        $content = file_get_contents($src);
        return file_put_contents($dst, strtr($content, $rep));
    }
    if(is_dir($src)) {//目录
        aw_copy($src, $dst);
        foreach(aw_glob($dst) as $f) {
            reConfig($f, $f, $rep);
        }
    }
}

//递归复制目录
function aw_copy($src, $dst) {
    if($src == $dst || !is_dir($src)) {
        return false;
    }
    $src = str_replace('/', '\\', $src);
    $dst = str_replace('/', '\\', $dst);
    exec("xcopy {$src} {$dst} /e /i /r /q", $a, $r);
    return !$r;
}

//递归获取文件
function aw_glob($path = './', $mark = '*', $full = false) {
    $files = array();
    //获取根目录文件
    if($result = glob($path.$mark, GLOB_MARK|GLOB_BRACE)) {
        $result = str_replace('\\', '/', $result);
        foreach($result as $file) {
            substr($file, -1, 1) == '/' || $files[] = $file;
        }
    }
    //获取子目录文件
    if($result = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR)) {
        $result = str_replace('\\', '/', $result);
        foreach($result as $path) {
            $full && $files[] = $path;
            $files = array_merge($files, aw_glob($path, $mark, $full));
        }
    }
    return $files;
}

?>