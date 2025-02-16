![excel](https://github.com/bailyboy021/Import-Export-Excel/blob/master/public/ie-excel.png?raw=true)

# Laravel Import Export Excel

Repository ini menyediakan contoh implementasi fitur import dan export data dari/ke file Excel menggunakan framework Laravel 11. Fitur ini sangat berguna untuk mengelola data dalam jumlah besar, memindahkan data antar sistem, atau membuat laporan dalam format yang mudah dibaca.


## Teknologi yang Digunakan

- **Framework**: Laravel 11 (PHP 8.2)
- **Database**: MySQL
- **ORM**: Eloquent
- **Bootstrap**: Bootstrap 5
- Maatwebsite/Excel

## Fitur

- **Import Data**: Mengimpor data dari file Excel ke dalam database Laravel.
- **Export Data**: Mengekspor data dari database Laravel ke dalam file Excel.
- **Validasi Data**: Melakukan validasi data sebelum diimpor untuk memastikan data yang masuk valid dan konsisten.
- **Format Excel**: Mendukung format file Excel .xls dan .xlsx.

## Instalasi

1.  Clone dari repository:

    ```bash
    git clone https://github.com/bailyboy021/Import-Export-Excel.git
    ```

2.  Pindah ke project directory:

    ```bash
    cd Import-Export-Excel
    ```

3.  Install Composer dependencies:

    ```bash
    composer install
    ```

4. Salin file .env.example menjadi .env lalu sesuaikan konfigurasi database dan Securing File Upload:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_excel
   DB_USERNAME=root
   DB_PASSWORD=

   ########################
   # Securing File Upload
   ########################
   FILE_WHITELIST='jpg|jpeg|png|svg|gif|webp|pdf|docx|doc|xlsx|xls|csv'
   FILE_BLACKLIST='php|phps|pht|phtm|phtml|pgif|shtml|htaccess|phar|inc|hphp|ctp|module|asp|aspx|config|ashx|asmx|aspq|axd|cshtm|cshtml|rem|soap|vbhtm|vbhtml|asa|cer|shtml|jsp|jspx|jsw|jsv|jspf|wss|action|cfm|cfml|cfc|dbm|swf|pl|cgi|yaws|xap|asax|exe|sh|bat|cmd|xml|txt|mf|bash|tar|tar.z|zip|rar'


5. Migrasi database dan seed data awal:

   ```bash
   php artisan migrate --seed

6. Jalankan server:

   ```bash
   php artisan serve

