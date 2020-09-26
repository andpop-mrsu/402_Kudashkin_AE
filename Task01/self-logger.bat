@echo off
setlocal enabledelayedexpansion
chcp 65001>nul

echo CREATE TABLE IF NOT EXISTS log(username varchar(10), datetime text default current_timestamp); | sqlite3 log.db
echo INSERT INTO log values('%USERNAME%', datetime('now', 'localtime')); | sqlite3 log.db

echo Имя программы: %~nx0
echo|<nul set /p="Количество запусков: "
echo SELECT COUNT(*) FROM log; | sqlite3 log.db
echo|<nul set /p="Первый запуск: "
echo SELECT datetime FROM log ORDER BY datetime limit 1; | sqlite3 log.db
echo SELECT * FROM log; | sqlite3 -table log.db

pause
