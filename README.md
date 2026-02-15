# 🏍️ POS Bengkel – Sistem Manajemen Bengkel Motor Modern

**POS Bengkel** adalah aplikasi Point of Sale (POS) khusus untuk bengkel motor, dibangun menggunakan **Laravel & Livewire**, yang dirancang untuk mempermudah pengelolaan bengkel secara **real-time**. Dengan POS ini, pemilik bengkel bisa memantau performa bisnis, mengelola transaksi, hingga mengatur stok sparepart tanpa ribet.

---

## 🚀 Fitur Utama

### 1️⃣ Dashboard Performa (Real-time Analytics)

- **Ringkasan Laba & Pendapatan**: Pantau **laba bersih**, **total pendapatan**, dan **pengeluaran bulanan** secara instan.
- **Statistik Harian Kendaraan**: Tahu jumlah kendaraan yang diservis setiap hari.
- **Grafik Tren Keuntungan**: Visualisasi keuntungan **6 bulan terakhir** untuk pengambilan keputusan cerdas.
- **Monitoring Status Pengerjaan**: Lihat kendaraan yang sedang diproses agar antrean lebih tertata.

### 2️⃣ Manajemen Data Master

- **Pelanggan & Kendaraan**: Simpan data pemilik kendaraan beserta detail unitnya.
- **Inventaris Sparepart**: Kelola stok suku cadang secara mudah.
- **Daftar Layanan / Jasa**: Atur harga layanan seperti **Tune Up**, **Ganti Oli**, dan lainnya.

### 3️⃣ Modul Transaksi & Layanan

- **Input Jasa Service**: Catat teknisi bertugas, jenis servis, dan keluhan pelanggan (misal: “Pasang Turbo”).
- **Pemakaian Sparepart Otomatis**: Sistem menghitung biaya total berdasarkan sparepart yang digunakan.
- **Status Pengerjaan Dinamis**: Lacak status unit, **Proses** atau **Selesai**, secara real-time.
- **Pembayaran Fleksibel**: Mendukung **Lunas** atau **Belum Lunas (Kasbon)**.

### 4️⃣ SDM & Operasional

- **Manajemen Teknisi**: Kelola data mekanik/bengkel dengan mudah.
- **Laporan Komprehensif**: Akses cepat ke laporan keuangan & aktivitas operasional.
- **Status Bengkel Real-time**: Sidebar menampilkan status **Buka/Tutup** bengkel.

---

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel

---

## 📸 Tampilan Aplikasi

### **Dashboard Performa**

Menampilkan statistik laba, pendapatan, pengeluaran, serta grafik tren bulanan.
![Dashboard Bengkel](screenshots/dashboard.jpeg)

### **Form Transaksi Service**

Mencatat data kendaraan, teknisi, jenis layanan, penggunaan sparepart, dan status pembayaran.
![Transaksi Service](screenshots/transaksi.jpeg)

---

## ⚡ Cara Instalasi

1. **Clone repository**

```bash
git clone https://github.com/teguhafrianda/Pos_Bengkel.git
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Setup Environment**

```bash
cp .env.example .env
# Sesuaikan database
```

4. **Run Migration & Seed**

```bash
php artisan migrate --seed
```

5. **Jalankan Server**

```bash
php artisan serve
```
