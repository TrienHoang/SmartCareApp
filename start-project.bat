@echo off

echo ===============================
echo 🧰 Đang bật Laragon...
echo ===============================
start "" "C:\laragon\laragon.exe"

echo ===============================
echo 🚀 Đang chạy Laravel Server...
echo ===============================
start cmd /k php artisan serve

echo ===============================
echo 🪄 Đang chạy npm run dev...
echo ===============================
start cmd /k npm run dev

echo ===============================
echo 🌐 Đang mở trình duyệt...
echo ===============================
start http://127.0.0.1:8000

pause
