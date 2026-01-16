# 🎟️ Bengkod Ticketing (TIXORA)

Halo! Ini project ticketing event yang aku bangun pakai **Laravel** untuk kebutuhan demo/presentasi: ada flow **Public → Buyer → Admin** .  
User bisa lihat event, pilih tiket, checkout (stok otomatis berkurang), lalu admin bisa kelola event/tiket/kategori dan monitoring transaksi.

---

## ✨ Highlight Fitur

### 👥 Public (Guest)
- Menampilkan **Home** berisi event featured
- List event + **search**
- **Filter kategori**
- Detail event: info lengkap + daftar tiket + kalkulasi total
- Checkout tersedia (butuh login)

### 🧑‍💻 Buyer (Authenticated User)
- Dashboard ringkasan: total order, total spent, tiket dibeli, upcoming orders
- Riwayat pembelian (orders) + detail order

### 🛠️ Admin (Role: admin)
- Dashboard KPI: total kategori/event/tiket/orders + revenue + recent activity
- CRUD Kategori (ada proteksi kalau kategori masih dipakai event)
- CRUD Event + upload poster (ada proteksi kalau event sudah punya transaksi)
- CRUD Tiket per Event (nested routes + proteksi kalau tiket sudah pernah dibeli)
- Monitoring transaksi (index + detail)

---

## 🧱 Tech Stack
- **Backend:** Laravel (Blade)
- **Auth:** Laravel Breeze
- **Frontend:** TailwindCSS + Vite
- **Database:** MySQL / SQLite
- **Storage:** Local + `storage:link` (untuk poster)

---

## 🔐 Akun Demo (Seeder)

Seeder: `database/seeders/TicketingSeeder.php`

- **Admin**
  - Email: `admin@tixora.test`
  - Password: `password`

- **Buyer**
  - Email: `buyer@tixora.test`
  - Password: `password`

> Tinggal jalankan migrate + seed, akun langsung siap dipakai demo.

---

## 🚀 Cara Jalanin di Local

### 1) Install dependency
```bash
composer install
npm install
```

### 2) Setup environment
**Windows (PowerShell):**
```powershell
copy .env.example .env
php artisan key:generate
```

### 3) Setup database
Pilih salah satu:

#### Opsi A — MySQL
Atur di `.env`:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticketing
DB_USERNAME=root
DB_PASSWORD=


Lalu:
```bash
php artisan migrate --seed
```

### 4) Storage symlink (poster)
```bash
php artisan storage:link
```

### 5) Run aplikasi
Jalankan 2 terminal:

**Terminal 1**
```bash
php artisan serve
```

**Terminal 2**
```bash
npm run dev
```

Akses:
- Public: `http://127.0.0.1:8000/`
---

## 🧭 Peta Route 

### Public
- `GET /` → Home
- `GET /events` → List event
- `GET /events/{event}` → Detail event
- `POST /events/{event}/checkout` → Checkout (auth)

### Buyer
- `GET /dashboard` → Dashboard buyer
- `GET /orders` → Riwayat pembelian
- `GET /orders/{order}` → Detail order

### Admin
- `GET /admin` → Dashboard admin
- `CRUD /admin/kategori`
- `CRUD /admin/events`
- `CRUD /admin/events/{event}/tikets`
- `GET /admin/orders` → Daftar transaksi
- `GET /admin/orders/{order}` → Detail transaksi

---

## ✅ Use Case Coverage 

| Use Case | Status |
|---|---|
| Cari event + filter kategori | ✅ |
| Lihat detail event + daftar tiket | ✅ |
| Checkout + pengurangan stok | ✅ |
| Riwayat pembelian (buyer) | ✅ |
| Admin kelola kategori | ✅ |
| Admin kelola event + poster | ✅ |
| Admin kelola tiket per event | ✅ |
| Admin monitoring transaksi | ✅ |

---

