@echo off

::设置内部变量
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

setlocal

set mroot=%~dp0
set mroot=%mroot:~0,-1%
set xroot=%mroot:~0,-13%

set mconf=%mroot:module=deploy%\my.ini

call :app_runtime


::外部调用模式
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if not "%1" == "" (
  call :%1
  goto :EOF
  exit
)


::独立控制台模式
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

title Webox.xServer 服务控制台

echo.
echo 暂不支持独立控制台模式...
pause >nul && exit


::模块管理标准接口
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:app_runtime
  set scName=Webox-MySQL
  goto :EOF

:app_create
  if not exist "%mconf%" (
    echo. && echo 错误: 配置文件不存在...
    goto :EOF
  )
  echo. && echo 正在安装MySQL服务...
  %mroot%\bin\mysqld.exe --install %scName% --defaults-file="%mconf%"
  sc description %scName% "Webox MySQL Server" >nul
  call :app_start
  goto :EOF

:app_remove
  echo. && echo 正在卸载MySQL服务...
  net stop %scName% >nul 2>nul
  sc delete %scName% >nul 2>nul
  goto :EOF

:app_start
  echo. && echo 正在启动MySQL服务...
  net start %scName% >nul 2>nul
  call :app_progress
  goto :EOF

:app_stop
  echo. && echo 正在停止MySQL服务...
  net stop %scName% >nul 2>nul
  goto :EOF

:app_reboot
  echo. && echo 正在重启MySQL服务...
  net stop %scName% >nul 2>nul
  net start %scName% >nul 2>nul
  call :app_progress
  goto :EOF

:app_progress
  echo. && echo 正在检查MySQL进程...
  ping 127.0.0.1 -n 1 >nul
  tasklist|find /i "mysqld.exe" >nul
  if %errorlevel% neq 0 (
    echo 错误: MySQL启动失败
  )
  goto :EOF

:app_program
  goto :EOF

:app_configure
  goto :EOF

:app_configtest
  goto :EOF
