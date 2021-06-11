# WeBox-windows 使用手册

功能: HTTP + Redis + MYSQL + PHP(FCGI)

作者: 若海[wang@rehiy.com] & 尛岢[mod@kerring.me]

主页: http://www.rehiy.com

说明: WeBox是业内首家通过CMD实现标准管理接口的集成化WEB开发环境

特性:

 - 支持php多版本切换或同时运行

 - 支持为站点配置独立php进程

### 安装向导

- 解压到任意磁盘根目录，或其它不包含中文及特殊字符的目录

- 如需兼容IIS服务，请运行runtime\httpcfg\iis.cmd修改IIS监听地址

- 运行WeBox.bat，选择[安装服务]，即可使用MYSQL+Nginx+PHP等服务

  - Nginx 默认监听地址为 0.0.0.0:80
  - MySQL 默认监听地址为 127.0.0.1:3306
  - PHP74 默认监听地址为 127.0.0.1:9701

### 组件列表

- Redis/3.2.100               https://github.com/dmajkic/redis/downloads

- MySQL/5.7.19                http://www.mysql.com/downloads/mysql

- Nginx/1.20.1                http://www.nginx.org/en/download.html

- PHP/7.4.20                  http://windows.php.net/download

- PHP-redis/5.3.4             http://pecl.php.net/package/redis
- PHP-xdebug/3.0.4            http://pecl.php.net/package/xdebug

### 常见问题

- 修改服务器参数
  - 请修改config目录内对应的文件

- 如何管理模块
  - config\*.php 表示已经启用的模块
  - config\*.dis 表示已经禁用的模块

- 如何建立新站点
  - 创建域名对应的网站目录，例如 webroot\org.example.www

- 如何管理MySQL
  - 服务器: 127.0.0.1
  - 帐户: root
  - 密码: 空

- 如何切换PHP版本
  - 确保服务未安装，否则请[卸载服务]
  - 编辑Nginx配置config\nginx\server\*，修改suffix配置
  - 运行WeBox.bat，选择[重建配置]，再选择[重启服务]

- 如何修改WEB根目录
  - 编辑runtime\config.php，修改[WB.WEB]的值
  - 建立[WB.WEB]对应目录，并移动原WEB到[WB.WEB]目录
  - 运行WeBox.bat，选择[重建配置]，再选择[重启服务]

- 如何修改MySQL数据目录
  - 编辑runtime\config.php，修改[WB.SQL]的值
  - 建立[WB.SQL]对应目录，并移动原MySQL数据到[WB.SQL]目录
  - 运行WeBox.bat，选择[重建配置]，再选择[重启服务]
