<h1 align="center">Halo, selamat datang di Perjadin App</h1>

<img align="center" src="http://ForTheBadge.com/images/badges/built-by-developers.svg">

### About this Repo?
Repositori ini dibuat sebagai obat untuk rasa penasaran saya dengan Laravel Inertia😄. Project ini dibuat dengan menggunakan LIVT Stack (Laravel, Inertia, Vue, Tailwind). Case pada project ini saya ambil dari salah satu contoh test case yang saya temukan di Linkedin. Semoga Perjadin App ini bisa bermanfaat entah sebagai referensi kode atau apapun. Tentunya masih ada banyak kekurangan dari yang saya buat, jika berkenan kamu bisa kontribusi dengan pull request pada repositori ini. Terimakasih.

------------

## 💻 Install

1. **Clone Repository**
```bash
git clone https://github.com/NichiNect/perjadin-livt.git perjadin-app
cd perjadin-app
composer install
npm install
copy .env.example .env
```

2. **Buka ```.env``` lalu ubah baris berikut sesuai dengan konfigurasi database**
```
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

3. **Instalasi website**
```bash
php artisan key:generate
php artisan migrate --seed
```

4. **Run the website**
Buka 2 terminal:
```bash
php artisan serve
```
```bash
npm run dev
```

### 👤 Default Account for testing
	
**Admin Default Account**
- Username: admin@gmail.com
- Password: thispassword

**SDM Default Account**
- Username : sdm1@gmail.com
- Password : thispassword

**Staff Default Account**
- Username : staff1@gmail.com
- Password : thispassword