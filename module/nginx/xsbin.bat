@echo off

::设置内部变量
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

setlocal

set mroot=%~dp0
set mroot=%mroot:~0,-1%
set xroot=%mroot:~0,-13%

set mconf=%mroot:module=deploy%\nginx.conf

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
  set xsbin=%mroot%\xsngx.exe
  set scName=Webox-Nginx
  set scPath=%mroot%
  set scExec=%~nx0
  set scPara=app_program
  goto :EOF

:app_create
  echo. && echo 正在安装Nginx服务...
  sc create %scName% binPath= "%xsbin%" start= auto >nul
  reg add "HKLM\SYSTEM\CurrentControlSet\Services\%scName%\Parameters" /v "AppDirectory" /d "%scPath%" >nul
  reg add "HKLM\SYSTEM\CurrentControlSet\Services\%scName%\Parameters" /v "Application" /d "%scExec%" >nul
  reg add "HKLM\SYSTEM\CurrentControlSet\Services\%scName%\Parameters" /v "AppParameters" /d "%scPara%" >nul
  sc description %scName% "Webox Web Server" >nul
  call :app_start
  goto :EOF

:app_remove
  call :app_stop
  echo. && echo 正在卸载Nginx服务...
  sc delete %scName% >nul 2>nul
  goto :EOF

:app_start
  echo. && echo 正在启动Nginx服务...
  net start %scName% >nul 2>nul
  call :app_progress
  goto :EOF

:app_stop
  echo. && echo 正在停止Nginx服务...
  net stop %scName% >nul 2>nul
  taskkill /T /F /IM nginx.exe >nul 2>nul
  goto :EOF

:app_reboot
  echo. && echo 正在重启Nginx服务...
  net stop %scName% >nul 2>nul
  taskkill /T /F /IM nginx.exe >nul 2>nul
  net start %scName% >nul 2>nul
  call :app_progress
  goto :EOF

:app_progress
  echo. && echo 正在检查Nginx进程...
  ping 127.0.0.1 -n 5 >nul
  tasklist|find /i "nginx.exe" >nul
  if %errorlevel% neq 0 (
    echo 错误: Nginx启动失败
  )
  goto :EOF

:app_configure
  goto :EOF

:app_configtest
  call %mroot%\nginx.exe -p "%mroot%" -c "%mconf%" -t
  goto :EOF

:app_program
  call :check_network
  start %mroot%\nginx.exe -p "%mroot%" -c "%mconf%"
  goto :EOF


::环境测试脚本
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

::检测本地网络是否可用
:check_network
  echo. && echo 正在测试网络环境...
  ping 127.0.0.1 -n 2 >nul || (
    echo 测试失败,请检查网络连接
    goto check_network
  )
  goto :EOF
