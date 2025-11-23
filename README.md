<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


Siap Bang\! Ini panduan **"Anti Ribet"** yang bisa langsung kamu copy-paste buat dikirim ke temen kamu atau ditaruh di `README.md` repository GitHub kamu.

Panduan ini pakai cara **Laravel Standard (Migration + Seeder)**, jadi temen kamu gak perlu import file SQL manual. Cukup ketik perintah, database langsung jadi + ada isinya.

-----

### 📄 Panduan Setup Project (Karang Taruna Web)

Woy fren\! 👋 Ini langkah-langkah buat jalanin project ini di laptop lu (khususnya buat database **PostgreSQL**).

#### 1️⃣ Persiapan Awal

Pastikan di laptop lu udah terinstall:

  * **PHP** (Minimal v8.2)
  * **Composer**
  * **PostgreSQL** (Bisa via Laragon atau install manual)
  * **Git**

#### 2️⃣ Install Dependencies

Buka terminal di folder project ini, terus ketik:

```bash
composer install
npm install
```

#### 3️⃣ Atur Environment (.env)

Duplikat file `.env.example` dan ubah namanya jadi `.env`.
Atau ketik perintah ini di terminal:

```bash
cp .env.example .env
```

Terus buka file **`.env`**, cari bagian Database, dan ubah jadi kayak gini (sesuaikan sama settingan Postgres lu):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=karang-taruna-web  <-- Nama DB bebas, asal sama dgn yg dibuat nanti
DB_USERNAME=postgres           <-- Default postgres biasanya 'postgres'
DB_PASSWORD=password_lu        <-- Masukin password postgres lu
```

#### 4️⃣ Generate Key

Biar Laravel-nya aman, generate key baru:

```bash
php artisan key:generate
```

#### 5️⃣ Buat Database Kosong

Buka **pgAdmin** atau **HeidiSQL** (atau terminal psql), terus bikin database baru sesuai nama di `.env` tadi (contoh: `karang-taruna-web`).

*Kalau pake terminal:*

```bash
createdb karang-taruna-web
```

#### 6️⃣ Migrasi & Isi Data (The Magic Step ✨)

Ini langkah paling penting. Perintah ini bakal bikin semua tabel otomatis dan ngisi data dummy (Admin, Berita, Galeri).

Ketik di terminal:

```bash
php artisan migrate:fresh --seed
```

*Kalau sukses, bakal muncul tulisan ijo-ijo "Seeding database completed successfully".*

#### 7️⃣ Link Storage (Biar Gambar Muncul)

Biar foto-foto yang diupload kebaca sama browser:

```bash
php artisan storage:link
```

#### 8️⃣ Jalankan Server

Nyalahin server Laravel & Vite (buka 2 terminal):

**Terminal 1 (Backend):**

```bash
php artisan serve
```

**Terminal 2 (Frontend Assets):**

```bash
npm run dev
```

#### 🚀 Login Admin

Buka browser: `http://127.0.0.1:8000/admin/login`

  * **Username:** `admin`
  * **Password:** `admin123`

-----

**Catatan buat Temen:**
Kalau ada error *“could not find driver”*, itu tandanya ekstensi `pgsql` di PHP lu belum nyala.

  * Buka `php.ini`.
  * Cari `;extension=pdo_pgsql` dan `;extension=pgsql`.
  * Hapus titik koma (`;`) di depannya.
  * Save & Restart server.

Gas ngoding\! 🔥
