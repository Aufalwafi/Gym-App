# FitTrack - Gym Workout & Fitness Tracker

Aplikasi berbasis web modern untuk pencatatan latihan gym (*live workout logger*), perancangan program latihan (*routine planner*), pelacakan progres beban & fisik (*progress & PR tracker*), serta kalkulator kebugaran & nutrisi (*BMI & TDEE calculator*).

Dikembangkan sebagai proyek mata kuliah/tugas semester 7 dengan standar arsitektur MVC yang bersih, relasi basis data terstruktur, tampilan responsif (*mobile-friendly* dengan estetika *dark mode* gym), dan dokumentasi lengkap.

---

## 🚀 Fitur Utama

### 1. 🏋️‍♂️ Live Workout Logger (Pencatatan Latihan Real-Time)
- **Input Set & Repetisi Cepat**: Tambah/hapus set dinamis, atur beban (kg), repetisi, dan tingkat kelelahan (*RPE 1-10*).
- **Penghitung Otomatis Estimated 1RM**: Menghitung estimasi *One-Rep Max* secara instan per set menggunakan formula Epley:
  $$\text{1RM} = \text{Beban} \times \left(1 + \frac{\text{Reps}}{30}\right)$$
- **Built-in Rest Timer (Stopwatch Jeda Istirahat)**: Tombol jeda cepat (60s, 90s, 120s) dengan animasi hitung mundur visual dan notifikasi suara/getar saat waktu istirahat habis.
- **Kalkulasi Volume Otomatis**: Menghitung total tonase volume beban (kg) yang terangkat dalam satu sesi latihan.

### 2. 📋 Routine & Split Planner (Template Jadwal Latihan)
- Pilihan template bawaan siap pakai: **Push Day**, **Pull Day**, **Leg Day**.
- Fasilitas membuat program latihan kustom (PPL, Upper/Lower, Bro Split, Full Body).
- Fitur *1-Click Start*: Memulai sesi latihan langsung dari template yang dipilih tanpa harus memilih gerakan satu per satu.

### 3. 📖 Exercise Encyclopedia (Katalog 40+ Gerakan Gym)
- Dikelompokkan berdasarkan otot utama: **Dada**, **Punggung**, **Kaki**, **Bahu**, **Biceps**, **Triceps**, dan **Core/Abs**.
- Filter berdasarkan alat: *Barbell, Dumbbell, Cable, Machine, Bodyweight*.
- Panduan cara melakukan gerakan yang benar dan tips keselamatan/form.
- Catatan *Personal Record (PR)* pengguna pada masing-masing gerakan.

### 4. ⚖️ Fitness & Nutrition Calculator
- **Kalkulator BMI**: Skor indeks massa tubuh, kategori (Kurus, Normal, Berlebih, Obesitas), dan rentang berat badan ideal berdasarkan tinggi badan.
- **Kalkulator BMR & TDEE (Mifflin-St Jeor)**:
  - Menghitung energi metabolisme dasar (BMR) dan pengeluaran kalori harian (TDEE).
  - Penyesuaian target kalori sesuai tujuan: **Bulking** (+350 kcal), **Cutting** (-450 kcal), atau **Maintenance**.
  - Distribusi makronutrisi harian: Protein (2g/kg BB), Karbohidrat, dan Lemak Sehat.
  - Tombol **Simpan Target ke Profil** untuk memperbarui dashboard.

### 5. 📈 Progress & Personal Records (PR) Hall of Fame
- Grafik visual interaktif perkembangan berat badan dari waktu ke waktu (*Chart.js*).
- Pencatatan lingkar tubuh: Dada, Lengan, Pinggang, dan persentase lemak tubuh (*Body Fat %*).
- Papan peringkat rekor angkatan terberat (*Personal Records*) pengguna.

### 6. 📄 Ekspor & Cetak Laporan Sesi Latihan
- Fitur cetak ramah printer (*CSS print-optimized*) untuk mengekspor rincian sesi latihan ke format dokumen PDF sebagai bukti/lampiran laporan kuliah.

---

## 🛠️ Tech Stack & Arsitektur

- **Backend**: PHP 8.2+ & Laravel 11 / 12 (MVC Architecture, Eloquent ORM, Form Requests, Seeders)
- **Frontend**: Blade Templating, Tailwind CSS v4, Alpine.js (reaktivitas UI), Chart.js (grafik progres), Lucide Icons
- **Database**: MySQL / SQLite
- **Environment**: XAMPP Windows

---

## 📦 Panduan Instalasi & Menjalankan

### 1. Kebutuhan Sistem
- PHP >= 8.2 (ekstensi `pdo`, `mbstring`, `zip` aktif)
- Composer
- Node.js & NPM

### 2. Akun Demo Bawaan
Aplikasi sudah dilengkapi akun demo siap pakai:
- **Email**: `admin@fittrack.local`
- **Password**: `password123`
*(Terdapat tombol **"Gunakan Akun Demo (1-Klik)"** di halaman login)*.

### 3. Menjalankan Server Lokal
Jalankan perintah berikut di terminal:
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **http://127.0.0.1:8000**

Jika ingin mengompilasi ulang aset Tailwind/JS saat melakukan perubahan tampilan:
```bash
npm run build
```

### 4. Menjalankan Unit & Feature Test
Seluruh endpoint dan logika bisnis telah diverifikasi dengan automated test suite:
```bash
php artisan test
```

---

## 🗄️ Skema Database (Tabel Utama)
1. `users`: Menyimpan data autentikasi, atribut antropometri (tinggi, berat target), target kalori/protein.
2. `exercise_categories`: Kategori kelompok otot (Chest, Back, Legs, dsb).
3. `exercises`: Katalog gerakan latihan gym dan tips eksekusi.
4. `routines`: Template program latihan.
5. `routine_exercises`: Relasi gerakan yang ada di dalam suatu routine beserta target set/reps/rest.
6. `workout_sessions`: Riwayat sesi latihan pengguna beserta durasi dan total volume.
7. `workout_sets`: Catatan set individual (beban kg, reps, RPE, estimasi 1RM).
8. `body_measurements`: Catatan berkala berat badan dan ukuran fisik tubuh.
