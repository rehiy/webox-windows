@echo off

::ϵͳ����
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

title WeBox �������̨ - v7.1207

cd /d %~dp0
set root=%~dp0
set root=%root:~0,-1%

::�������
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

call :check_manager
call :check_network

call :check_vc2012
call :check_vc2013
call :check_vc2015

::�������̨
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:?
  cls && echo.
  set SPC=          -
  echo %SPC%----- WeBox-windows �������̨ -----------Multi-Service---
  echo %SPC%-                                                       --
  echo %SPC%-    1.��װ����       2.��������       3.ж�ط���       --
  echo %SPC%-                                                       --
  echo %SPC%-    4.�ؽ�����       5.��������                        --
  echo %SPC%-                                                       --
  echo %SPC%----------------------- By Http://www.rehiy.com ----------
  set Step=?
:??
  echo.
  set /p Step="��ѡ��Ҫִ�еĲ���[1-5=>%Step%]: "
  if "%Step%"=="?" goto ?
  for /l %%i in (1,1,5) do (
    if "%Step%"=="%%i" (
      if %Step%==1 call :create
      if %Step%==2 call :module reboot
      if %Step%==3 call :module remove
      if %Step%==4 call :config
      if %Step%==5 call :module configtest
      call :check_error
      echo. && echo �������,�Ժ󷵻ز˵�...
      ping 127.1 -n 3 >nul
      goto ?
    )
  )
  echo δ�������: %Step%
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
  set "mod=deploy\module.ini"
  for /f "eol=; tokens=1,2" %%h in (%mod%) do (
    if "%1"=="create" call :check_service %%i
    if exist module\%%h.bat call module\%%h.bat %1
  )
  goto :EOF

::�������Խű�
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:check_error
  if %errorlevel% neq 0 (
    if "%1" == "" (
      echo. && echo ����ʧ��,��������������˵�...
      pause >nul && goto ?
    )
    echo %1
  )
  goto :EOF

:check_manager
  echo. && echo ��������Ȩ��...
  >nul 2>&1 reg.exe query "HKU\S-1-5-19" || (
    echo CreateObject^("Shell.Application"^).ShellExecute "%~f0", "%1", "", "runas", 1 > "%TEMP%\sudo.vbs"
    echo CreateObject^("Scripting.FileSystemObject"^).DeleteFile^("%TEMP%\sudo.vbs"^) >> "%TEMP%\sudo.vbs"
    wscript "%TEMP%\sudo.vbs" && exit
  )
  goto :EOF

:check_network
  echo. && echo �������绷��...
  ping 127.0.0.1 -n 2 >nul || (
    echo ����ʧ��,������������.
    goto check_network
  )
  goto :EOF

:check_service
  set "cs=%1"
  for /f "skip=3 tokens=4" %%i in ('sc query %cs%') do (
    if not ''=='%%i' (
      echo. && echo %cs%�����Ѿ�����... 
      goto ??
    )
  )
  goto :EOF

:check_vc2012
    echo. && echo ����vc���п�...
    dir %windir%\System32 | find /i /c "msvcr110.dll" >nul || (
        if "%1"=="retry" (
            echo ���ֶ���װVC++2012���п�!!!
            pause >nul && exit
        ) else (
            echo ���ڳ��԰�װVC���п�...
            start /w runtime\vc_redist_2012.exe /passive
            call :check_vc2012 retry
        )
    )
    cls && goto :EOF


:check_vc2013
    echo. && echo ����vc���п�...
    dir %windir%\System32 | find /i /c "msvcr120.dll" >nul || (
        if "%1"=="retry" (
            echo ���ֶ���װVC++2013���п�!!!
            pause >nul && exit
        ) else (
            echo ���ڳ��԰�װVC���п�...
            start /w runtime\vc_redist_2013.exe /passive
            call :check_vc2012 retry
        )
    )
    cls && goto :EOF

:check_vc2015
  echo. && echo ����VC���п�...
  dir %windir%\System32 | find /i /c "vcruntime140" >nul || (
    if "%1"=="retry" (
      echo ���ֶ���װVC++2015���п�!!!
      pause >nul && exit
    ) else (
      echo ���ڳ��԰�װVC���п�...
      start /w runtime\vc_redist_2015.exe /passive
      call :check_vc2015 retry
    )
  )
  goto :EOF
