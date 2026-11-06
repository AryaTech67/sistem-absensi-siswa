# 🎓 OnHadir — Sistem Informasi Presensi Siswa & QR Smartcard

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/SQLite_%2F_MySQL-Database-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="Database" />
  <img src="https://img.shields.io/badge/UKK_RPL-100%25_Compliant-059669?style=for-the-badge" alt="UKK RPL Compliant" />
</p>

---

## 📌 Ringkasan Proyek

**OnHadir** adalah platform web modern untuk manajemen kehadiran dan kedisiplinan siswa berbasis sekolah kejuruan (studi kasus: SMK Darus Sa'adah). 

Aplikasi ini dikembangkan untuk mendigitalkan seluruh siklus absensi harian—mulai dari **pemindaian presensi instan via QR Code & Barcode kartu pelajar**, **pencatatan massal 1-klik (batch entry)**, **pelacakan poin kedisiplinan dan tugas kompensasi**, **portal transparansi publik untuk wali murid**, hingga **mesin cetak Kartu Tanda Pelajar fisik (CR80 Smartcard standard)** siap cetak A4.

Proyek ini telah melalui audit dan **memenuhi 100% Kriteria Spesifikasi UKK (Uji Kompetensi Keahlian) RPL** (FR-01 s.d FR-38, Business Rules BR-01 s.d BR-12, dan 15 Acceptance Test Scenarios).

---

## ✨ Fitur Utama

- ⚡ **Pemindai Presensi QR Code & Barcode Real-Time**: Scan presensi siswa langsung menggunakan kamera laptop/smartphone dengan umpan balik suara beep dan deteksi keterlambatan otomatis.
- 📋 **Input Absensi Massal (1-Click Batch Input)**: Tombol cerdas *"Tandai Semua Hadir (H)"* yang memangkas waktu pencatatan 30+ siswa menjadi di bawah 15 detik.
- 🪪 **Mesin Cetak Kartu Pelajar (CR80 Standard)**: Layout cetak terkalibrasi presisi **8 kartu per lembar kertas A4** tanpa margin terpotong, lengkap dengan Pas Foto 3x4, barcode, QR Code unik, dan identitas sekolah.
- ⚖️ **Sistem Kedisiplinan & Kompensasi Keterlambatan**: Pencatatan otomatis jam pelajaran yang tertinggal, penugasan tugas edukatif, kalkulasi poin pelanggaran, dan indikator ambang batas surat panggilan orang tua.
- 👨‍👩‍👧 **Portal Mandiri Wali Murid & Siswa (`/cek-presensi`)**: Akses publik tanpa login untuk memantau rekap persentase kehadiran, rincian hari sakit/izin, dan riwayat disiplin secara transparan.
- 📊 **Rekapitulasi & Pelaporan Komprehensif**:
  - Rekap individual per siswa dan rekap kolektif per rombongan belajar.
  - Perhitungan persentase kehadiran presisi:
    $$\text{Persentase} = \frac{\text{Hadir} + \text{Terlambat}}{\text{Total Pertemuan Tercatat}} \times 100\%$$
  - Filter rentang tanggal inklusif dengan proteksi penolakan tanggal tidak valid (`start_date > end_date`).
  - Ekspor multi-format: **Cetak PDF Resmi (Kop Surat Sekolah)**, **Microsoft Excel (.xls)**, dan **CSV**.
- 🛡️ **Audit Trail & Keamanan Tingkat Tinggi**:
  - Role-Based Access Control (RBAC): **Administrator** vs **Petugas Absensi**.
  - Pencatatan log perubahan absensi (`attendance_audits`) untuk menelusuri riwayat revisi (status lama vs status baru, waktu, dan pelaku).
  - *Compound Unique Constraints* pada database untuk menjamin **nol duplikasi data**.
  - Perlindungan integritas: riwayat absensi kelas lampau tetap utuh saat siswa naik kelas (*historical retention*).

---

## 🛠️ Tech Stack & Arsitektur

| Komponen | Teknologi |
|---|---|
| **Framework Backend** | [Laravel 11.x](https://laravel.com/) (PHP 8.2+) |
| **Arsitektur** | Clean MVC, Form Request Validation, Eloquent ORM |
| **Styling & UI** | [Tailwind CSS 3.4](https://tailwindcss.com/), Google Fonts (*Plus Jakarta Sans*) |
| **Database** | SQLite (Default Dev) / MySQL dengan Foreign Keys & Unique Compound Indexing |
| **Library Interaktif** | [HTML5-QRCode](https://github.com/mebjas/html5-qrcode), [Chart.js](https://www.chartjs.org/) |
| **Keamanan** | Bcrypt Password Hashing, CSRF Tokens, Secure Session Auth |

---

## 🗄️ Struktur Relasi Database

- `users`: Akun administrator, guru piket, dan staf petugas absensi.
- `academic_years`: Periode tahun ajaran (misal: 2026/2027) dengan penanda status aktif.
- `classrooms`: Rombongan belajar yang terikat dengan tahun ajaran dan wali kelas.
- `students`: Data master siswa (NIS unik, NISN, nama, gender, TTL, alamat, foto profil, kontak wali).
- `attendance_sessions`: Sesi absensi per kelas, tanggal, dan nama sesi (Pagi/Siang) dengan constraint `UNIQUE(classroom_id, attendance_date, session_name)`.
- `attendances`: Catatan kehadiran tiap siswa dalam suatu sesi dengan constraint `UNIQUE(attendance_session_id, student_id)`.
- `attendance_audits`: Catatan audit forensik jika status absensi diperbaiki oleh petugas/admin.
- `school_settings`: Konfigurasi kop surat resmi, toleransi bel masuk, dan unduhan berkas cadangan database.

---

## 🚀 Panduan Instalasi Lokal

### 1. Kloning Repository
```bash
git clone https://github.com/AryaTech67/sistem-absensi-siswa.git
cd sistem-absensi-siswa
```

### 2. Instal Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Lingkungan (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi & Seeding Database
```bash
php artisan migrate --seed
```
*Seeder otomatis mengisikan:*
- 2 Akun Pengguna (Administrator & Petugas Absensi)
- 1 Tahun Ajaran Aktif (`2026/2027`)
- 6 Rombongan Belajar (`X-RPL`, `X-DKV`, `XI-RPL`, `XI-DKV`, `XII-RPL`, `XII-DKV`)
- 90 Siswa aktif (15 siswa per rombel) lengkap dengan NIS, data orang tua, dan kartu pelajar siap cetak.

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Akun Default

| Peran (Role) | Email Login | Kata Sandi | Hak Akses |
|---|---|---|---|
| **Administrator** | `arrxptrr19@gmail.com` | `arya7777` | Akses penuh (Kelola akun, master rombel, siswa, absensi, kop sekolah, backup) |
| **Petugas Absensi** | `suteja77@gmail.com` | `suteja7777` | Input presensi, scanner QR, perbaikan absensi, rekap & cetak laporan |

---

## 👨‍💻 Pengembang

Dikembangkan oleh **[Arya Putra](https://github.com/AryaTech67)**  
*Program Keahlian Rekayasa Perangkat Lunak (RPL)*

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE).
