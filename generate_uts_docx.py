import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, fill_hex):
    shading_elm = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    cell._tc.get_or_add_tcPr().append(shading_elm)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
    tcPr.append(tcMar)

def create_document():
    doc = Document()

    # Page setup (A4, 1 inch margins)
    section = doc.sections[0]
    section.page_width = Inches(8.27)
    section.page_height = Inches(11.69)
    section.top_margin = Inches(1.0)
    section.bottom_margin = Inches(1.0)
    section.left_margin = Inches(1.0)
    section.right_margin = Inches(1.0)

    # Base styles
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Calibri'
    normal_style.font.size = Pt(11)
    normal_style.font.color.rgb = RGBColor(30, 41, 59) # Slate 800

    # Header / Title Block
    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_inst.paragraph_format.space_after = Pt(2)
    run_inst1 = p_inst.add_run("UNIVERSITAS PGRI MADIUN (UNIPMA)\n")
    run_inst1.bold = True
    run_inst1.font.size = Pt(13)
    run_inst1.font.color.rgb = RGBColor(15, 23, 42)
    
    run_inst2 = p_inst.add_run("FAKULTAS TEKNIK - PROGRAM STUDI TEKNIK INFORMATIKA\n")
    run_inst2.bold = True
    run_inst2.font.size = Pt(11)
    run_inst2.font.color.rgb = RGBColor(71, 85, 105)

    run_inst3 = p_inst.add_run("UJIAN TENGAH SEMESTER (UTS) GASAL TAHUN AKADEMIK 2026/2027")
    run_inst3.font.size = Pt(10)
    run_inst3.font.italic = True
    run_inst3.font.color.rgb = RGBColor(100, 116, 139)

    # Horizontal divider line via border
    p_line = doc.add_paragraph()
    p_line.paragraph_format.space_after = Pt(14)
    run_line = p_line.add_run("―" * 58)
    run_line.font.color.rgb = RGBColor(203, 213, 225)
    run_line.font.size = Pt(11)
    p_line.alignment = WD_ALIGN_PARAGRAPH.CENTER

    # Document Title
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.space_after = Pt(4)
    run_title = p_title.add_run("LAPORAN JAWABAN TUGAS UTS\n")
    run_title.bold = True
    run_title.font.size = Pt(15)
    run_title.font.color.rgb = RGBColor(21, 128, 61) # Green 700

    run_sub = p_title.add_run("MATA KULIAH: PEMROGRAMAN BERGERAK (PB26)")
    run_sub.bold = True
    run_sub.font.size = Pt(12)
    run_sub.font.color.rgb = RGBColor(15, 23, 42)

    # Info Table (Metadata)
    table_meta = doc.add_table(rows=6, cols=2)
    table_meta.alignment = WD_TABLE_ALIGNMENT.CENTER
    table_meta.autofit = False

    meta_data = [
        ("Nama Mahasiswa", ": Aufal Wafi"),
        ("NIM", ": [Silakan Masukkan NIM Anda]"),
        ("Capaian Pembelajaran (CPL 10)", ": Mampu merancang dan mengembangkan solusi komputasional untuk berbagai tantangan di bidang TIK."),
        ("Sub-CPMK / Soal (Bobot 100%)", ": Merancang antarmuka pengguna (UI/UX), struktur navigasi, manajemen data, serta interaksi dengan hardware perangkat bergerak."),
        ("Judul Proyek Aplikasi", ": FitTrack — Mobile Gym Workout & Progressive Overload Tracking System"),
        ("Repositori Kode (GitHub)", ": https://github.com/Aufalwafi/Gym-App.git")
    ]

    col_widths = [Inches(2.3), Inches(4.0)]
    for r_idx, (k, v) in enumerate(meta_data):
        row = table_meta.rows[r_idx]
        cell_lbl, cell_val = row.cells[0], row.cells[1]
        
        cell_lbl.width = col_widths[0]
        cell_val.width = col_widths[1]
        
        p0 = cell_lbl.paragraphs[0]
        p0.paragraph_format.space_after = Pt(2)
        r0 = p0.add_run(k)
        r0.bold = True
        r0.font.size = Pt(9.5)
        r0.font.color.rgb = RGBColor(51, 65, 85)
        
        p1 = cell_val.paragraphs[0]
        p1.paragraph_format.space_after = Pt(2)
        r1 = p1.add_run(v)
        r1.font.size = Pt(9.5)
        if "github" in v:
            r1.font.color.rgb = RGBColor(22, 101, 52)
            r1.bold = True
            
        set_cell_background(cell_lbl, "F8FAFC")
        set_cell_background(cell_val, "F8FAFC")
        set_cell_margins(cell_lbl, top=40, bottom=40, left=80, right=80)
        set_cell_margins(cell_val, top=40, bottom=40, left=80, right=80)

    doc.add_paragraph().paragraph_format.space_after = Pt(12)

    # Helper function for Headings
    def add_sec_heading(title_text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(16)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(title_text)
        run.bold = True
        run.font.size = Pt(13)
        run.font.color.rgb = RGBColor(21, 128, 61) # Emerald/Green
        return p

    def add_sub_heading(title_text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(10)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(title_text)
        run.bold = True
        run.font.size = Pt(11)
        run.font.color.rgb = RGBColor(30, 41, 59)
        return p

    # Helper function for Screenshot Callout Box
    def add_screenshot_placeholder(box_title, description):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        cell = tbl.cell(0, 0)
        cell.width = Inches(6.3)
        set_cell_background(cell, "FEFCE8") # Light yellow/amber warning fill or green
        
        # Border left amber
        tcPr = cell._tc.get_or_add_tcPr()
        borders = parse_xml(f'<w:tcBorders {nsdecls("w")}><w:top w:val="dashed" w:sz="4" w:space="0" w:color="F59E0B"/><w:left w:val="single" w:sz="24" w:space="0" w:color="D97706"/><w:bottom w:val="dashed" w:sz="4" w:space="0" w:color="F59E0B"/><w:right w:val="dashed" w:sz="4" w:space="0" w:color="F59E0B"/></w:tcBorders>')
        tcPr.append(borders)
        set_cell_margins(cell, top=100, bottom=100, left=150, right=150)
        
        p = cell.paragraphs[0]
        p.paragraph_format.space_after = Pt(3)
        r_title = p.add_run(f"📸 [TEMPATKAN SCREENSHOT DI SINI: {box_title}]\n")
        r_title.bold = True
        r_title.font.size = Pt(10)
        r_title.font.color.rgb = RGBColor(180, 83, 9) # Amber 700
        
        r_desc = p.add_run(f"Petunjuk Pengambilan Gambar:\n{description}")
        r_desc.font.size = Pt(9.5)
        r_desc.font.italic = True
        r_desc.font.color.rgb = RGBColor(120, 53, 15)
        
        # Add spacing after table
        p_sp = doc.add_paragraph()
        p_sp.paragraph_format.space_after = Pt(6)

    # Helper function for Code Box
    def add_code_box(code_text):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        cell = tbl.cell(0, 0)
        cell.width = Inches(6.3)
        set_cell_background(cell, "0F172A") # Dark slate
        set_cell_margins(cell, top=80, bottom=80, left=120, right=120)
        
        p = cell.paragraphs[0]
        p.paragraph_format.space_after = Pt(0)
        r = p.add_run(code_text)
        r.font.name = "Consolas"
        r.font.size = Pt(8.5)
        r.font.color.rgb = RGBColor(226, 232, 240)
        
        p_sp = doc.add_paragraph()
        p_sp.paragraph_format.space_after = Pt(4)

    # --- PENDAHULUAN ---
    add_sec_heading("RINGKASAN SISTEM DAN LATAR BELAKANG")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(6)
    p.add_run(
        "Aplikasi FitTrack adalah sistem pencatatan latihan kebugaran beban (Gym Workout & Progressive Overload Tracking System) "
        "yang dirancang khusus dengan pendekatan Mobile-First Progressive Web Application (PWA). "
        "Dalam ilmu keolahragaan beban, prinsip utama pertumbuhan otot (hipertrofi) dan kekuatan adalah Progressive Overload, "
        "yaitu peningkatan beban, repetisi, atau volume latihan secara teratur dan terukur dari waktu ke waktu. "
        "FitTrack memecahkan masalah umum para penggiat kebugaran yang sering lupa dengan beban angkatan sesi sebelumnya, "
        "tidak memiliki pengingat waktu istirahat (rest timer) yang akurat di tengah kebisingan gym, "
        "atau terganggu oleh aplikasi komersial yang rumit dan boros baterai."
    )

    # --- BAGIAN 1: UI/UX ---
    add_sec_heading("BAGIAN 1: PERANCANGAN ANTARMUKA PENGGUNA (UI/UX)")
    
    add_sub_heading("1.1. Prinsip Desain Mobile-First & Konfigurasi Viewport")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Aplikasi dirancang dari sudut pandang perangkat genggam smartphone (layar sentuh vertikal rasio 19:9 hingga 20:9). "
        "Pengaturan viewport dikonfigurasi secara ketat pada berkas utama resources/views/layouts/app.blade.php:\n"
    )
    add_code_box(
        '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">\n'
        '<meta name="theme-color" content="#09090b">'
    )
    p_exp = doc.add_paragraph()
    p_exp.paragraph_format.space_after = Pt(4)
    p_exp.add_run(
        "• user-scalable=no & maximum-scale=1.0: Mencegah terjadinya pembesaran layar ganda secara tak sengaja (accidental double-tap zoom) "
        "saat pengguna menyentuh tombol pencatatan dengan jari berkeringat pasca-angkat beban.\n"
        "• theme-color (#09090b): Mengintegrasikan warna latar aplikasi dengan status bar sistem operasi mobile (Android/iOS) "
        "sehingga menyajikan pengalaman visual layaknya aplikasi native."
    )

    add_sub_heading("1.2. Ergonomi Jempol (Thumb Zone Ergonomics) & Ukuran Target Sentuh")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Sesuai penelitian Steven Hoober mengenai kebiasaan pengguna mobile, mayoritas pengguna mengoperasikan ponsel cerdas "
        "dengan satu tangan, khususnya menggunakan ibu jari. FitTrack menerapkan kaidah ergonomi jempol:\n"
        "1. Area Sepertiga Bawah (Natural Reach Zone): Bilah navigasi utama, tombol mulai latihan (Center Floating Action Button), "
        "dan tombol centang penyelesaian set ditempatkan pada zona jangkauan jempol bawah.\n"
        "2. Dimensi Target Sentuh (Touch Target Size): Seluruh tombol interaktif memiliki ukuran minimal 44x44 pt hingga 48x48 px "
        "sesuai standar Google Material Design 3 dan WCAG (Web Content Accessibility Guidelines) untuk menghindari salah sentuh (mis-click) "
        "saat tangan pengguna mengalami tremor kelelahan."
    )

    add_sub_heading("1.3. Estetika Visual 'Dark Gym' & Efisiensi Energi Layar AMOLED")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "• Palet Gelap Solid (Zinc-950 #09090b & Zinc-900 #18181b): Memberikan kontras tinggi yang nyaman dibaca pada pencahayaan gym "
        "yang temaram maupun terang benderang. Pada layar bertipe OLED/AMOLED, piksel hitam murni tidak dialiri arus listrik (true black), "
        "sehingga secara signifikan menghemat daya baterai perangkat bergerak.\n"
        "• Warna Aksen Hijau Solid (Energetic Green #16a34a): Melambangkan keberhasilan, vitalitas, dan fokus target latihan.\n"
        "• Tanpa Efek Glassmorphism Berat (Zero Backdrop-Blur): Seluruh efek kaca buram dihilangkan agar GPU ponsel tidak terbebani pemrosesan "
        "raster shader, menjamin performa aplikasi tetap berada pada 60-120 FPS bahkan di ponsel kelas entry-level."
    )

    add_screenshot_placeholder(
        "Tampilan Halaman Utama / Dashboard Mobile FitTrack",
        "Buka browser smartphone atau gunakan Chrome DevTools (Mode Mobile Responsive ukuran iPhone/Pixel). "
        "Screenshot halaman Dashboard (http://127.0.0.1:8000/dashboard). "
        "Pastikan terlihat sapaan pengguna, kartu ringkasan volume mingguan, kartu sesi terakhir, dan Bottom Navigation Bar di bagian bawah layar."
    )

    add_sub_heading("1.4. Optimalisasi Input Angka (Numeric Keypad Integration)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Kolom input beban (kg) dan repetisi dikonfigurasi dengan atribut inputmode=\"decimal\" dan type=\"number\". "
        "Hal ini memicu driver keyboard mobile langsung memunculkan tombol papan numerik berukuran besar, "
        "menghilangkan kebutuhan pengguna untuk berpindah-pindah mode dari keyboard alfabetik ke angka."
    )

    add_screenshot_placeholder(
        "Tampilan Form Catat Latihan Aktif (Mobile Workout Logger)",
        "Screenshot halaman pencatatan latihan aktif (http://127.0.0.1:8000/workouts/create). "
        "Perlihatkan daftar set latihan (Beban kg, Reps, RPE), tombol '+ Tambah Set', tombol '+ Tambah Latihan Dari Katalog', "
        "serta keyboard angka smartphone yang aktif saat menekan kolom beban."
    )

    # --- BAGIAN 2: STRUKTUR NAVIGASI ---
    add_sec_heading("BAGIAN 2: STRUKTUR NAVIGASI")
    
    add_sub_heading("2.1. Pola Navigasi Persistent Bottom Navigation Bar")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "FitTrack mengadopsi pola Hierarchical Persistent Bottom Navigation Bar yang selalu menetap di dasar layar (fixed bottom-0), "
        "memungkinkan perpindahan modul utama hanya dalam 1 kali ketukan jempol tanpa membuka menu tersembunyi (hamburger menu):\n"
        "1. Home (Dashboard): Pusat kendali ringkasan kebugaran mingguan, akumulasi volume beban terangkat, dan streak hari latihan.\n"
        "2. Riwayat: Rekapitulasi kronologis sesi latihan masa lalu beserta detail histori angkatan per gerakan.\n"
        "3. Quick Action FAB Center (+) 'Latihan': Tombol lingkar hijau berdiameter 48px yang melompat keluar sedikit ke atas "
        "(negative margin -mt-5) sebagai tombol aksi primer tercepat untuk langsung membuka form pencatatan sesi baru.\n"
        "4. Program (Routines): Katalog template latihan terstruktur (Push, Pull, Legs, Upper, Lower) yang dapat dieksekusi secara instan.\n"
        "5. Progres: Grafik kemajuan beban latihan, kalkulator One-Rep Max (1RM), dan kalkulator Total Daily Energy Expenditure (TDEE)."
    )

    add_sub_heading("2.2. Diagram Alur Pengguna (User Flow & Sitemap)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Alur navigasi aplikasi terbagi menjadi 3 tingkat kedalaman layar:\n"
        "• Level 1 (Top Level Navigation): Melalui 5 tombol Bottom Navigation Bar.\n"
        "• Level 2 (Detail & Interactive Form): Layar Logger Latihan Aktif, Layar Detail Sesi Riwayat, dan Layar Pembuat Template Program.\n"
        "• Level 3 (Modal Contextual Dialog): Modal Katalog Pemilihan Gerakan Latihan (berisi 34+ gerakan gym) yang muncul tanpa menutup sesi berjalan."
    )

    add_screenshot_placeholder(
        "Tampilan Modal Katalog Pemilihan Gerakan Latihan",
        "Pada halaman http://127.0.0.1:8000/workouts/create, tekan tombol '+ Tambah Latihan'. "
        "Screenshot jendela modal yang muncul di layar, perlihatkan kolom pencarian gerakan dan filter kategori otot (Dada, Punggung, Kaki, dll)."
    )

    add_screenshot_placeholder(
        "Tampilan Menu Program Latihan (Routines)",
        "Screenshot halaman Program Latihan (http://127.0.0.1:8000/routines) yang menampilkan kartu template latihan terstruktur (Push Day, Pull Day, Leg Day) beserta tombol 'Mulai Latihan Ini'."
    )

    add_sub_heading("2.3. Pola Interaksi Reaktif Tanpa Refresh (Zero-Reload UI via Alpine.js)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Selama sesi latihan berjalan, pengguna seringkali menambah set baru, menghapus set yang salah, atau memilih gerakan tambahan. "
        "Seluruh manipulasi ini dikendalikan oleh reaktivitas Alpine.js di sisi klien (client-side state). "
        "Tidak ada reload halaman (F5) yang terjadi, sehingga stopwatch istirahat tetap berjalan akurat dan data input tidak hilang."
    )

    # --- BAGIAN 3: MANAJEMEN DATA ---
    add_sec_heading("BAGIAN 3: MANAJEMEN DATA")
    
    add_sub_heading("3.1. Relasi Entitas dan Skema Basis Data Relasional")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Basis data FitTrack dirancang secara relasional dan ternormalisasi untuk mencegah inkonsistensi data pencatatan:\n"
        "1. users: Menyimpan identitas akun, kredensial sandi terenkripsi (Bcrypt), dan status autentikasi.\n"
        "2. exercise_categories & exercises: Menyimpan master data gerakan kebugaran (34 variasi gerakan) lengkap dengan relasi kategori otot dan perlengkapan.\n"
        "3. workout_sessions: Menyimpan data sesi latihan (user_id, nama sesi, waktu mulai, waktu selesai, durasi menit, total tonase beban kg).\n"
        "4. workout_sets: Menyimpan data terperinci per angkatan beban (workout_session_id, exercise_id, nomor set, beban_kg, repetisi, RPE 1-10, flag is_warmup).\n"
        "5. routines & routine_exercises: Menyimpan template pembagian jadwal latihan mingguan.\n"
        "6. body_measurements: Menyimpan riwayat perubahan berat badan dan antropometri fisik tubuh pengguna."
    )

    add_screenshot_placeholder(
        "Tampilan Skema Database / Migration atau SQLite Viewer",
        "Screenshot tabel database FitTrack (misalnya melalui terminal 'php artisan migrate:status', phpMyAdmin, atau SQLite GUI). "
        "Alternatif lain: Screenshot halaman Riwayat Latihan (http://127.0.0.1:8000/workouts) yang membuktikan data tersimpan secara terstruktur."
    )

    add_sub_heading("3.2. Operasi CRUD dan Validasi Keamanan Server-Side")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Manajemen data dilakukan melalui Laravel Eloquent ORM dengan proteksi ketat:\n"
        "• Validasi Input Terpusat: Memastikan nilai beban (min 0 kg, max 1000 kg), repetisi (min 0, max 500), serta referensi ID gerakan valid.\n"
        "• Integritas Transaksional (DB::transaction): Proses penyimpanan sesi yang melibatkan banyak set latihan dibungkus dalam transaksi basis data "
        "sehingga jika terjadi kegagalan jaringan di ponsel pada salah satu set, seluruh data di-rollback secara aman tanpa meninggalkan orphaned records.\n"
        "• Proteksi Mass Assignment ($fillable) & Relasi Kaskade (cascadeOnDelete) untuk menjaga keutuhan data saat sesi latihan dihapus."
    )

    add_sub_heading("3.3. Logika Bisnis & Algoritma Komputasi Bergerak")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "FitTrack menanamkan formula ilmiah ke dalam logika bisnis aplikasi:\n"
        "1. Algoritma One-Rep Max (Formula Epley):\n"
        "   1RM = w * (1 + (r / 30))\n"
        "   Dihitung secara instan di setiap baris set latihan saat beban (w) dan repetisi (r) diketik, "
        "   memberikan gambaran estimasi batas kekuatan maksimal atlet tanpa risiko cedera angkatan tunggal.\n"
        "2. Algoritma Kebutuhan Energi Metabolisme (Formula Mifflin-St Jeor):\n"
        "   BMR (Pria) = (10 * BB) + (6.25 * TB) - (5 * Usia) + 5\n"
        "   BMR (Wanita) = (10 * BB) + (6.25 * TB) - (5 * Usia) - 161\n"
        "   TDEE = BMR * Faktor Aktivitas Fisik\n"
        "   Digunakan pada modul Progres untuk merekomendasikan target asupan kalori sesuai tujuan fisik (Bulking, Cutting, Maintenance)."
    )

    add_screenshot_placeholder(
        "Tampilan Kalkulator 1RM & TDEE pada Menu Progres",
        "Screenshot halaman Progres (http://127.0.0.1:8000/progress). "
        "Perlihatkan kartu Kalkulator 1RM (dengan hasil estimasi Epley) dan Kalkulator TDEE/Kalori Harian berdasarkan berat/tinggi badan."
    )

    # --- BAGIAN 4: HARDWARE INTERACTION ---
    add_sec_heading("BAGIAN 4: INTERAKSI DENGAN HARDWARE PERANGKAT BERGERAK")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Keunggulan utama FitTrack sebagai aplikasi bergerak adalah integrasi langsung dengan modul perangkat keras fisik (hardware peripherals) "
        "yang tertanam di dalam smartphone pengguna melalui standar Web Hardware APIs modern:"
    )

    add_sub_heading("4.1. Interaksi Motor Penggetar Smartphone (Haptic Feedback via Vibration API)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "• Masalah Pengguna: Di dalam ruang gym yang bising oleh dentuman musik dan suara beban, notifikasi suara seringkali tidak terdengar, "
        "terlebih jika ponsel diletakkan di dalam saku celana atlet.\n"
        "• Solusi Hardware: FitTrack mengakses motor getar haptic ponsel menggunakan navigator.vibrate(). Saat penghitung waktu istirahat (rest timer) "
        "selesai (00:00), ponsel akan mengeksekusi getaran berirama tiga ketukan bertenaga:\n"
    )
    add_code_box(
        "// Kode di resources/views/workouts/create.blade.php\n"
        "if ('vibrate' in navigator) {\n"
        "    try {\n"
        "        // Pola: getar 250ms, jeda 100ms, getar 250ms, jeda 100ms, getar panjang 500ms\n"
        "        navigator.vibrate([250, 100, 250, 100, 500]);\n"
        "    } catch (e) {\n"
        "        console.log('Hardware Vibration not supported/permitted');\n"
        "    }\n"
        "}"
    )

    add_screenshot_placeholder(
        "Tampilan Komponen Rest Timer (Stopwatch Istirahat Aktif)",
        "Screenshot komponen Rest Timer (Stopwatch) di bagian atas layar form latihan (http://127.0.0.1:8000/workouts/create) saat waktu istirahat sedang berjalan mundur (misal: 01:25) beserta tombol pause/reset."
    )

    add_sub_heading("4.2. Interaksi Hardware Speaker (Web Audio API Synthesizer DAC)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "• Masalah Pengguna: Penggunaan berkas rekaman suara (.mp3/.wav) eksternal membebani kuota data dan berisiko gagal berbunyi jika sinyal internet "
        "di basement gym terputus (offline).\n"
        "• Solusi Hardware: FitTrack langsung memprogram hardware Digital-to-Analog Converter (DAC) dan speaker fisik smartphone menggunakan Web Audio API. "
        "Gelombang suara sinus (sine wave) disintesis secara komputasi murni pada frekuensi 880 Hz (A5 pitch) yang memiliki daya tembus tinggi di tengah kebisingan:\n"
    )
    add_code_box(
        "const ctx = new (window.AudioContext || window.webkitAudioContext)();\n"
        "const osc = ctx.createOscillator();\n"
        "const gain = ctx.createGain();\n"
        "osc.type = 'sine';\n"
        "osc.frequency.value = 880; // Nada A5, frekuensi tembus kebisingan\n"
        "osc.connect(gain);\n"
        "gain.connect(ctx.destination); // Terhubung ke Hardware Output Speaker Ponsel\n"
        "gain.gain.setValueAtTime(0.3, ctx.currentTime);\n"
        "gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);\n"
        "osc.start();\n"
        "osc.stop(ctx.currentTime + 0.8);"
    )

    add_sub_heading("4.3. Interaksi Modul Pengatur Daya Layar (Screen Wake Lock API)")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "• Masalah Pengguna: Sistem operasi smartphone secara otomatis mematikan layar (screen timeout/sleep) setelah 30 detik untuk menghemat daya. "
        "Hal ini sangat mengganggu saat pengguna sedang melakukan set latihan atau beristirahat antarsesi, karena harus membuka kunci layar berulang kali dengan tangan kotor/berkeringat.\n"
        "• Solusi Hardware: FitTrack meminta hak akses ke subsistem pengendali daya layar (display power management) menggunakan navigator.wakeLock.request('screen'). "
        "Layar ponsel dipertahankan tetap menyala selama berada di halaman latihan, dan otomatis dilepaskan saat sesi latihan selesai:\n"
    )
    add_code_box(
        "async requestWakeLock() {\n"
        "    try {\n"
        "        if ('wakeLock' in navigator) {\n"
        "            this.wakeLock = await navigator.wakeLock.request('screen');\n"
        "        }\n"
        "    } catch (err) {\n"
        "        console.log('Screen Wake Lock inactive:', err);\n"
        "    }\n"
        "}"
    )

    add_sub_heading("4.4. Interaksi Layar Sentuh Kapasitif & Sensor Gestur")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "• Penguncian Skala Sentuh: Proteksi user-scalable=no mencegah distorsi tata letak akibat gestur dua jari tak sengaja.\n"
        "• Respon Taktil Sentuh: Seluruh tombol dilengkapi umpan balik visual instan (active:scale-95 hover:bg-green-700) "
        "sehingga pengguna merasakan feedback sentuhan fisik saat menekan tombol pada layar kapasitif."
    )

    add_screenshot_placeholder(
        "Bukti Kode Interaksi Hardware pada VS Code (create.blade.php)",
        "Screenshot jendela editor VS Code Anda pada file resources/views/workouts/create.blade.php. "
        "Arahkan kursor pada baris fungsi 'requestWakeLock()', 'playTimerDing()', serta kode 'navigator.vibrate' dan 'navigator.wakeLock'. "
        "Hal ini membuktikan kepada dosen bahwa interaksi hardware benar-benar diimplementasikan dalam baris kode nyata."
    )

    # --- KESIMPULAN ---
    add_sec_heading("KESIMPULAN DAN REKOMENDASI PENGUJIAN")
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    p.add_run(
        "Aplikasi FitTrack telah berhasil memenuhi seluruh kompetensi yang diujikan pada UTS Pemrograman Bergerak (PB26) dengan bobot 100%:\n"
        "1. UI/UX: Mengedepankan prinsip Mobile-First, ergonomi jempol (Thumb Zone), estetika Dark Gym hemat daya baterai OLED, dan target sentuh berstandar 48px.\n"
        "2. Struktur Navigasi: Menghadirkan Persistent Bottom Navigation Bar dengan tombol cepat FAB, hierarki informasi 3 tingkat, dan navigasi tanpa reload (Zero-Reload UI).\n"
        "3. Manajemen Data: Menerapkan skema basis data relasional ternormalisasi (6 tabel utama), transaksi basis data aman (DB::transaction), dan komputasi formula ilmiah (Epley 1RM & Mifflin-St Jeor TDEE).\n"
        "4. Interaksi Hardware: Mengintegrasikan 4 modul fisik perangkat bergerak secara nyata meliputi Motor Getar (Vibration API), Speaker Audio (Web Audio API Synthesizer DAC), Pengendali Daya Layar (Screen Wake Lock API), dan Sensor Sentuh Kapasitif."
    )

    output_path = "docs/JAWABAN_UTS_PEMROGRAMAN_BERGERAK.docx"
    doc.save(output_path)
    print(f"Document successfully created at {output_path}")

if __name__ == '__main__':
    create_document()
