@echo off
REM ===================================
REM Rebuild PHP Container with MySQL Support
REM ===================================

echo Stopping containers...
docker-compose down

echo.
echo Building PHP container with MySQL extensions...
docker-compose build php

echo.
echo Starting all containers...
docker-compose up -d

echo.
echo Waiting for services to start...
timeout /t 10

echo.
echo ===================================
echo Containers rebuilt successfully!
echo ===================================
echo.
echo Test database connection at: http://localhost:8080/test-db.php
echo.
pause
