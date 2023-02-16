# Test CMS 👨🏻‍💻

## Instalasi

### API 🖥️
1. buka folder api dengan terminal
2. ketik `composer install` atau `composer update`
3. copy file .env.example ke .env, dan setup env nya
4. jalankan command line `php artisan migrate --seed`
5. jalankan command line `php artisan storage:link`
6. aplikasi siap digunakan, untuk menjalankannya bisa dengan menjalankan `php artisan serve`, atau setting vhost di nginx atau apache

for default user
<table>
  <tr>
    <td>Username</td>
    <td>Password</td>
  </tr>
  <tr>
    <td>admin@example.local</td>
    <td>admin@example.local</td>
  </tr>
</table>

### Frontend 💻
1. buka folder ui dengan terminal
2. ketik `npm install`
3. lalu copy .env.example ke .env, setting api url dengan mengisi VITE_API_URL
4. aplikasi siap digunakan, bisa dijalankan dengan `npm run dev`

## Teknologi yang digunakan

### API
untuk api menggunakan php dan menggunakan framework laravel, ditambah dengan library laravel buatan saya sendiri [cuytamvan/base-pattern-laravel](https://github.com/cuytamvan/base-pattern-laravel) untuk repository pattern. 

### UI
Untuk frontend atau ui saya menggunakan vue js, dan menggunakan typescript, scss, tailwindcss. Semua komponent saya buat sendiri, 
