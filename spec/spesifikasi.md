# Spesifikasi Sistem Rekomendasi Jurusan Kuliah

**Judul Proyek**  
Sistem Rekomendasi Jurusan Kuliah Berdasarkan Minat, Bakat, dan Nilai Akademik Siswa Menggunakan **Machine Learning (Scikit-learn)**

**Penulis**  
Novi Agustin (2202041102)  
Program Studi Informatika – Universitas KH. A. Wahab Hasbullah

---

## 1. Deskripsi Umum Sistem

Sistem ini adalah **aplikasi web berbasis rekomendasi jurusan kuliah** yang dirancang untuk membantu siswa SMA (khususnya MAN 3 Jombang) dalam memilih jurusan yang sesuai dengan **minat**, **bakat**, dan **nilai akademik** mereka.

Sistem dikembangkan dengan pendekatan **hybrid**:
- **Laravel 13** sebagai backend utama dan tampilan Admin
- **Inertia.js + Vue 3** untuk tampilan User/Siswa
- **Python + FastAPI** sebagai Microservice untuk Machine Learning

---

## 2. Model Machine Learning yang Digunakan

**Jenis Tugas**  
Multi-class Classification (merekomendasikan 1–3 jurusan terbaik)

**Library Machine Learning**  
**Scikit-learn** (Python)

**Algoritma yang Digunakan**  
Algoritma klasifikasi dari **Scikit-learn**:
- **Random Forest Classifier** sebagai algoritma utama
- **SVM (Support Vector Machine)** dan **KNN (K-Nearest Neighbors)** sebagai algoritma pembanding

**Alasan Pemilihan Scikit-learn**:
- Library standar yang paling umum digunakan untuk klasifikasi data tabular
- Mudah diimplementasikan dan dijelaskan untuk keperluan skripsi
- Mendukung berbagai algoritma klasifikasi seperti Random Forest, SVM, dan KNN
- Memiliki fitur evaluasi model yang lengkap (accuracy, precision, recall, F1-score)
- Cocok dengan batasan penelitian yang menyebutkan “Library Machine Learning (Scikit-learn)”

**Proses ML**:
1. **Training model** dilakukan di Python menggunakan **FastAPI**.
2. Model disimpan dalam format **`.joblib`** atau **`.pkl`**.
3. **Laravel** memanggil API FastAPI untuk mendapatkan rekomendasi.
4. Output berupa **Top-3 jurusan** beserta probabilitas kecocokan.

**Fitur Tambahan ML**:
- Retraining model melalui dashboard admin
- Logging metrik evaluasi model setiap training (accuracy, precision, recall, F1-score)
- Model versioning untuk menyimpan versi model yang aktif

---

## 3. Arsitektur Sistem

- **Frontend User** → Inertia.js + Vue 3
- **Frontend Admin** → Laravel Blade + Tailwind CSS
- **Backend** → Laravel 13 (API + Business Logic)
- **Machine Learning Service** → Python + FastAPI
- **Database** → MySQL
- **Authentication** → Laravel Fortify (custom username login)

---

## 4. Fitur-Fitur Sistem

### 4.1 Fitur Umum
- Login menggunakan **username** (bukan email)
- Role-based access control (Admin & Siswa)
- Responsive di semua perangkat
- Menggunakan **Tailwind CSS** untuk styling

### 4.2 Fitur Halaman User / Siswa (Vue + Inertia)
- Register akun siswa sendiri
- Login dengan username
- Mengisi kuesioner minat & bakat (form dinamis)
- Input nilai akademik
- Melihat hasil rekomendasi jurusan (Top-3 + persentase kecocokan)
- Melihat riwayat rekomendasi pribadi
- Download hasil rekomendasi sebagai PDF

### 4.3 Fitur Halaman Admin (Blade)
- Dashboard admin (statistik siswa, rekomendasi, akurasi model)
- CRUD Jurusan Kuliah
- CRUD Pertanyaan Kuesioner
- Manajemen Data Siswa (filter jurusan, kelas)
- Melihat rata-rata minat siswa (filter jurusan, kelas)
- **Training ulang model Machine Learning** (retrain)
- Melihat log training dan metrik evaluasi
- Manajemen akun admin (tambah admin baru)
- Laporan dan export data
- Fitur search berdasarkan nama atau NISN di semua tabel relevan

### 4.4 Fitur Keamanan & Autentikasi
- Login dengan username
- Password hashing (Laravel default)
- Role middleware
- Akun superadmin pertama kali hanya dibuat melalui Seeder (tidak bisa register sendiri)
- Akun admin hanya bisa ditambahkan oleh role superAdmin

---

## 5. Tech Stack Lengkap

**Backend**  
- Laravel 13 (PHP 8.3+)
- MySQL

**Frontend**  
- Blade + Tailwind CSS (Admin)
- Inertia.js + Vue 3 + Tailwind CSS (User)

**Machine Learning**  
- Python
- FastAPI
- **Scikit-learn** (Random Forest, SVM, KNN)

**Lainnya**  
- Laravel Fortify
- Job Queue (untuk training model)
- PDF generation

---

## 6. Target Pengguna

- **Siswa** → siswa MAN 3 Jombang (tampilan User)
- **Guru Bimbingan Konseling** → admin sistem (tampilan Admin)

---

## 7. Alur Kerja Sistem

1. **Siswa mengisi data diri** (mendaftar/login dengan username) → **mengisi kuesioner minat dan bakat** → **menginput nilai akademik**
2. **Backend (Laravel)** memvalidasi input dan menghitung skor minat, bakat, dan nilai akademik.
3. **Laravel** mengirimkan data skor ke **FastAPI**.
4. **FastAPI (Python)** memuat model machine learning yang telah dilatih menggunakan **Random Forest**, kemudian memberikan **Top-3 rekomendasi jurusan** beserta **probabilitas** kecocokannya.
5. **Laravel** menerima hasil dan menampilkan rekomendasi jurusan pada halaman **User**.
6. **Admin** dapat melihat log training, melakukan retraining model, dan mengeksport laporan hasil rekomendasi serta statistik.

---

## 8. Struktur Database

| Tabel | Fungsi |
|---|---|
| users | Akun superadmin, admin, siswa |
| students | Profil siswa (NISN, kelas, data diri) |
| majors | Data jurusan kuliah |
| criteria | Kriteria minat/bakat (logika, sosial, bahasa, kreativitas, dll.) |
| questions | Pertanyaan kuesioner |
| question_options | Opsi jawaban Likert |
| student_answers | Jawaban siswa pada kuesioner |
| academic_scores | Nilai akademik siswa |
| recommendation_sessions | Sesi tes/rekomendasi |
| recommendation_results | Hasil rekomendasi Top-3 |
| training_datasets | Data untuk training model |
| ml_models | Versi model machine learning |
| training_logs | Log training dan metrik evaluasi |

---
