# PHP Photo Gallery (Simple)

Fitur:
- Register user & login
- Admin dashboard
- CRUD Kategori
- CRUD Foto (upload gambar ke folder `/uploads`)
- Filter galeri berdasarkan kategori & pencarian

## Cara Menjalankan (Laragon/XAMPP)
1. Buat database MySQL bernama `photo_gallery`.
2. Import file `database.sql`.
3. Atur koneksi di `config.php` (DB_HOST, DB_NAME, DB_USER, DB_PASS, BASE_URL).
   - Jika project diletakkan di `C:\laragon\www\gallery`, set `BASE_URL` ke `/gallery`.
4. Pastikan folder `uploads` writable.
5. Buka `http://localhost/gallery/public/index.php`.
6. Register user baru. Untuk membuat admin:
   - Dapatkan hash password (PHP): `password_hash('admin123', PASSWORD_DEFAULT)`
   - Jalankan SQL insert admin sesuai instruksi di `database.sql`.
   - Atau ubah kolom `role` user Anda menjadi `admin` via SQL.

## Catatan Keamanan
- Contoh ini minimalis: sudah pakai prepared statements & password_hash.
- Tambahkan validasi ukuran file & batas maksimal upload di php.ini jika diperlukan.
- Untuk produksi, tambahkan rate limiting, CSRF sudah ada, dan sanitasi yang lebih ketat.

📊 Flowchart (alur sistem)
```
                ┌───────────────────────┐
                │       User/Admin      │
                └───────────┬───────────┘
                            │
               ┌────────────┴────────────┐
               │                         │
     ┌─────────▼────────┐       ┌────────▼─────────┐
     │     User Area     │       │   Admin Login    │
     └─────────┬────────┘       └────────┬─────────┘
               │                         │
   ┌───────────▼───────────┐             │
   │  Lihat Galeri Publik  │             │
   └───────────┬───────────┘             │
               │                         │
   ┌───────────▼───────────┐             │
   │  Filter Foto &        │             │
   │  Pilih Kategori       │             │
   └───────────────────────┘             │
                                         │
                               ┌─────────▼─────────┐
                               │   Dashboard Admin │
                               └─────────┬─────────┘
                                         │
        ┌──────────────────────┬─────────┴─────────┬─────────────────────┐
        │                      │                   │                     │
┌───────▼───────┐     ┌────────▼───────┐   ┌───────▼────────┐   ┌───────▼────────┐
│ Tambah Foto   │     │ Edit Foto      │   │ Hapus Foto     │   │ Kelola Kategori │
└───────┬───────┘     └────────┬───────┘   └───────┬────────┘   └───────┬────────┘
        │                      │                   │                    │
        └──────────────────────┴───────────────────┴────────────────────┘
                                │
                     ┌──────────▼───────────┐
                     │   Lihat Statistik    │
                     │ (Jumlah Foto/Kategori)│
                     └───────────────────────┘
```