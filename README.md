# Webox.xServer 使用手册

功能: HTTP + Redis + MYSQL + PHP(FCGI)

作者: 若海[mail@anrip.com] & 尛岢[mod@kerring.me]

主页: http://www.anrip.com

说明: xServer是业内首家通过CMD实现标准管理接口的集成化WEB开发环境

特性:

 - 支持php多版本切换或同时运行

 - 支持为站点配置独立php进程

### 安装向导

1.解压到任意磁盘根目录，或其它不包含中文及特殊字符的目录

2.如需兼容IIS服务，请运行runtime\httpcfg\iis.cmd修改IIS监听地址

3.运行xServer.bat，选择[安装服务]，即可使用MYSQL+Nginx+PHP等服务

  Nginx 默认监听地址为 0.0.0.0:80
  MySQL 默认监听地址为 127.0.0.1:3306
  PHP56 默认监听地址为 127.0.0.1:9501
  PHP71 默认监听地址为 127.0.0.1:9701

### 组件列表

Redis/3.2.100               https://github.com/dmajkic/redis/downloads

MySQL/5.7.19                http://www.mysql.com/downloads/mysql

Nginx/1.12.2                http://www.nginx.org/en/download.html

PHP/5.6.32
PHP/7.1.11                  http://windows.php.net/download

PHP-redis/3.1.3             http://pecl.php.net/package/redis
PHP-xdebug/2.5.5            http://pecl.php.net/package/xdebug

### 常见问题

警告：
  若需修改服务器参数，请修改config目录内对应的文件

〇、如果管理模块
1、config\*.php表示已经启用的模块
2、config\*.dis表示已经禁用的模块

一、如何建立新站点
1.创建域名对应的网站目录，例如 webroot\net.anrip\www

二、如何管理MySQL
1.使用浏览器访问http://127.0.0.1/dber.php
2.服务器:127.0.0.1；帐户:root/密码:空

三、如何切换PHP版本
1.确保服务未安装，否则请[卸载服务]
2.编辑config\phpye\phpye.ini修改进程池参数
3.编辑Nginx配置etc/suffix/*.inc，修改为对应端口
3.运行xServer.bat，选择[重建配置]，再选择[重启服务]

四、如何修改WEB根目录
1.编辑runtime\config.php，修改[XS.WEB]的值
2.建立[XS.WEB]对应目录，并移动原WEB到[XS.WEB]目录
3.运行xServer.bat，选择[重建配置]，再选择[重启服务]

五、如何修改MySQL数据目录
1.编辑runtime\config.php，修改[XS.SQL]的值
2.建立[XS.SQL]对应目录，并移动原MySQL数据到[XS.SQL]目录
3.运行xServer.bat，选择[重建配置]，再选择[重启服务]

### 更新日志

2017年11月23日
- 更新PHP5版本为5.6.32
- 更新PHP7版本为7.1.11
- 更新Nginx版本为1.12.2

2017年09月28日
- 增加PHP5版本为5.6.31

2017年09月13日
- 更新PHP版本为7.1.9
- 更新MySQL版本为5.7.19

2017年08月23日
- 增加vc_redist_2013

2017年07月13日
- 更新PHP版本为7.1.7
- 更新Nginx版本为1.12.1

2017年05月27日
- 将php57更改为php71
- 还原配置文件编码

2017年05月15日
- 不再支持32位操作系统
- 更新PHP版本为7.1.4
- 更新Nginx版本为1.12.0
- 更新MySQL版本为5.7.18
- 更新Redis版本为3.2.100

2017年之前
- 支持 Windows 32bit
- 更新日志请下载32位兼容版查看

2015年之前
- 支持 Windows XP +
- 更新日志请下载XP兼容版查看
