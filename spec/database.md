# Desain Database

## 1. Tabel `users`
Menyimpan data pengguna (admin, siswa, superadmin).

**Kolom:**
- `id`: Primary key
- `username`: Username unik
- `email`: Opsional, bisa kosong jika menggunakan username
- `password`: Password yang sudah di-hash
- `role`: Role pengguna (superadmin, admin, siswa)
- `created_at`: Tanggal pembuatan akun
- `updated_at`: Tanggal pembaruan akun

---

## 2. Tabel `students`
Menyimpan profil siswa.

**Kolom:**
- `id`: Primary key
- `user_id`: Foreign key yang mengacu ke `users.id`
- `nisn`: Nomor Induk Siswa Nasional
- `nama`: Nama siswa
- `kelas`: Kelas siswa (misalnya: XII IPA 1)
- `asal_sekolah`: Nama sekolah
- `created_at`: Tanggal pembuatan data siswa
- `updated_at`: Tanggal pembaruan data siswa

---

## 3. Tabel `majors`
Menyimpan data jurusan kuliah yang dapat direkomendasikan.

**Kolom:**
- `id`: Primary key
- `nama_jurusan`: Nama jurusan (misalnya: Teknik Informatika, Psikologi)
- `deskripsi`: Deskripsi singkat tentang jurusan
- `created_at`: Tanggal pembuatan jurusan
- `updated_at`: Tanggal pembaruan jurusan

---

## 4. Tabel `criteria`
Menyimpan kriteria minat dan bakat yang digunakan dalam kuesioner.

**Kolom:**
- `id`: Primary key
- `nama_kriteria`: Nama kriteria (misalnya: logika, sosial, bahasa)
- `deskripsi`: Deskripsi kriteria
- `created_at`: Tanggal pembuatan kriteria
- `updated_at`: Tanggal pembaruan kriteria

---

## 5. Tabel `questions`
Menyimpan pertanyaan kuesioner minat dan bakat.

**Kolom:**
- `id`: Primary key
- `criteria_id`: Foreign key yang mengacu ke `criteria.id`
- `pertanyaan`: Isi pertanyaan
- `created_at`: Tanggal pembuatan pertanyaan
- `updated_at`: Tanggal pembaruan pertanyaan

---

## 6. Tabel `question_options`
Menyimpan opsi jawaban untuk setiap pertanyaan (misalnya: Sangat Tidak Setuju, Tidak Setuju, Netral, Setuju, Sangat Setuju).

**Kolom:**
- `id`: Primary key
- `question_id`: Foreign key yang mengacu ke `questions.id`
- `opsi`: Opsi jawaban (misalnya: Sangat Tidak Setuju)
- `created_at`: Tanggal pembuatan opsi
- `updated_at`: Tanggal pembaruan opsi

---

## 7. Tabel `student_answers`
Menyimpan jawaban siswa terhadap setiap pertanyaan di kuesioner.

**Kolom:**
- `id`: Primary key
- `student_id`: Foreign key yang mengacu ke `students.id`
- `question_id`: Foreign key yang mengacu ke `questions.id`
- `answer`: Jawaban siswa (misalnya: 1 = Sangat Tidak Setuju, 5 = Sangat Setuju)
- `created_at`: Tanggal pengisian jawaban
- `updated_at`: Tanggal pembaruan jawaban

---

## 8. Tabel `academic_scores`
Menyimpan nilai akademik siswa, seperti nilai matematika, bahasa Indonesia, bahasa Inggris, dll.

**Kolom:**
- `id`: Primary key
- `student_id`: Foreign key yang mengacu ke `students.id`
- `subject_id`: Foreign key yang mengacu ke `subjects.id`
- `nilai`: Nilai yang diperoleh siswa pada mata pelajaran tersebut
- `is_required`: Boolean untuk menandai apakah mata pelajaran ini wajib diisi (true) atau opsional (false)
- `created_at`: Tanggal pembuatan nilai
- `updated_at`: Tanggal pembaruan nilai

---

## 9. Tabel `subjects`
Menyimpan mata pelajaran yang dapat ditambahkan oleh admin dan digunakan dalam `academic_scores`.

**Kolom:**
- `id`: Primary key
- `nama_mapel`: Nama mata pelajaran (misalnya: Matematika, Bahasa Indonesia, Fisika, dll.)
- `is_required`: Boolean untuk menandai apakah mata pelajaran ini wajib diisi (true) atau opsional (false)
- `created_at`: Tanggal pembuatan mata pelajaran
- `updated_at`: Tanggal pembaruan mata pelajaran

---

## 10. Tabel `recommendation_sessions`
Menyimpan sesi tes dan rekomendasi yang dihasilkan untuk setiap siswa.

**Kolom:**
- `id`: Primary key
- `student_id`: Foreign key yang mengacu ke `students.id`
- `created_at`: Tanggal tes dimulai
- `updated_at`: Tanggal tes selesai

---

## 11. Tabel `recommendation_results`
Menyimpan hasil rekomendasi jurusan untuk siswa, termasuk probabilitas atau skor kecocokan.

**Kolom:**
- `id`: Primary key
- `session_id`: Foreign key yang mengacu ke `recommendation_sessions.id`
- `major_id`: Foreign key yang mengacu ke `majors.id`
- `score`: Skor kecocokan untuk jurusan
- `created_at`: Tanggal rekomendasi dibuat
- `updated_at`: Tanggal pembaruan rekomendasi

---

## 12. Tabel `training_datasets`
Menyimpan data yang digunakan untuk melatih model machine learning.

**Kolom:**
- `id`: Primary key
- `criteria_id`: Foreign key yang mengacu ke `criteria.id`
- `nilai_mtk`: Nilai akademik yang digunakan untuk training
- `nilai_bindo`: Nilai bahasa Indonesia yang digunakan untuk training
- `nilai_bing`: Nilai bahasa Inggris yang digunakan untuk training
- `nilai_ipa`: Nilai IPA yang digunakan untuk training
- `nilai_ips`: Nilai IPS yang digunakan untuk training
- `jurusan_id`: Foreign key yang mengacu ke `majors.id` (label jurusan)
- `created_at`: Tanggal pembuatan data training
- `updated_at`: Tanggal pembaruan data training

---

## 13. Tabel `ml_models`
Menyimpan informasi tentang versi model machine learning yang digunakan.

**Kolom:**
- `id`: Primary key
- `model_name`: Nama model (misalnya: Random Forest, SVM)
- `version`: Versi model (misalnya: v1, v2)
- `trained_at`: Tanggal model dilatih
- `is_active`: Status model apakah aktif atau tidak
- `created_at`: Tanggal pembuatan data model
- `updated_at`: Tanggal pembaruan data model

---

## 14. Tabel `training_logs`
Menyimpan log setiap proses training model beserta metrik evaluasi.

**Kolom:**
- `id`: Primary key
- `model_id`: Foreign key yang mengacu ke `ml_models.id`
- `accuracy`: Akurasi model
- `precision`: Precision model
- `recall`: Recall model
- `f1_score`: F1-score model
- `created_at`: Tanggal proses training
- `updated_at`: Tanggal pembaruan log training