@echo off

::设置内部变量
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

setlocal

set xroot=%~dp0
set xroot=%xroot:~0,-7%
set xnssm=%xroot%\runtime\nssm.exe

set mroot=%xroot%\module\%~n0
set mconf=%xroot%\config\%~n0\my.ini

call :app_runtime


::外部调用模式
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if not "%1" == "" (
  call :app_%1
  goto :EOF
  exit
)


::独立控制台模式
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

title %scName% 服务控制台

echo.
echo 暂不支持独立控制台模式...
pause >nul && exit


::模块管理标准接口
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:app_runtime
  set scName=WeBox-MySQL
  goto :EOF

:app_create
  if not exist "%mconf%" (
    echo. && echo 错误: MySQL配置文件不存在...
    goto :EOF
  )
  echo. && echo 正在安装MySQL服务...
  %xnssm% install %scName% %mroot%\bin\mysqld.exe
  %xnssm% set %scName% DisplayName "WeBox MySQL Server" >nul
  %xnssm% set %scName% AppParameters --defaults-file=%mconf% >nul
  call :app_start
  goto :EOF

:app_remove
  call :app_stop
  echo. && echo 正在卸载MySQL服务...
  %xnssm% remove %scName% confirm
  goto :EOF

:app_start
  echo. && echo 正在启动MySQL服务...
  %xnssm% start %scName%
  call :app_progress
  goto :EOF

:app_stop
  echo. && echo 正在停止MySQL服务...
  %xnssm% stop %scName%
  goto :EOF

:app_reboot
  echo. && echo 正在重启MySQL服务...
  %xnssm% restart %scName%
  call :app_progress
  goto :EOF

:app_progress
  echo. && echo 正在检查MySQL进程...
  ping 127.0.0.1 -n 1 >nul
  tasklist | findstr mysqld.exe >nul
  if %errorlevel% neq 0 (
    echo 错误: MySQL启动失败
  )
  goto :EOF

:app_configure
  goto :EOF

:app_configtest
  goto :EOF
