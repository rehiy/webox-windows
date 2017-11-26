@echo off

::设置内部变量
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

setlocal

set mroot=%~dp0
set mroot=%mroot:~0,-1%
set mconf=%mroot:module=deploy%\php.ini

set xroot=%mroot:~0,-13%
set xnssm=%xroot%\runtime\nssm.exe
set xconf=%mroot:module=deploy%\xxfpm.ini

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

title Webox.xServer 服务控制台

echo.
echo 暂不支持独立控制台模式...
pause >nul && exit


::模块管理标准接口
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:app_runtime
  set scName=Webox-PHP71
  goto :EOF

:app_create
  if not exist "%mconf%" (
    echo. && echo 错误: PHP71配置文件不存在...
    goto :EOF
  )
  echo. && echo 正在安装PHP71服务...
  %xnssm% install %scName% %mroot%\xxfpm\fpm71.exe
  %xnssm% set %scName% DisplayName "Webox PHP71 Server" >nul
  for /f "eol=; tokens=1,2,3,4" %%h in (%xconf%) do (
    %xnssm% set %scName% AppParameters \"%mroot%\php71.exe -c %mconf%\" -i %%h -p %%i -n %%j >nul
    break
  )
  call :app_start
  goto :EOF

:app_remove
  call :app_stop
  echo. && echo 正在卸载PHP71服务...
  %xnssm% remove %scName% confirm
  goto :EOF

:app_start
  echo. && echo 正在启动PHP71服务...
  %xnssm% start %scName%
  call :app_progress
  goto :EOF

:app_stop
  echo. && echo 正在停止PHP71服务...
  %xnssm% stop %scName%
  taskkill /T /F /IM php5* >nul 2>nul
  goto :EOF

:app_reboot
  echo. && echo 正在重启PHP71服务...
  %xnssm% stop %scName%
  taskkill /T /F /IM php5* >nul 2>nul
  %xnssm% start %scName%
  call :app_progress
  goto :EOF

:app_progress
  echo. && echo 正在检查PHPye进程...
  ping 127.0.0.1 -n 1 >nul
  tasklist | findstr php71.exe >nul
  if %errorlevel% neq 0 (
    echo 错误: PHP71启动失败
  )
  goto :EOF

:app_configure
  goto :EOF

:app_configtest
  goto :EOF
