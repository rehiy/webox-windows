@echo off

cd /d %~dp0

echo. && echo 取消IIS侦听 0.0.0.0 
httpcfg delete iplisten -i 0.0.0.0

echo. && echo 设定IIS侦听 127.1.1.1
httpcfg set iplisten -i 127.1.1.1

echo. && echo 重启服务IIS服务
net stop http /y
net start w3svc

echo. && echo 查看TCP端口状态
netstat -a -n -p tcp

ping 127.1 -n 10 > nul
