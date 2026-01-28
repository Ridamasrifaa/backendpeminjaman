# Sistem Peminjaman dengan Role-Based Access Control

Sistem login dengan 3 tipe user berbeda yang memiliki akses data berbeda.

## Setup Database

Sebelum menggunakan sistem ini, jalankan file setup:
1. Buka browser dan akses: `http://localhost/backendpeminjaman/setup_database.php`
2. Database akan otomatis dibuat

## 3 Tipe User yang Tersedia

### 1. **ANGGOTA** (Member)
- Dapat melihat: Peminjaman pribadi mereka saja
- Dapat melakukan: Mengajukan permintaan peminjaman baru
- Menu:
  - Peminjaman Saya
  - Ajukan Peminjaman

### 2. **SEKRETARIS** (Secretary)
- Dapat melihat: Semua peminjaman dari semua pengguna
- Dapat melakukan: Melihat data user dan peminjaman menunggu persetujuan
- Menu:
  - Semua Peminjaman
  - Menunggu Persetujuan
  - Data User

### 3. **KETUA** (Leader)
- Dapat melihat: SEMUA data dan laporan
- Dapat melakukan: Menyetujui/menolak peminjaman, mengelola user, membuat laporan
- Menu:
  - Semua Peminjaman
  - Persetujuan Peminjaman
  - Laporan
  - Data User

## File-File dalam Sistem

1. **login.php** - Halaman login dengan session management
2. **register.php** - Halaman registrasi dengan pemilihan role
3. **dashboard.php** - Dashboard utama (konten berubah sesuai role user)
4. **logout.php** - Logout dan destroy session
5. **db.php** - Koneksi database
6. **setup_database.php** - Setup awal database

## Cara Penggunaan

### 1. Setup Database
```
http://localhost/backendpeminjaman/setup_database.php
```

### 2. Registrasi User
```
http://localhost/backendpeminjaman/register.php
```
Pilih role saat registrasi:
- Anggota
- Sekretaris
- Ketua

### 3. Login
```
http://localhost/backendpeminjaman/login.php
```

### 4. Akses Dashboard
Setelah login, Anda akan diarahkan ke dashboard.php
- Konten akan berbeda sesuai role Anda
- Menu akan berbeda untuk setiap role

## Struktur Database

### Tabel users
```sql
- id (Primary Key)
- name
- email (Unique)
- password (hashed)
- role (anggota, sekretaris, ketua)
- created_at
```

### Tabel loans
```sql
- id (Primary Key)
- user_id (Foreign Key)
- item_name
- quantity
- loan_date
- return_date
- status (pending, approved, rejected, returned)
- reason
- created_at
```

## Fitur Keamanan

✓ Password di-hash menggunakan PASSWORD_DEFAULT
✓ Session-based authentication
✓ SQL Queries dengan prepared statements (dapat ditingkatkan)
✓ Role-based access control

## Pengembangan Lebih Lanjut

Untuk meningkatkan sistem:
1. Tambahkan prepared statements untuk menghindari SQL Injection
2. Tambahkan logout otomatis jika session timeout
3. Tambahkan email verification saat registrasi
4. Tambahkan logging untuk audit trail
5. Tambahkan form validation yang lebih ketat
6. Implementasikan approval workflow yang lengkap

## Testing

### User Test Accounts
Silakan registrasi user dengan role berbeda untuk testing:
- User 1: role=anggota
- User 2: role=sekretaris
- User 3: role=ketua

Kemudian login dan lihat perbedaan akses untuk setiap role.
