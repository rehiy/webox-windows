@echo off

::系统设置
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

title WeBox 服务控制台 - v21.0611

cd /d %~dp0
set root=%~dp0
set root=%root:~0,-1%

::环境检测
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

call :check_manager
call :check_network

call :check_vc2019

::服务控制台
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:?
  cls && echo.
  set SPC=          -
  echo %SPC%----- WeBox-windows 服务控制台 -----------Multi-Service---
  echo %SPC%-                                                       --
  echo %SPC%-    1.安装服务       2.重启服务       3.卸载服务       --
  echo %SPC%-                                                       --
  echo %SPC%-    4.重建配置       5.环境测试                        --
  echo %SPC%-                                                       --
  echo %SPC%----------------------- By Http://www.rehiy.com ----------
  set Step=?
:??
  echo.
  set /p Step="请选择要执行的操作[1-5=>%Step%]: "
  if "%Step%"=="?" goto ?
  for /l %%i in (1,1,5) do (
    if "%Step%"=="%%i" (
      if %Step%==1 call :create
      if %Step%==2 call :module reboot
      if %Step%==3 call :module remove
      if %Step%==4 call :config
      if %Step%==5 call :module configtest
      call :check_error
      echo. && echo 操作完毕,稍后返回菜单...
      ping 127.1 -n 3 >nul
      goto ?
    )
  )
  echo 未定义操作: %Step%
  goto ??

:create
  call :config
  call :module create
  goto :EOF

:config
  echo.
  runtime\parser -n -f runtime\config.php
  goto :EOF

:module
  set "mod=config\module.ini"
  for /f "eol=; tokens=1,2" %%h in (%mod%) do (
    if "%1"=="create" call :check_service %%i
    if exist module\%%h.bat call module\%%h.bat %1
  )
  goto :EOF

::环境测试脚本
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:check_error
  if %errorlevel% neq 0 (
    if "%1" == "" (
      echo. && echo 操作失败,按任意键返回主菜单...
      pause >nul && goto ?
    )
    echo %1
  )
  goto :EOF

:check_manager
  echo. && echo 测试运行权限...
  >nul 2>&1 reg.exe query "HKU\S-1-5-19" || (
    echo CreateObject^("Shell.Application"^).ShellExecute "%~f0", "%1", "", "runas", 1 > "%TEMP%\sudo.vbs"
    echo CreateObject^("Scripting.FileSystemObject"^).DeleteFile^("%TEMP%\sudo.vbs"^) >> "%TEMP%\sudo.vbs"
    wscript "%TEMP%\sudo.vbs" && exit
  )
  goto :EOF

:check_network
  echo. && echo 测试网络环境...
  ping 127.0.0.1 -n 2 >nul || (
    echo 测试失败,请检查网络连接.
    goto check_network
  )
  goto :EOF

:check_service
  set "cs=%1"
  for /f "skip=3 tokens=4" %%i in ('sc query %cs%') do (
    if not ''=='%%i' (
      echo. && echo %cs%服务已经存在... 
      goto ??
    )
  )
  goto :EOF

:check_vc2019
  echo. && echo 测试VC运行库...
  dir %windir%\System32 | find /i /c "vcruntime140" >nul || (
    if "%1"=="retry" (
      echo 请手动安装VC++2015-2019运行库!!!
      pause >nul && exit
    ) else (
      echo 正在尝试安装VC运行库...
      start /w runtime\vc_redist_2019.exe /passive
      call :check_vc2019 retry
    )
  )
  goto :EOF