# ติดตั้ง Laravel เวอร์ชั่นล่าสุด
composer create-project laravel/laravel pr-notify

# เข้าไปในโฟลเดอร์โปรเจค
cd pr-notify

# ติดตั้ง Laravel Breeze สำหรับระบบ Authentication
composer require laravel/breeze --dev

# ติดตั้ง packages ที่จำเป็น
composer require spatie/laravel-permission
composer require yajra/laravel-datatables-oracle
composer require laravelcollective/html
composer require intervention/image

# ติดตั้ง Breeze (Authentication scaffolding)
php artisan breeze:install

# ติดตั้ง npm dependencies
npm install

# ติดตั้ง jQuery และ FullCalendar
npm install jquery @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction bootstrap@5 @popperjs/core

# สร้าง assets
npm run dev