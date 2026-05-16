# Alur Proyek: Sistem Rekomendasi Jurusan Kuliah

Dokumen ini menjelaskan **alur bisnis resmi** sistem: urutan admin mengisi data dan melatih model, lalu urutan siswa hingga hasil rekomendasi, dan kemampuan admin memantau hasil.

**Stack ringkas:** Laravel (backend + Blade admin) + Inertia/Vue (siswa) + FastAPI (machine learning) + MySQL. Siswa **tidak** memanggil FastAPI; hanya Laravel.

---

## 1. Admin mengisi master data

Sebelum siswa dapat rekomendasi yang konsisten, admin menyiapkan data referensi berikut (biasanya lewat panel admin):

| Data | Tabel utama | Keterangan |
|------|-------------|------------|
| Jurusan kuliah | `majors` | Nama, deskripsi; dipakai sebagai label dan hasil Top-3 |
| Kriteria minat/bakat | `criteria` | Pengelompok pertanyaan (mis. Logika, Sosial, …) |
| Pertanyaan kuesioner | `questions` | Terhubung ke `criteria_id`; flag aktif/nonaktif |
| Opsi jawaban (Likert) | `question_options` | Skor 1–5 per opsi |
| Mata pelajaran | `subjects` | Nama mapel; flag wajib/opsional untuk nilai akademik |
| Dataset training | `training_datasets` | Fitur selaras inferensi (skor per kriteria 0–100 + nilai mapel standar + `jurusan_id` sebagai label) |

**Urutan logis:** `majors` dan `criteria` lebih dulu, lalu `questions` / `question_options`, lalu `subjects`, baru **`training_datasets`** (karena butuh referensi jurusan dan struktur fitur).

---

## 2. Admin menjalankan training model

Setelah dataset siap, admin memicu **training ulang model**. Alur teknis:

1. **Laravel memvalidasi dataset** (misalnya tidak kosong, minimal dua jurusan berbeda sebagai label, setiap baris fitur lengkap).
2. **Laravel memicu proses training** (biasanya lewat **job antrian**), yang berkomunikasi dengan **FastAPI** untuk melatih model (kecuali mode stub pengembangan).
3. **Baris `ml_models` dibuat** untuk versi baru (nama, algoritma, versi, path artefak, waktu latih).
4. **Baris `training_logs` dibuat** (metrik seperti akurasi, precision, recall, F1, status sukses/gagal, pesan jika gagal).
5. **Model hasil training yang sukses** dapat ditandai **aktif** (`is_active`): secara default alur sukses mengaktifkan model baru; admin juga dapat **mengaktifkan ulang** versi lama dari halaman Model ML (hanya satu model aktif pada satu waktu).

Prediksi siswa **hanya** memakai model yang **`is_active = true`**. Training gagal tidak mengaktifkan model gagal; model aktif lama (jika ada) tetap dipakai sampai diganti.

---

## 3. Siswa register

Data yang diisi (sesuai desain auth):

- **nama** (identitas awal; detail profil bisa dilengkapi lagi di langkah profil)
- **username**
- **password**
- **password_confirmation**

Akun disimpan di **`users`** dengan role siswa; profil **`students`** dibuat/diisi sesuai implementasi registrasi.

---

## 4. Siswa login

Siswa masuk dengan **username** dan password (bukan alur login berbasis email wajib).

---

## 5. Siswa lengkapi profil

Di halaman profil siswa:

| Field | Sifat |
|-------|--------|
| **nama** | Wajib (minimal untuk identitas) |
| **nisn** | Disarankan (admin/laporan) |
| **kelas** | Disarankan |
| **asal_sekolah** | Disarankan |

Data profil disimpan pada **`students`** (terhubung `user_id`).

---

## 6. Siswa isi kuesioner

- Semua **pertanyaan aktif** wajib dijawab (skala Likert 1–5, konsisten dengan opsi yang dipilih).
- Jawaban disimpan ke **`student_answers`** (per siswa dan per pertanyaan).

Pada implementasi aplikasi, pengiriman jawaban dapat dipisah dari langkah berikutnya; yang penting secara data **jawaban sudah ada di database** sebelum proses rekomendasi.

---

## 7. Siswa input nilai akademik

- Semua **subject wajib** (`is_required = true`) harus punya nilai (rentang sesuai validasi, umumnya 0–100).
- Subject **opsional** boleh kosong (tidak dikirim sebagai pengganti sembarang jika memang kosong).
- Nilai disimpan ke **`academic_scores`** (per siswa dan per mapel).

*Di aplikasi ini, profil siswa (`PUT /user/profil`) dan nilai akademik (`GET`/`POST /user/academic-scores`) dipisah; setelah kuesioner dikirim, siswa diarahkan ke halaman nilai akademik sebelum memproses rekomendasi.*

---

## 8. Siswa klik Proses Rekomendasi

1. **Frontend** mengirim permintaan dengan **body kosong** `{}` (tidak mengirim ulang seluruh jawaban/nilai manual untuk dipercaya sebagai sumber kebenaran).
2. **Laravel** membaca **`student_answers`** dan **`academic_scores`** dari database untuk siswa yang login.
3. **Laravel menghitung skor kriteria** (rata-rata Likert per kriteria, lalu normalisasi ke **skala 0–100**) dan menyusun vektor fitur + nilai mapel standar sesuai aturan proyek.
4. **Laravel memastikan** ada **model ML aktif** dan konfigurasi layanan ML memadai.
5. **Laravel memanggil FastAPI** prediksi (atau stub di lingkungan pengembangan).
6. **Laravel menyimpan**:
   - **`recommendation_sessions`** (status: misalnya `processing` lalu `completed` atau `failed`),
   - **`recommendation_results`** (Top-3: `major_id`, score persentase, urutan rank).

Endpoint contoh implementasi: `POST /user/recommendations/process` dengan body `{}`, setelah kuesioner tersimpan dan prasyarat data terpenuhi.

---

## 9. Siswa melihat hasil

Untuk sesi yang **selesai sukses**, siswa melihat antara lain:

- **Top-3 jurusan** rekomendasi
- **Score** sebagai persentase kecocokan
- **Deskripsi jurusan** (dari data `majors`)
- **Unduh PDF** (jika fitur PDF pada proyek sudah diimplementasikan dan terhubung ke sesi tersebut)

---

## 10. Admin memantau dan mengelola operasional

Admin dapat (sesuai fitur yang tersedia di panel):

- Melihat **hasil rekomendasi siswa** (daftar/detail sesi dan Top-3)
- Melihat **statistik** ringkas (dashboard dan agregasi terkait)
- Melihat **log training** (`training_logs`) dan status tiap run
- Melihat **versi model ML** (`ml_models`) serta mengaktifkan versi tertentu untuk inferensi

---

## Referensi spesifikasi teknis

Detail rumus fitur, kontrak payload, dan aturan validasi ada di:

- `spec/spesifikasi.md`
- `spec/RULES_PERHITUNGAN.md`
- `spec/rules_sistem_admin.md`
- `spec/RULES_USERS.md`
- `spec/PROJECT_RULES.md`

---

*Sesuaikan dokumen ini jika alur UI atau endpoint berubah; urutan bisnis di atas tetap menjadi acuan utama.*
