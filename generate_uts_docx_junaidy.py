import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

def set_cell_background(cell, fill_hex):
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    cell._tc.get_or_add_tcPr().append(shd)

def set_cell_margins(cell, top=80, bottom=80, left=120, right=120):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
    tcPr.append(tcMar)

def set_table_borders(table, color="CCCCCC", sz="4", val="single"):
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'<w:top w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'<w:bottom w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'<w:left w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'<w:insideH w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'<w:insideV w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

def build_docx():
    doc = Document()

    # A4 Page Margins (1 inch / 2.54 cm all sides)
    section = doc.sections[0]
    section.page_width = Inches(8.27)
    section.page_height = Inches(11.69)
    section.top_margin = Inches(1.0)
    section.bottom_margin = Inches(1.0)
    section.left_margin = Inches(1.0)
    section.right_margin = Inches(1.0)

    # Base Font: Calibri / Arial style clean look like UTS_JunaidyMuhtarom
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Calibri'
    normal_style.font.size = Pt(11)
    normal_style.font.color.rgb = RGBColor(15, 23, 42)

    # 1. Header Information (Identitas Mahasiswa persis seperti format Junaidy)
    p_hdr = doc.add_paragraph()
    p_hdr.paragraph_format.space_after = Pt(12)
    p_hdr.paragraph_format.line_spacing = 1.15
    
    r_hdr = p_hdr.add_run(
        "Nama: Aufal Wafi\n"
        "NIM: 2305101101\n"
        "Mata Kuliah: Pemrograman Bergerak\n"
        "Link repositori GitHub: https://github.com/Aufalwafi/Gym-App.git"
    )
    r_hdr.font.size = Pt(11)
    r_hdr.bold = True

    # Helper: Numbered Heading style (1. Gambaran Aplikasi, 2. Rancangan UI/UX, dst.)
    def add_num_heading(num_title):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(14)
        p.paragraph_format.space_after = Pt(5)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(num_title)
        run.bold = True
        run.font.size = Pt(12)
        run.font.color.rgb = RGBColor(0, 0, 0)
        return p

    def add_screenshot_box(screen_title, note):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(6)
        p.paragraph_format.space_after = Pt(2)
        r_t = p.add_run(f"{screen_title}")
        r_t.bold = True
        r_t.font.size = Pt(11)

        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        cell = tbl.cell(0, 0)
        cell.width = Inches(6.27)
        set_cell_background(cell, "F9FAFB") # Light gray
        set_cell_margins(cell, top=120, bottom=120, left=150, right=150)
        
        tcPr = cell._tc.get_or_add_tcPr()
        borders = parse_xml(f'<w:tcBorders {nsdecls("w")}><w:top w:val="dashed" w:sz="6" w:space="0" w:color="9CA3AF"/><w:left w:val="dashed" w:sz="6" w:space="0" w:color="9CA3AF"/><w:bottom w:val="dashed" w:sz="6" w:space="0" w:color="9CA3AF"/><w:right w:val="dashed" w:sz="6" w:space="0" w:color="9CA3AF"/></w:tcBorders>')
        tcPr.append(borders)

        cp = cell.paragraphs[0]
        cp.alignment = WD_ALIGN_PARAGRAPH.CENTER
        cp.paragraph_format.space_after = Pt(2)
        cr1 = cp.add_run(f"[TEMPATKAN SCREENSHOT DI SINI: {screen_title}]\n")
        cr1.bold = True
        cr1.font.size = Pt(10)
        cr1.font.color.rgb = RGBColor(31, 41, 55)

        cr2 = cp.add_run(f"({note})")
        cr2.font.italic = True
        cr2.font.size = Pt(9.5)
        cr2.font.color.rgb = RGBColor(107, 114, 128)

        p_end = doc.add_paragraph()
        p_end.paragraph_format.space_after = Pt(6)

    # ==========================================
    # 1. GAMBARAN APLIKASI
    # ==========================================
    add_num_heading("1. Gambaran Aplikasi")
    p1 = doc.add_paragraph()
    p1.paragraph_format.space_after = Pt(8)
    p1.paragraph_format.line_spacing = 1.15
    p1.add_run(
        "FitTrack adalah aplikasi perangkat bergerak untuk mencatat, memantau, dan menganalisis "
        "latihan kebugaran beban (gym workout & progressive overload) dalam satu tempat. Pengguna dapat "
        "memilih program latihan terstruktur, mencatat beban (kg) dan repetisi setiap set angkatan, "
        "mengukur waktu istirahat antarsesi (rest timer) dengan getaran haptic dan suara bel terintegrasi, "
        "menghitung estimasi kekuatan maksimal (One-Rep Max / 1RM) secara otomatis, serta memantau progres "
        "volume latihan mingguan dan perkembangan berat serta ukuran lingkar tubuh.\n\n"
        "Desain antarmuka dibuat berestetika Dark Gym monokrom-hijau (latar gelap zinc dan aksen hijau solid) "
        "agar bersih, kontras tinggi, hemat baterai perangkat bertipe layar OLED/AMOLED, dan mempermudah "
        "pencatatan saat pengguna beraktivitas fisik di gym."
    )

    # ==========================================
    # 2. RANCANGAN UI/UX
    # ==========================================
    add_num_heading("2. Rancangan UI/UX")
    p2 = doc.add_paragraph()
    p2.paragraph_format.space_after = Pt(6)
    p2.add_run("Rancangan antarmuka FitTrack terdiri dari 9 layar utama:")

    # Tabel Layar Utama
    tbl_screens = doc.add_table(rows=10, cols=3)
    tbl_screens.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(tbl_screens, color="CBD5E1", sz="4")

    headers_screens = ["No", "Layar", "Fungsi dan Elemen Utama"]
    col_w_screens = [Inches(0.5), Inches(2.0), Inches(3.77)]
    for i, h in enumerate(headers_screens):
        c = tbl_screens.rows[0].cells[i]
        c.width = col_w_screens[i]
        set_cell_background(c, "F1F5F9")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(10)

    screens_data = [
        ("1", "Login / Registrasi", "Form email dan kata sandi, tombol masuk, tautan daftar akun baru, status validasi error."),
        ("2", "Beranda (Dashboard)", "Sapaan pengguna, target kebugaran (Bulking/Cutting), ringkasan volume mingguan, streak latihan, kartu sesi terakhir, dan tombol cepat mulai latihan baru."),
        ("3", "Catat Latihan Aktif", "Form pencatatan sesi aktif: daftar gerakan, baris input beban (kg) & reps dengan auto-numpad, nilai RPE, tombol tambah set, kalkulator 1RM instan, dan tombol simpan sesi."),
        ("4", "Rest Timer (Stopwatch)", "Komponen hitung mundur istirahat antarsesi (misal 90 detik), tombol jeda/reset, serta pemicu getaran hardware dan audio synthesizer saat waktu habis."),
        ("5", "Modal Tambah Gerakan", "Jendela pop-up modal pemilihan gerakan dari 34+ katalog gym dengan kolom pencarian cepat tanpa perlu menutup form sesi latihan aktif."),
        ("6", "Katalog Latihan", "Daftar lengkap gerakan latihan kebugaran terkelompokkan berdasarkan kategori otot (Dada, Punggung, Kaki, Bahu, Lengan, Perut) dan peralatan."),
        ("7", "Riwayat Latihan", "Daftar kronologis seluruh sesi latihan masa lalu lengkap dengan tanggal pelaksanaan, durasi waktu, total tonase beban (kg), dan rincian beban per gerakan."),
        ("8", "Program Latihan (Routines)", "Katalog template latihan terstruktur (Push Day, Pull Day, Leg Day, Upper, Lower) beserta tombol 'Mulai Latihan Ini' untuk mengeksekusi template langsung."),
        ("9", "Progres & Kalkulator", "Grafik tren perkembangan volume beban angkatan mingguan, riwayat timbangan berat badan, Kalkulator 1RM (Formula Epley), dan Kalkulator Kalori Harian (TDEE).")
    ]

    for row_idx, data in enumerate(screens_data, start=1):
        for col_idx, text in enumerate(data):
            c = tbl_screens.rows[row_idx].cells[col_idx]
            c.width = col_w_screens[col_idx]
            set_cell_margins(c, 60, 60, 100, 100)
            p = c.paragraphs[0]
            p.paragraph_format.space_after = Pt(2)
            r = p.add_run(text)
            r.font.size = Pt(9.5)

    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_before = Pt(8)
    p_sp.paragraph_format.space_after = Pt(4)
    r_sub = p_sp.add_run("Berikut Gambar Rancangan UI/UX nya")
    r_sub.bold = True
    r_sub.font.size = Pt(11)

    # Screenshots Placeholders sesuai 9 Layar
    add_screenshot_box("1. Login / Registrasi", "Buka http://127.0.0.1:8000/login di browser mode mobile atau HP, screenshot layar login lengkap dengan tombol masuk dan form email/password.")
    add_screenshot_box("2. Beranda (Dashboard)", "Buka http://127.0.0.1:8000/dashboard, screenshot tampilan mobile yang memperlihatkan sapaan, kartu ringkasan volume mingguan, sesi terakhir, dan Bottom Navigation Bar.")
    add_screenshot_box("3. Catat Latihan Aktif (Live Workout Logger)", "Buka http://127.0.0.1:8000/workouts/create, screenshot form pengisian baris set beban (kg), repetisi, tombol '+ Tambah Set', dan tombol selesai.")
    add_screenshot_box("4. Rest Timer (Stopwatch Istirahat)", "Pada halaman form latihan aktif, screenshot komponen Rest Timer saat sedang berdetak mundur (misal 01:25) beserta tombol Play/Reset.")
    add_screenshot_box("5. Modal Tambah Gerakan", "Pada form latihan aktif, tekan tombol '+ Tambah Latihan Dari Katalog', screenshot modal pop-up yang muncul di atas layar form latihan.")
    add_screenshot_box("6. Katalog Latihan (Exercise Catalog)", "Buka http://127.0.0.1:8000/exercises, screenshot daftar gerakan gym beserta filter kategori otot (Dada, Punggung, Bahu, Kaki, dll).")
    add_screenshot_box("7. Riwayat Latihan (Workout History)", "Buka http://127.0.0.1:8000/workouts, screenshot daftar kartu riwayat sesi latihan masa lalu yang memuat durasi dan total volume angkatan.")
    add_screenshot_box("8. Program Latihan (Routines)", "Buka http://127.0.0.1:8000/routines, screenshot tampilan kartu program latihan (Push Day, Pull Day, Leg Day) beserta tombol 'Mulai Latihan Ini'.")
    add_screenshot_box("9. Progres & Kalkulator (1RM & TDEE)", "Buka http://127.0.0.1:8000/progress, screenshot grafik progres angkatan serta kartu Kalkulator 1RM (Epley) dan Kalkulator Kalori TDEE.")
    add_screenshot_box("10. Dialog dan State Pendukung", "Screenshot tampilan pesan validasi error pengisian form, konfirmasi hapus set latihan, atau notifikasi toast berhasil simpan sesi.")

    # Prinsip UX
    p_ux = doc.add_paragraph()
    p_ux.paragraph_format.space_before = Pt(8)
    p_ux.paragraph_format.space_after = Pt(4)
    r_ux = p_ux.add_run("Prinsip UX")
    r_ux.bold = True
    r_ux.font.size = Pt(11)

    p_ux_pts = doc.add_paragraph()
    p_ux_pts.paragraph_format.space_after = Pt(8)
    p_ux_pts.paragraph_format.line_spacing = 1.15
    p_ux_pts.add_run(
        "• Konsisten: Bilah navigasi bawah (bottom navigation), tipografi Plus Jakarta Sans, tombol aksi, dan jarak margin sama di seluruh layar.\n"
        "• Sederhana: Memulai sesi latihan baru hanya butuh 1 ketukan cepat dari tombol lingkaran (+) di bilah navigasi Beranda.\n"
        "• Kontras tinggi Dark Gym: Perpaduan warna gelap solid (Zinc-950 #09090b) dan hijau solid (#16a34a) membuat teks angka terbaca sangat jelas di gym temaram serta menghemat baterai layar AMOLED.\n"
        "• Ergonomi Jempol (Thumb Zone): Semua tombol interaksi penting dan input berada di sepertiga bawah layar, ukuran target sentuh minimal 48x48 px, dan keyboard numerik langsung aktif saat kolom beban ditekan.\n"
        "• Umpan balik jelas: Umpan balik getaran fisik (vibrate) dan nada audio saat istirahat selesai, status validasi form yang jelas, dan indikator penyimpanan data."
    )

    # ==========================================
    # 3. STRUKTUR NAVIGASI
    # ==========================================
    add_num_heading("3. Struktur Navigasi")
    p3 = doc.add_paragraph()
    p3.paragraph_format.space_after = Pt(6)
    p3.add_run(
        "Aplikasi memakai navigasi bilah bawah (bottom navigation) dengan 4 tab utama, ditambah "
        "tombol aksi tengah (+) untuk langsung memulai pencatatan latihan baru."
    )

    # Tabel Navigasi
    tbl_nav = doc.add_table(rows=7, cols=2)
    tbl_nav.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(tbl_nav, color="CBD5E1", sz="4")

    headers_nav = ["Tingkat", "Isi"]
    col_w_nav = [Inches(2.2), Inches(4.07)]
    for i, h in enumerate(headers_nav):
        c = tbl_nav.rows[0].cells[i]
        c.width = col_w_nav[i]
        set_cell_background(c, "F1F5F9")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(10)

    nav_data = [
        ("Awal", "Login / Registrasi → (berhasil) → Beranda (Dashboard)"),
        ("Tab utama", "Beranda · Riwayat · Program Latihan · Progres & Kalkulator"),
        ("Tombol tengah (+)", "Mulai Latihan Baru → Pilih Gerakan Modal → Input Set/Beban → Rest Timer → Simpan → Beranda/Riwayat"),
        ("Dari Beranda", "Ketuk kartu sesi terakhir → Detail Riwayat Sesi, atau tombol 'Mulai Latihan Baru'"),
        ("Dari Program Latihan", "Ketuk tombol 'Mulai Latihan Ini' pada template Push/Pull/Legs → langsung memuat template ke form Catat Latihan Aktif"),
        ("Dari Pengaturan / Profil", "Ubah profil target kebugaran (Bulking/Cutting) · Keluar akun → Login")
    ]

    for row_idx, data in enumerate(nav_data, start=1):
        for col_idx, text in enumerate(data):
            c = tbl_nav.rows[row_idx].cells[col_idx]
            c.width = col_w_nav[col_idx]
            set_cell_margins(c, 60, 60, 100, 100)
            p = c.paragraphs[0]
            p.paragraph_format.space_after = Pt(2)
            r = p.add_run(text)
            r.font.size = Pt(9.5)

    p_nav_flow = doc.add_paragraph()
    p_nav_flow.paragraph_format.space_before = Pt(8)
    p_nav_flow.paragraph_format.space_after = Pt(8)
    p_nav_flow.paragraph_format.line_spacing = 1.15
    p_nav_flow.add_run(
        "Alur utama: Login → Beranda → (+) Latihan → Tambah Set & Gerakan → Rest Timer (Getar/Audio) → Simpan → Riwayat → Progres. "
        "Tombol kembali peramban selalu menuju layar sebelumnya, dan dari tab mana pun pengguna bisa berpindah tab secara cepat melalui "
        "bilah navigasi bawah tanpa kehilangan posisi aktif."
    )

    # ==========================================
    # 4. MANAJEMEN DATA
    # ==========================================
    add_num_heading("4. Manajemen Data")
    p4 = doc.add_paragraph()
    p4.paragraph_format.space_after = Pt(6)
    p4.add_run(
        "Data disimpan dalam basis data relasional SQLite / MySQL dengan pemodelan Eloquent ORM di sisi server Laravel, "
        "serta didukung penanganan reaktivitas lokal (Alpine.js) di sisi klien agar pencatatan set tetap berjalan lancar saat berada di area gym."
    )

    # Tabel Skema Basis Data
    tbl_db = doc.add_table(rows=8, cols=3)
    tbl_db.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(tbl_db, color="CBD5E1", sz="4")

    headers_db = ["Tabel", "Field Utama", "Keterangan"]
    col_w_db = [Inches(1.5), Inches(2.6), Inches(2.17)]
    for i, h in enumerate(headers_db):
        c = tbl_db.rows[0].cells[i]
        c.width = col_w_db[i]
        set_cell_background(c, "F1F5F9")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(10)

    db_data = [
        ("users", "id, name, email, password, target_goal, created_at", "Data akun pengguna/atlet (id = kunci utama)."),
        ("exercises", "id, category_id, name, target_muscle, equipment", "Master katalog gerakan kebugaran (34 variasi gerakan)."),
        ("exercise_categories", "id, name, slug", "Kategori otot: Dada, Punggung, Kaki, Bahu, Lengan, Perut."),
        ("workout_sessions", "id, user_id, name, started_at, ended_at, duration_minutes, total_volume_kg", "Catatan sesi latihan milik satu pengguna."),
        ("workout_sets", "id, workout_session_id, exercise_id, set_number, weight_kg, reps, rpe, is_warmup", "Data granular per set angkatan beban dan repetisi."),
        ("routines", "id, user_id, name, target_days", "Template program latihan mingguan (Push, Pull, Legs)."),
        ("body_measurements", "id, user_id, recorded_date, weight_kg, chest_cm, waist_cm, arm_cm", "Pencatatan perkembangan metrik antropometri tubuh.")
    ]

    for row_idx, data in enumerate(db_data, start=1):
        for col_idx, text in enumerate(data):
            c = tbl_db.rows[row_idx].cells[col_idx]
            c.width = col_w_db[col_idx]
            set_cell_margins(c, 60, 60, 100, 100)
            p = c.paragraphs[0]
            p.paragraph_format.space_after = Pt(2)
            r = p.add_run(text)
            r.font.size = Pt(9.5)

    p_db_rules = doc.add_paragraph()
    p_db_rules.paragraph_format.space_before = Pt(8)
    p_db_rules.paragraph_format.space_after = Pt(4)
    r_rules = p_db_rules.add_run("Aturan pengelolaan data")
    r_rules.bold = True
    r_rules.font.size = Pt(11)

    p_rules_pts = doc.add_paragraph()
    p_rules_pts.paragraph_format.space_after = Pt(8)
    p_rules_pts.paragraph_format.line_spacing = 1.15
    p_rules_pts.add_run(
        "• CRUD: Tambah, lihat, ubah, dan hapus sesi latihan, set angkatan, template program latihan, serta log antropometri tubuh.\n"
        "• Integritas Transaksional (DB::transaction): Penyimpanan sesi latihan dan set angkatan dibungkus dalam transaksi basis data untuk mencegah data tersimpan sebagian jika terjadi gangguan koneksi.\n"
        "• Keamanan: Validasi server-side memastikan nilai beban (0-1000 kg) dan repetisi (0-500) valid, proteksi CSRF token di setiap request, dan relasi kaskade (cascadeOnDelete) menjaga konsistensi database.\n"
        "• Komputasi Ilmiah Real-Time: Nilai One-Rep Max (Formula Epley: 1RM = w * (1 + r/30)) dan Total Daily Energy Expenditure (Formula Mifflin-St Jeor) dihitung secara instan saat data diisi."
    )

    # ==========================================
    # 5. INTERAKSI DENGAN HARDWARE PERANGKAT BERGERAK
    # ==========================================
    add_num_heading("5. Interaksi dengan Hardware Perangkat Bergerak")
    p5 = doc.add_paragraph()
    p5.paragraph_format.space_after = Pt(6)
    p5.add_run("FitTrack memanfaatkan berbagai fitur dan modul perangkat keras smartphone secara langsung:")

    # Tabel Hardware
    tbl_hw = doc.add_table(rows=6, cols=3)
    tbl_hw.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(tbl_hw, color="CBD5E1", sz="4")

    headers_hw = ["Hardware", "Layar", "Penggunaan"]
    col_w_hw = [Inches(1.8), Inches(1.8), Inches(2.67)]
    for i, h in enumerate(headers_hw):
        c = tbl_hw.rows[0].cells[i]
        c.width = col_w_hw[i]
        set_cell_background(c, "F1F5F9")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(10)

    hw_data = [
        ("Layar / Daya (Screen Wake Lock)", "Catat Latihan Aktif, Rest Timer", "Mencegah layar smartphone mati otomatis (screen sleep/lock) menggunakan API navigator.wakeLock saat atlet sedang melakukan angkatan atau istirahat."),
        ("Motor Getar (Haptic Feedback)", "Rest Timer, Selesai Sesi", "Menggetarkan perangkat fisik ponsel (Vibration API navigator.vibrate([250, 100, 250, 100, 500])) saat hitung mundur istirahat habis agar terasa di saku celana di tengah bisingnya gym."),
        ("Speaker / DAC Audio Synthesizer", "Rest Timer", "Menyintesis sinyal gelombang sinus frekuensi tinggi 880 Hz (A5 pitch) via Web Audio API langsung ke chip speaker fisik ponsel tanpa perlu mengunduh file mp3."),
        ("Layar Sentuh (Touchscreen & Numpad)", "Catat Latihan Aktif, Semua Layar", "Memanggil papan ketik angka (inputmode=\"decimal\") secara otomatis untuk kecepatan pengisian beban/reps dan mengunci gestur pinch-zoom (user-scalable=no)."),
        ("Jaringan & Offline Cache", "Semua Layar", "Menjalankan state sesi latihan secara lokal di peramban pengguna (Alpine.js) sehingga form latihan tidak macet saat sinyal internet gym lemah.")
    ]

    for row_idx, data in enumerate(hw_data, start=1):
        for col_idx, text in enumerate(data):
            c = tbl_hw.rows[row_idx].cells[col_idx]
            c.width = col_w_hw[col_idx]
            set_cell_margins(c, 60, 60, 100, 100)
            p = c.paragraphs[0]
            p.paragraph_format.space_after = Pt(2)
            r = p.add_run(text)
            r.font.size = Pt(9.5)

    add_screenshot_box(
        "Bukti Baris Kode Interaksi Hardware di Editor VS Code",
        "Buka file resources/views/workouts/create.blade.php di VS Code, screenshot bagian baris kode fungsi requestWakeLock(), navigator.vibrate, dan playTimerDing() sebagai bukti implementasi hardware."
    )

    p_perm = doc.add_paragraph()
    p_perm.paragraph_format.space_before = Pt(6)
    p_perm.paragraph_format.space_after = Pt(8)
    p_perm.paragraph_format.line_spacing = 1.15
    p_perm.add_run(
        "Izin (permission) diminta secara transparan hanya saat fitur berjalan (seperti hak akses Wake Lock dan motor getar peramban). "
        "Jika fitur getar atau wake lock tidak didukung pada perangkat peramban lama, aplikasi secara otomatis menyediakan fallback visual "
        "sehingga alur pencatatan latihan tetap berjalan dengan lancar tanpa kendala."
    )

    # ==========================================
    # 6. PENUTUP
    # ==========================================
    add_num_heading("6. Penutup")
    p6 = doc.add_paragraph()
    p6.paragraph_format.space_after = Pt(8)
    p6.paragraph_format.line_spacing = 1.15
    p6.add_run(
        "Rancangan FitTrack mencakup UI/UX 9 layar berorientasi mobile-first, struktur navigasi bilah bawah (bottom navigation) "
        "dengan tombol cepat (+), manajemen data relasional ternormalisasi dengan komputasi formula ilmiah kebugaran (1RM & TDEE), "
        "serta pemanfaatan modul perangkat keras fisik smartphone (Screen Wake Lock, Motor Getar Haptic, Audio Synthesizer Speaker, "
        "dan Layar Sentuh Numpad). Repositori dan kode sumber lengkap dapat diakses pada tautan GitHub di bagian awal dokumen."
    )

    # Save to UTS_AufalWafi.docx (matching UTS_JunaidyMuhtarom format)
    doc.save("UTS_AufalWafi.docx")
    doc.save("docs/UTS_AufalWafi.docx")
    print("Document successfully created as UTS_AufalWafi.docx and docs/UTS_AufalWafi.docx")

if __name__ == '__main__':
    build_docx()
