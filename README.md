<div align="center">
  <img src="public/assets/images/logo-rm.png" alt="Rizki Mandiri Logo" width="200" style="margin-bottom: 20px;">

  # RIZKI MANDIRI (RM) System
  **Sistem Manajemen Inventaris & Point of Sale (POS) Modern**

  ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
  ![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

  Sistem terintegrasi untuk mengelola inventaris, penjualan kasir, pembelian grosir, pencatatan pengeluaran, dan manajemen multi-cabang dengan antarmuka yang modern, responsif, dan mudah digunakan.

  ---
</div>

## ✨ Fitur Utama (Features)

Sistem ini dirancang untuk mempermudah operasional bisnis harian dengan fitur-fitur komprehensif:

*   **🏢 Manajemen Multi-Cabang**: Mendukung kontrol data dan pengeluaran per cabang. Super Admin dapat memantau seluruh cabang, sementara Admin hanya dapat mengelola data cabangnya sendiri.
*   **📦 Manajemen Inventaris (Inventory Ledger)**: 
    *   Sistem pencatatan keluar-masuk barang yang detail (Saldo Awal, Pembelian, Penjualan, Penyesuaian).
    *   Fitur **"Input Cepat/Banyak (Array Input)"** untuk mencatat belanjaan dalam jumlah besar sekaligus dengan sekali simpan.
    *   Validasi stok otomatis untuk mencegah penjualan barang yang kosong atau harga jual di bawah harga modal beli.
*   **🛒 Point of Sale (POS)**: Modul kasir terintegrasi untuk mencetak struk dan merekapitulasi penjualan harian. Mendukung fitur diskon dan pajak (PPN).
*   **💰 Manajemen Pengeluaran**: Pencatatan beban harian/operasional (Operational Expenses) yang terpisah secara rapi menggunakan filter tanggal dan cabang.
*   **🧾 Invoicing & Penagihan Customer**: Pembuatan Faktur Penjualan (Customer Invoice), Proforma Invoice, Cetak Tanda Terima (Delivery Order / Surat Jalan), hingga manajemen Draft dan E-Sign dokumen. 
*   **📱 Notifikasi Terintegrasi**: Memiliki format template khusus pengiriman resi atau tagihan via WhatsApp & Email (Export Invoice).
*   **🎨 Desain UI/UX Modern**: Mengadopsi _Glassmorphism_, tata letak _Mobile First_, dan dasbor interaktif dengan grafik (*charts*).

## 🚀 Teknologi yang Digunakan (Tech Stack)

*   **Backend:** Laravel Framework (PHP)
*   **Frontend:** Blade Template, jQuery, JavaScript, CSS kustom
*   **Database:** MySQL (MariaDB)
*   **Styling:** Bootstrap 5, FontAwesome, Select2 (untuk *dropdown* cerdas)
*   **Templating Engine:** Velzon Admin Dashboard Theme (disesuaikan penuh untuk *branding* RM System)

## 🛠️ Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan RM System di komputer lokal Anda:

### Persyaratan Sistem
*   PHP ^8.1
*   Composer
*   MySQL/MariaDB Database Server
*   Node.js & NPM (untuk *compile asset* jika diperlukan)

### Langkah Instalasi

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/username-anda/rm_system.git
   cd rm_system
   ```

2. **Instal dependensi PHP & Node:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Salin file konfigurasi *environment*:**
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database:**
   Buka file `.env` dan sesuaikan nama dan kredensial database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rm_system
   DB_USERNAME=root
   DB_PASSWORD=rahasia
   ```

5. **Generate Application Key & Migrate Database:**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
   *(Perhatikan: `--seed` akan memasukkan kerangka dasar seperti hak akses Role/Super Admin dan cabang pusat).*

6. **Jalankan Server Lokal:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=1010
   ```
   *Buka browser dan akses:* `http://localhost:1010`

---

## 🔒 Otentikasi & Hak Akses (Role Base Access)

*Aplikasi ini dilindungi oleh otentikasi ketat.*
- **Superadmin:** Menguasai pengelolaan *user*, pembuatan cabang baru, penghapusan data master, dan rekapitulasi pelaporan antar cabang.
- **Admin:** Mengelola inventaris, membuat invoice, mencatat kasir cabang bersangkutan tanpa bisa mengetahui omzet cabang lain.

---

<p align="center">
  Dibuat dengan ❤️ untuk operasional <b>Rizki Mandiri</b>.
</p>
