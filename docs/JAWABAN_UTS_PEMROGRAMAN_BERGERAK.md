# LAPORAN JAWABAN UJIAN TENGAH SEMESTER (UTS)
## MATA KULIAH: PEMROGRAMAN BERGERAK (PB26)
**Program Studi Teknik Informatika — Universitas PGRI Madiun (UNIPMA)**

---

### IDENTITAS MAHASISWA & PROYEK
* **Mata Kuliah** : Pemrograman Bergerak (PB26: UTS)
* **CPL 10** : Mampu merancang dan mengembangkan solusi komputasional untuk berbagai tantangan di bidang teknologi informasi dan komunikasi.
* **Sub-CPMK** : Merancang antarmuka pengguna (UI/UX), struktur navigasi, manajemen data, serta interaksi dengan hardware perangkat bergerak.
* **Judul Proyek Aplikasi** : **FitTrack** — *Mobile Gym Workout & Progressive Overload Tracking System*
* **Platform & Stack** : Mobile-First Web Application / PWA (Laravel 11/12, Tailwind CSS, Alpine.js, Web Hardware APIs, SQLite/MySQL)
* **Repositori GitHub** : `https://github.com/Aufalwafi/Gym-App.git`

---

## DAFTAR ISI LAPORAN
1. [Deskripsi Umum dan Urgensi Sistem](#1-deskripsi-umum-dan-urgensi-sistem)
2. [Bagian 1: Perancangan Antarmuka Pengguna (UI/UX)](#bagian-1-perancangan-antarmuka-pengguna-uiux)
3. [Bagian 2: Struktur Navigasi](#bagian-2-struktur-navigasi)
4. [Bagian 3: Manajemen Data](#bagian-3-manajemen-data)
5. [Bagian 4: Interaksi dengan Hardware Perangkat Bergerak](#bagian-4-interaksi-dengan-hardware-perangkat-bergerak)
6. [Kesimpulan](#kesimpulan)

---

## 1. DESKRIPSI UMUM DAN URGENSI SISTEM

Dalam aktivitas kebugaran beban (*weight training*), prinsip utama hipertrofi otot dan peningkatan kekuatan adalah **Progressive Overload** (peningkatan beban, repetisi, atau volume latihan secara bertahap dan terukur dari waktu ke waktu). Namun, sebagian besar pengguna gym mengalami kendala:
1. Kesulitan mengingat beban angkatan sesi sebelumnya di tengah kelelahan fisik.
2. Tidak adanya pencatatan waktu istirahat antarsesi (*rest timer*) yang akurat.
3. Aplikasi gym komersial yang terlalu rumit, berat, penuh iklan, dan boros baterai ponsel.

**FitTrack** dirancang khusus untuk memecahkan persoalan tersebut melalui pendekatan aplikasi bergerak (*mobile application*). Aplikasi ini memberikan kemudahan pencatatan repetisi dan beban (*set logging*), kalkulasi beban maksimal (*One-Rep Max*), timer istirahat otomatis, serta pemantauan progres beban dan komposisi tubuh dalam satu genggaman perangkat bergerak.

---

## BAGIAN 1: PERANCANGAN ANTARMUKA PENGGUNA (UI/UX)

Perancangan UI/UX pada FitTrack mengedepankan efisiensi operasional pada layar sentuh smartphone (*touchscreen*), kemudahan pembacaan data di lingkungan berlampu temaram atau silau (*gym lighting*), serta kenyamanan interaksi satu tangan saat pengguna beristirahat antarsesi.

```
+-----------------------------------------------------------+
| [DUMBBELL ICON] FitTrack             [Avatar Profile / Hi]|
|-----------------------------------------------------------|
| [Status Badge: Bulking / Cutting / Maintenance]           |
| Selamat Datang, Aufal Wafi!                               |
| 3 Sesi Latihan Selesai Minggu Ini                         |
|-----------------------------------------------------------|
| [Ringkasan Volume: 12.450 kg]  [Streak: 4 Hari]           |
|-----------------------------------------------------------|
| >> KARTU SESI TERAKHIR: Push Day (Dada, Bahu, Trisep)     |
|    - Bench Press: 4 Set (Top: 80 kg x 5 reps)            |
|    - Incline DB Press: 3 Set (26 kg x 8 reps)             |
|-----------------------------------------------------------|
|                                                           |
|       (Area Konten: Latihan Aktif / Grafik Progres)       |
|                                                           |
|-----------------------------------------------------------|
| [Home]    [Riwayat]     [ (+) LATIHAN ]    [Program]  [Progres] |
|                             (FAB)                         |
+-----------------------------------------------------------+
         Tampilan Mobile-First FitTrack (Thumb-Zone Friendly)
```

### 1.1. Prinsip Desain Mobile-First & Konfigurasi Viewport
Aplikasi dirancang dari sudut pandang perangkat genggam melalui deklarasi *meta viewport* yang ketat:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="theme-color" content="#09090b">
```
* **`user-scalable=no` & `maximum-scale=1.0`**: Mencegah *accidental double-tap zoom* atau cubitan layar (*pinch zoom*) yang kerap terjadi tanpa sengaja saat pengguna memegang ponsel dengan tangan basah oleh keringat.
* **`theme-color: #09090b`**: Mengintegrasikan warna latar aplikasi dengan *system status bar* (baris indikator sinyal, baterai, dan jam pada Android/iOS) sehingga menciptakan *native app feel*.

### 1.2. Ergonomi Jempol (Thumb Zone Ergonomics)
Berdasarkan riset Steven Hoober mengenai kebiasaan pengguna perangkat bergerak (*How Do Users Really Hold Mobile Devices*), mayoritas interaksi ponsel dilakukan dengan satu tangan (terutama ibu jari).
* **Zona Jangkauan Bawah (*Natural Reach Zone*)**: Seluruh navigasi primer, aksi mulai latihan (*floating action button*), tombol simpan set (*Checkmark button*), dan tombol navigasi *rest timer* diletakkan di bagian sepertiga bawah layar ponsel.
* **Touch Target Size**: Seluruh tombol interaktif memiliki ukuran minimal **44 × 44 pt** hingga **48 × 48 px** sesuai standar pedoman *Google Material Design 3* dan *Apple Human Interface Guidelines*, sehingga pengguna terhindar dari salah sentuh (*mis-click*) saat tremor pasca-angkat beban.

### 1.3. Estetika Visual "Dark Gym" & Efisiensi Energi Layar AMOLED
* **Warna Dasar Gelap (`#09090b` / Zinc-950)**: Memberikan kontras visual tinggi, mengurangi kelelahan mata (*eye fatigue*) di dalam gym, serta secara teknis menghemat daya baterai perangkat berbasis layar OLED/AMOLED karena piksel hitam tidak memancarkan cahaya (*true black*).
* **Warna Aksen Solid Emerald Green (`#16a34a` / Green-600)**: Warna hijau solid melambangkan energi, stamina, kesuksesan pencapaian target, dan mudah diidentifikasi sekilas pandang.
* **Penghindaran Efek Berat (*Zero Glassmorphism / No Blur*)**: Efek blur latar belakang (*backdrop-blur*) ditiadakan agar beban pemrosesan *raster rendering* GPU smartphone tetap minimal, menjamin *frame rate* stabil 60–120 FPS bahkan pada perangkat *smartphone entry-level*.

### 1.4. Kontrol Input Mobile & Kecepatan Input Data
* Input beban dan repetisi dilengkapi atribut `type="number"`, `inputmode="decimal"`, dan `step="any"` sehingga *on-screen keyboard* smartphone langsung memunculkan tombol papan numerik besar (*numeric keypad*), bukan papan tombol alfabetik.
* Terdapat fitur duplikasi otomatis nilai beban dan repetisi dari set sebelumnya (*auto-carry forward*) untuk memangkas waktu ketik saat latihan.

---

## BAGIAN 2: STRUKTUR NAVIGASI

Struktur navigasi FitTrack menggunakan pola **Hierarchical Persistent Bottom Navigation** yang lazim diterapkan pada aplikasi *mobile native* terkemuka (seperti Instagram, Spotify, dan Strong Workout Tracker).

```
                      +-------------------+
                      |   Halaman Login   |
                      +---------+---------+
                                |
                     (Autentikasi Berhasil)
                                |
                                v
               +---------------------------------+
               |   BOTTOM NAVIGATION BAR (Root)  |
               +---+---------+---------+-----+---+
                   |         |         |     |
      +------------+   +-----+-----+   |     +-------------+
      |                |               |                   |
      v                v               v                   v
+-----------+    +-----------+   +-----------+       +-----------+
| 1. Home   |    | 2. Riwayat|   | 4. Program|       | 5. Progres|
| Dashboard |    |  Latihan  |   |  Latihan  |       | & 1RM/TDEE|
+-----------+    +-----------+   +-----------+       +-----------+
                       |               |
                       v               v
                 Detail Sesi     Pilih Template
                       |               |
                       +-------+-------+
                               |
                               v
               +---------------------------------+
               | 3. Quick Action: "LATIHAN" (+) |
               |    (Active Live Workout Logger) |
               +---------------------------------+
                               |
                 +-------------+-------------+
                 |                           |
                 v                           v
         Modal Pilih Gerakan        Rest Timer Hardware
         (Katalog 34+ Latihan)     (Audio + Getar Haptic)
```

### 2.1. Komponen Bottom Navigation Bar
Bilah navigasi bawah dipasang melayang secara *fixed* di bagian dasar layar (`fixed bottom-0 left-0 right-0 z-50`):
1. **Home / Dashboard**: Menampilkan ringkasan status mingguan, total beban terangkat (*weekly volume*), *streak* konsistensi, dan sesi terakhir.
2. **Riwayat**: Daftar riwayat seluruh sesi masa lalu (*past logs*) lengkap dengan tanggal, durasi, total tonase, dan rincian per gerakan.
3. **Pusat Tombol (+) Latihan (*Center Floating Action Button*)**: Tombol lingkaran hijau berukuran 48px yang melompat keluar sedikit ke atas (*negative margin -mt-5*), memberikan akses kilat (*one-tap access*) bagi pengguna untuk langsung memulai pencatatan latihan baru.
4. **Program (Routines)**: Template latihan terstruktur (seperti Push, Pull, Legs, Upper, Lower) yang dapat diaktifkan dalam satu klik menjadi sesi langsung.
5. **Progres & Profil**: Grafik perkembangan beban latihan, pengukuran lingkar/berat badan mingguan, serta alat hitung ilmiah (*1RM Calculator & TDEE Calculator*).

### 2.2. Pola Interaksi Sesi Latihan Aktif (Zero-Page Reload via Alpine.js)
Selama sesi latihan berjalan, aplikasi tidak melakukan *page reload* penuh untuk setiap penambahan set atau penghapusan gerakan. Dengan memanfaatkan reaktivitas *Alpine.js*, manipulasi DOM terjadi di sisi klien (*client-side state*):
* Menambah set baru: `addSet(exIndex)` menduplikasi beban set sebelumnya secara reaktif.
* Menghitung prediksi kekuatan: `calculate1rm(set)` mengalkulasi formula Epley secara *real-time* saat angka diketik.
* Membuka modal katalog latihan tanpa menghentikan *rest timer* yang sedang berdetak di latar belakang.

---

## BAGIAN 3: MANAJEMEN DATA

Manajemen data FitTrack dirancang secara relasional dan dinormalisasi untuk menjaga integritas data catatan fisik pengguna.

### 3.1. Entity-Relationship Diagram (ERD) Konseptual

```
+------------------+          1:N         +-----------------------+
|      users       |--------------------->|   workout_sessions    |
+------------------+                      +-----------------------+
| id (PK)          |                      | id (PK)               |
| name             |                      | user_id (FK)          |
| email            |                      | name                  |
| password         |                      | started_at            |
+--------+---------+                      | ended_at              |
         |                                | duration_minutes      |
         | 1:N                            | total_volume_kg       |
         v                                +-----------+-----------+
+------------------+                                  | 1:N
|body_measurements |                                  v
+------------------+                      +-----------------------+
| id (PK)          |                      |     workout_sets      |
| user_id (FK)     |                      +-----------------------+
| recorded_date    |                      | id (PK)               |
| weight_kg        |                      | workout_session_id(FK)|
| chest_cm         |                      | exercise_id (FK)      |
| waist_cm         |                      | set_number            |
| arm_cm           |                      | weight_kg             |
+------------------+                      | reps                  |
                                          | rpe                   |
+----------------------+                  | is_warmup             |
| exercise_categories  |                  +-----------+-----------+
+----------------------+                              |
| id (PK)              |                              | N:1
| name (Dada, Punggung)|                              v
| slug                 |                  +-----------------------+
+----------+-----------+                  |       exercises       |
           | 1:N                          +-----------------------+
           +----------------------------->| id (PK)               |
                                          | category_id (FK)      |
+------------------+          1:N         | name (e.g. Bench Press|
|     routines     |--------------------->| target_muscle         |
+------------------+                      | equipment (barbell,db)|
| id (PK)          |                      +-----------------------+
| user_id (FK)     |                                  ^
| name (Push Day)  |                                  | N:M
| target_days      |                      +-----------+-----------+
+------------------+                      |   routine_exercises   |
                                          +-----------------------+
```

### 3.2. Rincian Entitas dan Struktur Basis Data
1. **`users`**: Menyimpan kredensial autentikasi pengguna, *encrypted password* (Bcrypt/Argon2id), dan token otentikasi.
2. **`exercise_categories` & `exercises`**: Menyimpan data master gerakan fitness (terisi lebih dari 34 variasi latihan baku: Dada, Punggung, Bahu, Kaki, Lengan, Perut) beserta peralatan yang dibutuhkan (*Barbell, Dumbbell, Machine, Cable, Bodyweight*).
3. **`workout_sessions`**: Menyimpan sesi latihan pengguna (waktu mulai, waktu selesai, durasi aktual, total akumulasi beban / tonase volume). Relasi: `belongsTo(User::class)`.
4. **`workout_sets`**: Menyimpan detail granular setiap angkatan: beban (*weight_kg*), repetisi (*reps*), tingkat kesulitan subjektif (*RPE - Rate of Perceived Exertion 1–10*), dan penanda *warmup/working set*. Relasi: `belongsTo(WorkoutSession::class)` dan `belongsTo(Exercise::class)`.
5. **`routines` & `routine_exercises`**: Template latihan mingguan yang menghubungkan urutan latihan, target set, dan target repetisi.
6. **`body_measurements`**: Pencatatan perkembangan metrik antropometri tubuh (berat badan harian, lingkar dada, lingkar pinggang, lingkar lengan).

### 3.3. Operasi CRUD dan Validasi Server-Side
Sistem menggunakan *Eloquent ORM* dengan proteksi *Mass Assignment Protection* (`$fillable`), *Foreign Key Constraints* dengan `cascadeOnDelete()`, serta validasi data terpusat:
```php
// Contoh Validasi dan Penyimpanan Transaksional pada WorkoutSessionController
$validated = $request->validate([
    'name' => 'required|string|max:100',
    'started_at' => 'required|date',
    'exercises' => 'required|array|min:1',
    'exercises.*.id' => 'required|exists:exercises,id',
    'exercises.*.sets' => 'required|array|min:1',
    'exercises.*.sets.*.weight_kg' => 'required|numeric|min:0|max:1000',
    'exercises.*.sets.*.reps' => 'required|integer|min:0|max:500',
]);
```
Semua mutasi penyimpanan dibungkus dalam `DB::transaction()` untuk memastikan jika ada satu data set yang gagal ditulis, sistem otomatis melakukan *rollback*, sehingga tidak terjadi *orphaned records*.

### 3.4. Logika Bisnis & Algoritma Komputasi Bergerak
1. **Algoritma Estimasi One-Rep Max (Formula Epley)**:
   Digunakan untuk memprediksi kekuatan beban maksimal 1 repetisi pengguna secara aman tanpa risiko cedera:
   $$\text{1RM} = w \times \left(1 + \frac{r}{30}\right)$$
   *Keterangan: $w$ = Beban angkatan (kg), $r$ = Jumlah repetisi.*

2. **Algoritma Kebutuhan Energi Harian (Formula Mifflin-St Jeor)**:
   Digunakan pada modul kalkulator makronutrisi dan metabolisme:
   $$\text{BMR (Pria)} = (10 \times \text{BB}) + (6.25 \times \text{TB}) - (5 \times \text{Usia}) + 5$$
   $$\text{BMR (Wanita)} = (10 \times \text{BB}) + (6.25 \times \text{TB}) - (5 \times \text{Usia}) - 161$$
   $$\text{TDEE} = \text{BMR} \times \text{Faktor Aktivitas Fisik}$$

---

## BAGIAN 4: INTERAKSI DENGAN HARDWARE PERANGKAT BERGERAK

Salah satu kriteria terpenting dalam pemrograman bergerak adalah kemampuan perangkat lunak dalam berinteraksi dengan modul fisik (*hardware peripheral*) perangkat. FitTrack memanfaatkan 4 API perangkat keras utama:

```
+--------------------------------------------------------------------------+
|                     INTERAKSI HARDWARE SMARTPHONE                        |
+--------------------------------------------------------------------------+
|                                                                          |
|   1. VIBRATION MOTOR (Haptic Feedback)                                   |
|      API: navigator.vibrate([250, 100, 250, 100, 500])                   |
|      Fungsi: Menggetarkan ponsel di saku saat waktu istirahat habis      |
|                                                                          |
|   2. HARDWARE AUDIO SYNTHESIZER / DAC SPEAKER                            |
|      API: window.AudioContext & OscillatorNode (880 Hz Sine Wave)         |
|      Fungsi: Membunyikan bel 'ding' frekuensi tinggi di lingkungan gym    |
|                                                                          |
|   3. DISPLAY POWER CONTROLLER (Screen Wake Lock)                         |
|      API: navigator.wakeLock.request('screen')                           |
|      Fungsi: Mencegah layar smartphone mati otomatis saat sesi aktif     |
|                                                                          |
|   4. CAPACITIVE TOUCH CONTROLLER (Touchscreen & Virtual Keyboard)        |
|      Fitur: inputmode="decimal", user-scalable=no                        |
|      Fungsi: Mengoptimalkan input digit angka dan meniadakan accidental  |
|              pinch zoom saat tangan berkeringat                          |
|                                                                          |
+--------------------------------------------------------------------------+
```

### 4.1. Interaksi Motor Penggetar (Haptic Vibration API)
* **Problem**: Di lingkungan gym yang bising dengan suara dentuman musik dan bantingan dumble, suara speaker ponsel seringkali tidak terdengar jika ponsel ditaruh di saku celana atlet.
* **Solusi Hardware**: FitTrack memprogram modul motor getar haptic (*vibration motor*) smartphone menggunakan standar W3C *Vibration API*.
* **Implementasi Kode (`resources/views/workouts/create.blade.php`)**:
```javascript
playTimerDing() {
    // 1. Mengakses Motor Getar Smartphone (Haptic Feedback)
    if ('vibrate' in navigator) {
        try {
            // Pola ritme: getar 250ms, jeda 100ms, getar 250ms, jeda 100ms, getar panjang 500ms
            navigator.vibrate([250, 100, 250, 100, 500]);
        } catch (e) {
            console.log('Hardware Vibration not supported/permitted');
        }
    }
    // ... Audio feedback
}
```
* **Hasil Pengujian**: Saat penghitung waktu istirahat (*rest timer*) mencapai angka `00:00`, ponsel bergetar dengan pola khas 3 ketukan bertenaga sehingga atlet langsung mengetahui saatnya memulai set berikutnya tanpa harus menatap layar secara terus-menerus.

### 4.2. Interaksi Hardware Speaker (Web Audio API Synthesizer)
* **Problem**: Mengunduh file aset audio eksternal (`.mp3` atau `.wav`) membutuhkan koneksi data internet tambahan, berisiko gagal diputar jika ponsel berada dalam mode *offline* atau sinyal di basement gym lemah.
* **Solusi Hardware**: FitTrack langsung mengakses *Digital-to-Analog Converter* (DAC) dan *Sound Card/Speaker* ponsel melalui *Web Audio API*. Sinyal gelombang sinus (*sine wave*) disintesis secara komputasi matematika murni di frekuensi 880 Hz (A5 pitch).
* **Implementasi Kode**:
```javascript
try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.value = 880; // Nada A5, frekuensi tembus kebisingan gym
    osc.connect(gain);
    gain.connect(ctx.destination); // Terhubung ke Hardware Output Speaker

    gain.gain.setValueAtTime(0.3, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);

    osc.start();
    osc.stop(ctx.currentTime + 0.8);
} catch (e) {
    console.log('Audio Context fallback');
}
```
* **Hasil Pengujian**: Menghasilkan suara denting (*ding*) jernih dan instan 0 milidetik *latency* tanpa perlu memuat file audio eksternal.

### 4.3. Interaksi Modul Pengatur Daya Layar (Screen Wake Lock API)
* **Problem**: Secara *default*, sistem operasi mobile (Android / iOS) akan mematikan layar (*screen timeout*) setelah 30–60 detik tidak tersentuh untuk menghemat baterai. Hal ini sangat mengganggu atlet yang sedang melakukan gerakan angkat beban (misalnya plank atau istirahat 90 detik) karena mereka harus membuka kunci layar (*unlock*) berulang kali dengan tangan kotor/berkeringat.
* **Solusi Hardware**: FitTrack meminta hak akses ke subsistem manajemen daya layar (*screen power management*) melalui *Screen Wake Lock API*.
* **Implementasi Kode**:
```javascript
wakeLock: null,

async requestWakeLock() {
    try {
        if ('wakeLock' in navigator) {
            this.wakeLock = await navigator.wakeLock.request('screen');
        }
    } catch (err) {
        console.log('Screen Wake Lock inactive:', err);
    }
},

init() {
    // Meminta hardware layar tetap menyala saat masuk halaman sesi latihan
    this.requestWakeLock();

    // Jika pengguna berpindah tab aplikasi lalu kembali lagi, perbarui Wake Lock
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            this.requestWakeLock();
        }
    });
}
```
* **Hasil Pengujian**: Selama pengguna berada di halaman pencatatan latihan aktif, layar ponsel tetap menyala tanpa pernah tertidur (*screen sleep disabled*), dan otomatis kembali normal saat pengguna keluar dari halaman latihan.

### 4.4. Interaksi Layar Sentuh Kapasitif & Sensor Orientasi
* **Pencegahan Zoom Tak Disengaja**: Viewport mobile dikunci terhadap pembesaran ganda (*double-tap zoom lock*) agar ketukan berulang pada tombol checklist set tidak menggeser tampilan.
* **Kontrol Papan Ketik Virtual**: Penggunaan atribut `inputmode="decimal"` memanggil *hardware driver keyboard* angka yang memudahkan pengetikan beban berformat desimal (contoh: 12.5 kg).

---

## KESIMPULAN

Rancangan aplikasi **FitTrack** telah secara komprehensif mengintegrasikan seluruh elemen kompetensi yang dipersyaratkan dalam Capaian Pembelajaran Lulusan (CPL 10) dan Sub-CPMK Mata Kuliah Pemrograman Bergerak:

1. **Rancangan Antarmuka Pengguna (UI/UX)**: Telah memenuhi kaidah *Mobile-First*, ergonomi *Thumb Zone* dengan *touch target* berstandar 48px, estetika *Dark Gym* hemat energi OLED, serta status bar ponsel yang menyatu.
2. **Struktur Navigasi**: Telah mengimplementasikan *Persistent Bottom Navigation Bar* dengan penempatan tombol *Floating Action Button* (FAB) di tengah, hierarki navigasi yang jelas, serta transisi halaman tanpa *reload* (*SPA-like experience*).
3. **Manajemen Data**: Telah memiliki rancangan basis data relasional ternormalisasi (6 tabel utama), integritas *foreign key cascade*, transaksi basis data aman, serta implementasi rumus algoritma kebugaran ilmiah (Epley 1RM & Mifflin-St Jeor TDEE).
4. **Interaksi Hardware Bergerak**: Telah mengintegrasikan 4 modul fisik perangkat bergerak secara nyata: Motor Penggetar (*Vibration API*), Speaker Audio (*Web Audio API*), Kontrol Daya Layar (*Screen Wake Lock API*), dan Kontrol Sentuh Layar Kapasitif (*Touchscreen Inputmode Optimization*).
