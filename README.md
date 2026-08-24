# SPK Beasiswa Cerdas Humanis — Metode MOORA
## Laravel 10 + MySQL + PHP 8.1+

---

## ⚡ Cara Instalasi (Laragon / XAMPP / WAMP)

### 1. Letakkan folder di root server
```
C:\laragon\www\spk-beasiswa\   (Laragon)
C:\xampp\htdocs\spk-beasiswa\  (XAMPP)
```

### 2. Install dependencies
```bash
cd spk-beasiswa
composer install
```

### 3. Copy & edit file environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Edit `.env` — sesuaikan database
```env
APP_URL=http://spk-beasiswa.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_beasiswa
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat database di MySQL
```sql
CREATE DATABASE spk_beasiswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
**Atau:** import file `spk_beasiswa.sql` langsung (sudah include data demo)

### 6. Jalankan migrasi + seeder (jika tidak pakai SQL import)
```bash
php artisan migrate --seed
php artisan storage:link
```

### 7. Akses di browser
```
http://spk-beasiswa.test   (Laragon auto-detect)
http://localhost/spk-beasiswa/public  (XAMPP)
```

---

## 👤 Akun Demo

| Role  | Username     | Password |
|-------|-------------|----------|
| BAA   | admin_baa   | password |
| BAPSI | admin_bapsi | password |
| LKM   | admin_lkm   | password |

---

## 📋 Syarat Sistem

- PHP >= 8.1
- MySQL >= 5.7 / MariaDB >= 10.3
- Composer
- Extension PHP: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `bcmath`

---

## 🗄️ Import Database Langsung (Rekomendasi)

Jika ada file `spk_beasiswa.sql`:
```bash
mysql -u root -p spk_beasiswa < spk_beasiswa.sql
```
Atau via phpMyAdmin → Import → pilih `spk_beasiswa.sql`

---

## 🏗️ Struktur MVC

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php
│   ├── BAA/   → DashboardController, CalonMahasiswaController, DokumenController
│   ├── BAPSI/ → DashboardController, VerifikasiController, KriteriaController,
│   │             KuotaController, MooraController, SubKriteriaController
│   └── LKM/   → DashboardController, RankingController, KeputusanController
├── Middleware/RoleMiddleware.php
├── Models/    → User, Prodi, CalonMahasiswa, Dokumen, Kriteria,
│                SubKriteria, Nilai, HasilMoora, Keputusan
└── Services/MooraService.php   ← INTI PERHITUNGAN MOORA
```

---

## 📊 Metode MOORA

| Kode | Kriteria           | Tipe    | Bobot |
|------|--------------------|---------|-------|
| C1   | Kondisi Orang Tua  | Benefit | 30%   |
| C2   | Penghasilan OT     | Cost    | 35%   |
| C3   | Tagihan Listrik    | Cost    | 20%   |
| C4   | Tanggungan Anak    | Benefit | 15%   |

**Formula Yi** = Σ(benefit × weight) − Σ(cost × weight)

---

*SPK Beasiswa Cerdas Humanis — Laravel 10 — Metode MOORA*
