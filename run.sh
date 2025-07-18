# ตั้งค่า .env
cp .env.example .env
php artisan key:generate

# สร้างฐานข้อมูล
# (สร้างฐานข้อมูลชื่อ pr_notify ใน MySQL ก่อน)
mysql -u root -p pr_notify < pr_notify.sql

# ทำลิงค์สำหรับไฟล์
php artisan storage:link

# ทดสอบรัน
php artisan serve