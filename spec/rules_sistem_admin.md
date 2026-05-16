# RULES ALUR SISTEM ADMIN
# Sistem Rekomendasi Jurusan Kuliah
# Laravel Blade Admin + MySQL + FastAPI Machine Learning

## 1. Tujuan Rules

Rules ini digunakan sebagai panduan alur fitur admin agar sesuai dengan struktur database sistem rekomendasi jurusan kuliah.

Admin bertugas mengelola:

- Dashboard statistik
- Data jurusan kuliah
- Data kriteria minat dan bakat
- Data pertanyaan kuesioner
- Data opsi jawaban kuesioner
- Data mata pelajaran
- Data siswa
- Data nilai akademik siswa
- Data jawaban siswa
- Proses training model Machine Learning
- Versi model Machine Learning
- Log training model
- Hasil rekomendasi siswa
- Laporan dan export data
- Akun admin

---

## 2. Role Admin

Terdapat 3 role pada tabel users:

- superadmin
- admin
- siswa

Aturan akses:

1. superadmin boleh mengakses semua fitur admin.
2. superadmin boleh membuat akun admin baru.
3. admin boleh mengelola data sistem, tetapi tidak boleh membuat superadmin.
4. siswa tidak boleh mengakses halaman admin.
5. semua halaman admin wajib menggunakan middleware auth dan role.

Contoh middleware:

auth
role:superadmin,admin

Khusus manajemen akun admin:

auth
role:superadmin

---

## 3. Alur Login Admin

Database terkait:

- users

Alur:

1. Admin membuka halaman login.
2. Admin login menggunakan username dan password.
3. Sistem mencari user berdasarkan username.
4. Sistem memvalidasi password yang sudah di-hash.
5. Sistem mengecek role user.
6. Jika role adalah superadmin atau admin, arahkan ke dashboard admin.
7. Jika role siswa, tolak akses ke admin.
8. Jika username atau password salah, tampilkan pesan error.

Rules:

1. Login menggunakan username, bukan email.
2. Password tidak boleh disimpan dalam bentuk plain text.
3. Admin dan superadmin berasal dari tabel users.
4. Siswa juga berada di tabel users, tetapi tidak boleh masuk area admin.
5. Middleware role wajib diterapkan pada route admin.

---

## 4. Alur Dashboard Admin

Database terkait:

- users
- students
- majors
- criteria
- questions
- student_answers
- academic_scores
- recommendation_sessions
- recommendation_results
- ml_models
- training_logs

Data yang ditampilkan:

1. Total siswa.
2. Total jurusan.
3. Total kriteria.
4. Total pertanyaan.
5. Total sesi rekomendasi.
6. Total rekomendasi selesai.
7. Model Machine Learning aktif.
8. Akurasi model terakhir.
9. Jumlah siswa yang sudah mengisi kuesioner.
10. Jumlah siswa yang sudah input nilai akademik.

Alur:

1. Admin masuk dashboard.
2. Sistem menghitung ringkasan data dari tabel terkait.
3. Sistem mengambil model aktif dari ml_models.
4. Sistem mengambil log training terbaru dari training_logs.
5. Sistem menampilkan statistik dalam bentuk card.
6. Sistem menampilkan grafik atau tabel ringkasan rekomendasi jika dibutuhkan.

Rules:

1. Dashboard hanya menampilkan data ringkasan.
2. Jangan menampilkan semua data mentah di dashboard.
3. Gunakan query agregasi seperti count, avg, max.
4. Hindari query N+1.
5. Gunakan eager loading jika menampilkan relasi.
6. Data dashboard boleh dibuat service khusus.

Rekomendasi service:

app/Services/Admin/DashboardService.php

---

## 5. Alur Manajemen Jurusan Kuliah

Database terkait:

- majors
- recommendation_results
- training_datasets

Fitur:

- List jurusan
- Tambah jurusan
- Edit jurusan
- Detail jurusan
- Hapus jurusan

Kolom utama:

- nama_jurusan
- deskripsi

Alur tambah jurusan:

1. Admin membuka halaman jurusan.
2. Admin klik tambah jurusan.
3. Admin mengisi nama jurusan dan deskripsi.
4. Sistem validasi nama_jurusan wajib dan unik.
5. Sistem menyimpan data ke tabel majors.
6. Sistem redirect ke halaman list jurusan dengan pesan sukses.

Alur edit jurusan:

1. Admin memilih jurusan.
2. Sistem menampilkan data jurusan.
3. Admin mengubah nama_jurusan atau deskripsi.
4. Sistem validasi nama_jurusan tetap unik.
5. Sistem update data majors.
6. Sistem redirect dengan pesan sukses.

Alur hapus jurusan:

1. Admin klik hapus.
2. Sistem cek apakah jurusan sudah digunakan di recommendation_results atau training_datasets.
3. Jika sudah digunakan, jangan hard delete.
4. Jika belum digunakan, boleh hapus.
5. Lebih aman gunakan soft delete atau is_active.

Rules:

1. nama_jurusan wajib unik.
2. Jurusan yang sudah dipakai hasil rekomendasi tidak disarankan dihapus permanen.
3. Jika ingin menyembunyikan jurusan, gunakan status is_active.
4. Jurusan dipakai sebagai label pada training_datasets.
5. Jurusan dipakai sebagai hasil pada recommendation_results.

Controller:

app/Http/Controllers/Admin/MajorController.php

Request:

app/Http/Requests/Admin/StoreMajorRequest.php
app/Http/Requests/Admin/UpdateMajorRequest.php

Service:

app/Services/Admin/MajorService.php

View:

resources/views/admin/majors/index.blade.php
resources/views/admin/majors/create.blade.php
resources/views/admin/majors/edit.blade.php
resources/views/admin/majors/show.blade.php

---

## 6. Alur Manajemen Kriteria Minat dan Bakat

Database terkait:

- criteria
- questions
- student_answers
- training_datasets

Fitur:

- List kriteria
- Tambah kriteria
- Edit kriteria
- Detail kriteria
- Hapus kriteria

Kolom utama:

- nama_kriteria
- deskripsi

Alur tambah kriteria:

1. Admin membuka halaman kriteria.
2. Admin klik tambah kriteria.
3. Admin mengisi nama_kriteria dan deskripsi.
4. Sistem validasi nama_kriteria wajib dan unik.
5. Sistem simpan ke tabel criteria.
6. Sistem redirect dengan pesan sukses.

Alur edit kriteria:

1. Admin memilih kriteria.
2. Admin mengubah nama_kriteria atau deskripsi.
3. Sistem validasi data.
4. Sistem update tabel criteria.
5. Sistem redirect dengan pesan sukses.

Alur hapus kriteria:

1. Admin klik hapus kriteria.
2. Sistem cek apakah criteria sudah memiliki questions.
3. Jika sudah memiliki questions, jangan hapus permanen.
4. Jika belum digunakan, boleh hapus.
5. Lebih aman gunakan is_active jika ada.

Rules:

1. Kriteria digunakan untuk mengelompokkan pertanyaan.
2. Satu criteria memiliki banyak questions.
3. Criteria bisa menjadi bagian dari training_datasets.
4. Jangan hapus kriteria jika sudah digunakan oleh pertanyaan atau dataset.
5. Jika ingin menonaktifkan, gunakan field is_active bila tersedia.

Controller:

app/Http/Controllers/Admin/CriteriaController.php

Request:

app/Http/Requests/Admin/StoreCriteriaRequest.php
app/Http/Requests/Admin/UpdateCriteriaRequest.php

Service:

app/Services/Admin/CriteriaService.php

View:

resources/views/admin/criteria/index.blade.php
resources/views/admin/criteria/create.blade.php
resources/views/admin/criteria/edit.blade.php
resources/views/admin/criteria/show.blade.php

---

## 7. Alur Manajemen Pertanyaan Kuesioner

Database terkait:

- criteria
- questions
- question_options
- student_answers

Fitur:

- List pertanyaan
- Tambah pertanyaan
- Edit pertanyaan
- Detail pertanyaan
- Hapus pertanyaan
- Kelola opsi jawaban

Kolom questions:

- criteria_id
- pertanyaan

Kolom question_options:

- question_id
- opsi
- score jika migration sudah ditambahkan

Alur tambah pertanyaan:

1. Admin membuka halaman pertanyaan.
2. Admin klik tambah pertanyaan.
3. Admin memilih criteria.
4. Admin mengisi teks pertanyaan.
5. Admin mengisi opsi jawaban jika opsi dibuat custom.
6. Sistem validasi criteria_id wajib ada di tabel criteria.
7. Sistem validasi pertanyaan wajib diisi.
8. Sistem simpan ke tabel questions.
9. Sistem simpan opsi ke tabel question_options.
10. Sistem redirect dengan pesan sukses.

Alur edit pertanyaan:

1. Admin memilih pertanyaan.
2. Sistem menampilkan criteria, pertanyaan, dan opsi jawaban.
3. Admin mengubah data.
4. Sistem update questions.
5. Sistem update question_options jika ada perubahan opsi.
6. Sistem redirect dengan pesan sukses.

Alur hapus pertanyaan:

1. Admin klik hapus.
2. Sistem cek apakah pertanyaan sudah dijawab siswa di student_answers.
3. Jika sudah dijawab, jangan hapus permanen.
4. Jika belum dijawab, boleh hapus.
5. Lebih aman gunakan is_active jika tersedia.

Rules:

1. Pertanyaan wajib terhubung ke criteria.
2. Pertanyaan aktif akan muncul di halaman kuesioner siswa.
3. Pertanyaan yang sudah memiliki jawaban siswa tidak boleh dihapus permanen.
4. Opsi jawaban standar Likert sebaiknya konsisten.
5. Jika memakai skor 1-5, pastikan score disimpan atau answer siswa menyimpan nilai angka.
6. Jangan ubah makna opsi jawaban jika sudah dipakai siswa.

Contoh opsi Likert:

- Sangat Tidak Setuju = 1
- Tidak Setuju = 2
- Netral = 3
- Setuju = 4
- Sangat Setuju = 5

Controller:

app/Http/Controllers/Admin/QuestionController.php
app/Http/Controllers/Admin/QuestionOptionController.php

Request:

app/Http/Requests/Admin/StoreQuestionRequest.php
app/Http/Requests/Admin/UpdateQuestionRequest.php

Service:

app/Services/Admin/QuestionService.php

View:

resources/views/admin/questions/index.blade.php
resources/views/admin/questions/create.blade.php
resources/views/admin/questions/edit.blade.php
resources/views/admin/questions/show.blade.php

---

## 8. Alur Manajemen Mata Pelajaran

Database terkait:

- subjects
- academic_scores

Fitur:

- List mata pelajaran
- Tambah mata pelajaran
- Edit mata pelajaran
- Set wajib atau opsional
- Hapus atau nonaktifkan mata pelajaran

Kolom utama:

- nama_mapel
- is_required

Alur tambah mata pelajaran:

1. Admin membuka halaman mata pelajaran.
2. Admin klik tambah.
3. Admin mengisi nama_mapel.
4. Admin menentukan apakah mata pelajaran wajib.
5. Sistem validasi nama_mapel wajib dan unik.
6. Sistem simpan ke tabel subjects.
7. Sistem redirect dengan pesan sukses.

Alur edit mata pelajaran:

1. Admin memilih mata pelajaran.
2. Admin mengubah nama_mapel atau is_required.
3. Sistem validasi.
4. Sistem update subjects.
5. Sistem redirect dengan pesan sukses.

Alur hapus mata pelajaran:

1. Admin klik hapus.
2. Sistem cek apakah subject sudah digunakan di academic_scores.
3. Jika sudah digunakan, jangan hapus permanen.
4. Jika belum digunakan, boleh hapus.
5. Lebih aman gunakan is_active jika tersedia.

Rules:

1. Mata pelajaran digunakan siswa saat input nilai akademik.
2. Subject yang wajib harus diisi oleh siswa sebelum rekomendasi diproses.
3. Subject yang sudah digunakan academic_scores tidak boleh dihapus permanen.
4. Gunakan is_required untuk validasi input nilai siswa.

Controller:

app/Http/Controllers/Admin/SubjectController.php

Request:

app/Http/Requests/Admin/StoreSubjectRequest.php
app/Http/Requests/Admin/UpdateSubjectRequest.php

Service:

app/Services/Admin/SubjectService.php

View:

resources/views/admin/subjects/index.blade.php
resources/views/admin/subjects/create.blade.php
resources/views/admin/subjects/edit.blade.php

---

## 9. Alur Manajemen Data Siswa

Database terkait:

- users
- students
- student_answers
- academic_scores
- recommendation_sessions
- recommendation_results

Fitur:

- List siswa
- Detail siswa
- Search siswa
- Filter berdasarkan kelas
- Filter berdasarkan asal sekolah
- Melihat jawaban kuesioner siswa
- Melihat nilai akademik siswa
- Melihat riwayat rekomendasi siswa

Kolom students:

- user_id
- nisn
- nama
- kelas
- asal_sekolah

Alur list siswa:

1. Admin membuka halaman siswa.
2. Sistem mengambil data dari students.
3. Sistem join atau eager load ke users.
4. Admin dapat search berdasarkan nama atau NISN.
5. Admin dapat filter berdasarkan kelas.
6. Admin dapat melihat detail siswa.

Alur detail siswa:

1. Admin klik detail siswa.
2. Sistem menampilkan profil siswa dari students.
3. Sistem menampilkan akun siswa dari users.
4. Sistem menampilkan jawaban dari student_answers.
5. Sistem menampilkan nilai dari academic_scores.
6. Sistem menampilkan riwayat rekomendasi dari recommendation_sessions dan recommendation_results.

Rules:

1. Siswa selalu terhubung ke user melalui user_id.
2. Data login siswa berada di users.
3. Data profil siswa berada di students.
4. Admin tidak boleh melihat password.
5. Search siswa minimal berdasarkan nama dan NISN.
6. Gunakan pagination.
7. Jangan tampilkan seluruh data siswa tanpa batas.
8. Detail siswa harus menggunakan eager loading.

Controller:

app/Http/Controllers/Admin/StudentController.php

Service:

app/Services/Admin/StudentService.php

Repository:

app/Repositories/StudentRepository.php

View:

resources/views/admin/students/index.blade.php
resources/views/admin/students/show.blade.php

---

## 10. Alur Melihat Jawaban Kuesioner Siswa

Database terkait:

- students
- criteria
- questions
- question_options
- student_answers

Fitur:

- Melihat jawaban per siswa
- Melihat jawaban berdasarkan criteria
- Melihat rata-rata minat dan bakat

Alur:

1. Admin membuka detail siswa.
2. Admin memilih tab jawaban kuesioner.
3. Sistem mengambil student_answers milik siswa.
4. Sistem memuat relasi question.
5. Sistem memuat relasi criteria dari question.
6. Sistem menampilkan jawaban berdasarkan kelompok criteria.
7. Sistem menghitung rata-rata jawaban per criteria jika dibutuhkan.

Rules:

1. Jawaban siswa harus dikelompokkan berdasarkan criteria.
2. Jangan tampilkan hanya ID question.
3. Tampilkan teks pertanyaan dan jawaban.
4. Jika answer berupa angka, tampilkan juga label opsi jika tersedia.
5. Perhitungan rata-rata minat harus berbasis criteria.
6. Gunakan eager loading:
   student_answers.question.criteria

Controller:

app/Http/Controllers/Admin/StudentAnswerController.php

Service:

app/Services/Admin/StudentAnswerReportService.php

---

## 11. Alur Melihat Nilai Akademik Siswa

Database terkait:

- students
- subjects
- academic_scores

Fitur:

- Melihat nilai per siswa
- Melihat nilai berdasarkan subject
- Melihat kelengkapan nilai wajib

Alur:

1. Admin membuka detail siswa.
2. Admin memilih tab nilai akademik.
3. Sistem mengambil academic_scores milik siswa.
4. Sistem memuat relasi subject.
5. Sistem menampilkan nama_mapel, nilai, dan status wajib.
6. Sistem mengecek apakah semua subject wajib sudah diisi.

Rules:

1. Nilai akademik harus terhubung ke subject.
2. Jangan tampilkan hanya subject_id.
3. Tampilkan nama_mapel.
4. Tampilkan nilai dengan format 2 desimal jika menggunakan decimal.
5. Subject wajib harus diberi indikator.
6. Jika nilai wajib belum lengkap, tampilkan status belum lengkap.

Controller:

app/Http/Controllers/Admin/AcademicScoreController.php

Service:

app/Services/Admin/AcademicScoreReportService.php

---

## 12. Alur Training Dataset

Database terkait:

- training_datasets
- criteria
- majors

Fitur:

- List dataset training
- Tambah dataset training
- Import dataset training
- Edit dataset training
- Hapus dataset training
- Validasi label jurusan

Kolom utama:

- criteria_id
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips
- jurusan_id

Alur tambah dataset:

1. Admin membuka halaman dataset training.
2. Admin klik tambah dataset.
3. Admin memilih criteria.
4. Admin mengisi nilai akademik.
5. Admin memilih jurusan sebagai label.
6. Sistem validasi criteria_id ada di criteria.
7. Sistem validasi jurusan_id ada di majors.
8. Sistem validasi nilai berada pada rentang yang benar, misalnya 0 sampai 100.
9. Sistem simpan ke training_datasets.
10. Sistem redirect dengan pesan sukses.

Alur import dataset:

1. Admin upload file dataset.
2. Sistem validasi format file.
3. Sistem membaca data.
4. Sistem validasi setiap baris.
5. Sistem cocokkan label jurusan dengan majors.
6. Sistem simpan data valid.
7. Sistem tampilkan ringkasan data berhasil dan gagal.

Rules:

1. Dataset training wajib memiliki label jurusan.
2. Label jurusan berasal dari tabel majors.
3. Nilai akademik harus numerik.
4. Dataset tidak boleh memiliki label jurusan yang tidak ada di database.
5. Dataset training menjadi sumber untuk proses training model.
6. Jangan jalankan training jika dataset kosong.

Controller:

app/Http/Controllers/Admin/TrainingDatasetController.php

Request:

app/Http/Requests/Admin/StoreTrainingDatasetRequest.php

Service:

app/Services/Admin/TrainingDatasetService.php

View:

resources/views/admin/training-datasets/index.blade.php
resources/views/admin/training-datasets/create.blade.php
resources/views/admin/training-datasets/edit.blade.php

---

## 13. Alur Training Ulang Model Machine Learning

Database terkait:

- training_datasets
- ml_models
- training_logs
- majors
- criteria

Service terkait:

- FastAPI Machine Learning Service

Fitur:

- Retrain model
- Simpan versi model
- Aktifkan model terbaru
- Simpan log training
- Simpan metrik evaluasi

Alur retrain:

1. Admin membuka halaman training model.
2. Sistem menampilkan model aktif dari ml_models.
3. Sistem menampilkan log training terakhir dari training_logs.
4. Admin klik tombol retrain.
5. Sistem validasi jumlah training_datasets mencukupi.
6. Laravel membuat status proses training.
7. Laravel mengirim request ke FastAPI untuk menjalankan training.
8. FastAPI melakukan training model.
9. FastAPI menyimpan model dalam format .joblib atau .pkl.
10. FastAPI mengembalikan informasi model dan metrik evaluasi.
11. Laravel menyimpan data model ke ml_models.
12. Laravel menyimpan metrik ke training_logs.
13. Laravel menonaktifkan model lama.
14. Laravel mengaktifkan model baru.
15. Sistem menampilkan pesan sukses.

Rules:

1. Training tidak boleh dijalankan jika dataset kosong.
2. Training sebaiknya dijalankan melalui queue/job.
3. Saat training berjalan, tampilkan status running.
4. Jika training gagal, simpan status failed di training_logs.
5. Hanya satu model boleh aktif.
6. Model aktif digunakan saat rekomendasi siswa.
7. Metrik accuracy, precision, recall, dan f1_score wajib disimpan.
8. Jangan menjalankan proses Python langsung di Controller.
9. Laravel berkomunikasi dengan FastAPI melalui service client.
10. Controller hanya memanggil service training.

Controller:

app/Http/Controllers/Admin/TrainingController.php

Service:

app/Services/MachineLearning/ModelTrainingService.php
app/Services/MachineLearning/FastApiClient.php
app/Services/MachineLearning/ModelVersionService.php

Job:

app/Jobs/TrainMachineLearningModelJob.php

View:

resources/views/admin/trainings/index.blade.php
resources/views/admin/trainings/logs.blade.php

---

## 14. Alur Manajemen Model Machine Learning

Database terkait:

- ml_models
- training_logs

Fitur:

- List model
- Detail model
- Lihat model aktif
- Aktivasi model tertentu
- Lihat metrik training

Kolom ml_models:

- model_name
- version
- trained_at
- is_active

Alur aktivasi model:

1. Admin membuka halaman model.
2. Sistem menampilkan semua model dari ml_models.
3. Admin memilih model untuk diaktifkan.
4. Sistem menonaktifkan semua model lain.
5. Sistem mengaktifkan model yang dipilih.
6. Sistem menyimpan perubahan.
7. Sistem redirect dengan pesan sukses.

Rules:

1. Hanya satu model boleh aktif.
2. Model yang aktif digunakan untuk proses rekomendasi.
3. Model lama tidak boleh dihapus jika masih dibutuhkan untuk audit.
4. Training_logs harus tetap terhubung ke ml_models.
5. Aktivasi model harus menggunakan database transaction.

Controller:

app/Http/Controllers/Admin/MlModelController.php

Service:

app/Services/MachineLearning/ModelVersionService.php

View:

resources/views/admin/ml-models/index.blade.php
resources/views/admin/ml-models/show.blade.php

---

## 15. Alur Melihat Log Training

Database terkait:

- training_logs
- ml_models

Fitur:

- List log training
- Detail log training
- Filter berdasarkan model
- Filter berdasarkan status
- Melihat accuracy, precision, recall, dan f1_score

Alur:

1. Admin membuka halaman log training.
2. Sistem mengambil data training_logs.
3. Sistem eager load relasi ml_models.
4. Admin dapat filter berdasarkan model.
5. Admin dapat melihat detail log.
6. Sistem menampilkan metrik evaluasi.

Rules:

1. Log training tidak boleh dihapus sembarangan.
2. Log training digunakan sebagai audit proses ML.
3. Setiap proses training wajib membuat training_logs.
4. Jika training gagal, tetap buat log dengan status failed jika kolom status tersedia.
5. Gunakan pagination.

Controller:

app/Http/Controllers/Admin/TrainingLogController.php

Service:

app/Services/Admin/TrainingLogService.php

View:

resources/views/admin/training-logs/index.blade.php
resources/views/admin/training-logs/show.blade.php

---

## 16. Alur Melihat Hasil Rekomendasi Siswa

Database terkait:

- students
- users
- recommendation_sessions
- recommendation_results
- majors

Fitur:

- List hasil rekomendasi
- Detail hasil rekomendasi
- Filter berdasarkan siswa
- Filter berdasarkan kelas
- Filter berdasarkan jurusan
- Export hasil rekomendasi

Alur list hasil rekomendasi:

1. Admin membuka halaman hasil rekomendasi.
2. Sistem mengambil recommendation_sessions.
3. Sistem eager load student dan user.
4. Sistem eager load recommendation_results dan major.
5. Sistem menampilkan siswa, tanggal rekomendasi, dan Top-3 jurusan.
6. Admin dapat melihat detail.

Alur detail hasil rekomendasi:

1. Admin klik detail hasil rekomendasi.
2. Sistem menampilkan profil siswa.
3. Sistem menampilkan semua recommendation_results dalam session tersebut.
4. Sistem menampilkan nama jurusan dan score.
5. Sistem menampilkan data pendukung jika dibutuhkan.

Rules:

1. Satu siswa bisa memiliki banyak recommendation_sessions.
2. Satu recommendation_session bisa memiliki banyak recommendation_results.
3. recommendation_results harus terhubung ke majors.
4. Score harus ditampilkan sebagai persentase kecocokan jika berasal dari probabilitas.
5. Gunakan rank jika tersedia untuk Top-3.
6. Jangan tampilkan hasil rekomendasi tanpa nama siswa dan nama jurusan.

Controller:

app/Http/Controllers/Admin/RecommendationReportController.php

Service:

app/Services/Admin/RecommendationReportService.php

View:

resources/views/admin/recommendations/index.blade.php
resources/views/admin/recommendations/show.blade.php

---

## 17. Alur Laporan dan Export Data

Database terkait:

- students
- users
- majors
- criteria
- student_answers
- academic_scores
- recommendation_sessions
- recommendation_results
- training_logs
- ml_models

Jenis laporan:

1. Laporan data siswa.
2. Laporan hasil rekomendasi.
3. Laporan rata-rata minat siswa.
4. Laporan nilai akademik.
5. Laporan training model.
6. Laporan akurasi model.

Alur export:

1. Admin membuka halaman laporan.
2. Admin memilih jenis laporan.
3. Admin memilih filter jika ada.
4. Sistem mengambil data sesuai filter.
5. Sistem membuat file export.
6. Sistem mengunduh file export.

Rules:

1. Laporan harus bisa difilter.
2. Export tidak boleh mengambil semua data besar tanpa filter jika data sudah banyak.
3. Gunakan queue untuk export besar.
4. Jangan menulis logic export di Controller.
5. Gunakan service khusus export.
6. Format export boleh Excel atau PDF sesuai kebutuhan.

Controller:

app/Http/Controllers/Admin/ReportController.php

Service:

app/Services/Report/AdminReportService.php
app/Services/Report/ExportReportService.php

View:

resources/views/admin/reports/index.blade.php

---

## 18. Alur Manajemen Akun Admin

Database terkait:

- users

Fitur:

- List admin
- Tambah admin
- Edit admin
- Reset password admin
- Nonaktifkan admin jika field status tersedia

Alur tambah admin:

1. Superadmin membuka halaman manajemen admin.
2. Superadmin klik tambah admin.
3. Superadmin mengisi nama atau username.
4. Superadmin mengisi password.
5. Sistem validasi username unik.
6. Sistem hash password.
7. Sistem simpan ke users dengan role admin.
8. Sistem redirect dengan pesan sukses.

Alur edit admin:

1. Superadmin memilih akun admin.
2. Superadmin mengubah username atau data lain.
3. Sistem validasi.
4. Sistem update users.
5. Sistem redirect dengan pesan sukses.

Alur reset password:

1. Superadmin memilih akun admin.
2. Superadmin mengisi password baru.
3. Sistem validasi password.
4. Sistem hash password baru.
5. Sistem update users.password.
6. Sistem redirect dengan pesan sukses.

Rules:

1. Hanya superadmin boleh membuat admin.
2. Superadmin pertama dibuat melalui seeder.
3. Admin tidak boleh membuat admin lain.
4. Password wajib di-hash.
5. Username wajib unik.
6. Jangan tampilkan password di halaman admin.
7. Jangan izinkan admin mengubah role dirinya sendiri menjadi superadmin.
8. Jangan izinkan superadmin menghapus dirinya sendiri.

Controller:

app/Http/Controllers/Admin/AdminUserController.php

Request:

app/Http/Requests/Admin/StoreAdminUserRequest.php
app/Http/Requests/Admin/UpdateAdminUserRequest.php
app/Http/Requests/Admin/ResetAdminPasswordRequest.php

Service:

app/Services/Admin/AdminUserService.php

View:

resources/views/admin/admin-users/index.blade.php
resources/views/admin/admin-users/create.blade.php
resources/views/admin/admin-users/edit.blade.php

---

## 19. Urutan Implementasi Fitur Admin

Implementasikan fitur admin dengan urutan berikut agar sesuai dependensi database:

1. Auth admin dan role middleware
2. Seeder superadmin
3. Layout admin Blade
4. Dashboard admin sederhana
5. CRUD majors
6. CRUD criteria
7. CRUD questions
8. CRUD question_options
9. CRUD subjects
10. Manajemen students
11. Detail student answers
12. Detail academic scores
13. Training datasets
14. Training model
15. ML models
16. Training logs
17. Recommendation reports
18. Reports dan export
19. Manajemen admin user

Alasan urutan:

- Auth harus selesai sebelum admin area.
- majors, criteria, questions, dan subjects adalah master data.
- students, answers, dan scores bergantung pada master data.
- training_datasets butuh criteria dan majors.
- training model butuh training_datasets.
- recommendation_reports butuh recommendation_sessions dan recommendation_results.
- reports/export dibuat setelah data utama tersedia.

---

## 20. Mapping Menu Admin ke Database

Menu: Dashboard
Database:
- users
- students
- majors
- recommendation_sessions
- recommendation_results
- ml_models
- training_logs

Menu: Jurusan Kuliah
Database:
- majors

Menu: Kriteria
Database:
- criteria

Menu: Pertanyaan Kuesioner
Database:
- questions
- criteria
- question_options

Menu: Mata Pelajaran
Database:
- subjects

Menu: Data Siswa
Database:
- users
- students
- student_answers
- academic_scores
- recommendation_sessions
- recommendation_results

Menu: Dataset Training
Database:
- training_datasets
- criteria
- majors

Menu: Training Model
Database:
- training_datasets
- ml_models
- training_logs

Menu: Model ML
Database:
- ml_models
- training_logs

Menu: Log Training
Database:
- training_logs
- ml_models

Menu: Hasil Rekomendasi
Database:
- recommendation_sessions
- recommendation_results
- students
- majors

Menu: Laporan
Database:
- students
- student_answers
- academic_scores
- recommendation_sessions
- recommendation_results
- training_logs

Menu: Manajemen Admin
Database:
- users

---

## 21. Pattern Backend yang Wajib Dipakai

Gunakan pattern:

Route
-> Controller
-> Form Request
-> Service
-> Repository jika query kompleks
-> Model
-> View Blade

Rules:

1. Controller tidak boleh gemuk.
2. Controller tidak boleh berisi logic training ML.
3. Controller tidak boleh berisi query laporan panjang.
4. Form Request wajib untuk validasi create/update.
5. Service wajib untuk proses bisnis.
6. Repository digunakan untuk query kompleks.
7. View Blade hanya untuk tampilan, bukan logic bisnis.

---

## 22. Pattern View Admin

Gunakan layout:

resources/views/layouts/admin.blade.php

Komponen reusable admin:

resources/views/components/admin/sidebar.blade.php
resources/views/components/admin/navbar.blade.php
resources/views/components/admin/footer.blade.php
resources/views/components/admin/card.blade.php
resources/views/components/admin/table.blade.php
resources/views/components/admin/badge.blade.php
resources/views/components/admin/alert.blade.php
resources/views/components/admin/modal.blade.php
resources/views/components/admin/form-error.blade.php

Rules:

1. Semua halaman admin wajib memakai layout admin.
2. Sidebar tidak boleh ditulis ulang di setiap halaman.
3. Navbar tidak boleh ditulis ulang di setiap halaman.
4. Table admin yang sama harus dijadikan component.
5. Card statistik dashboard harus dijadikan component.
6. Alert dan error form harus reusable.

---

## 23. Rules Validasi Umum Admin

1. Semua input wajib divalidasi menggunakan Form Request.
2. Data foreign key wajib dicek exists.
3. Data nama master wajib dicek unique jika memang tidak boleh duplikat.
4. Password wajib minimal 8 karakter.
5. Nilai akademik wajib numerik.
6. Nilai akademik sebaiknya berada pada rentang 0 sampai 100.
7. Score rekomendasi wajib numerik.
8. Accuracy, precision, recall, dan f1_score wajib numerik.
9. Jangan percaya input dari frontend.
10. Jangan validasi langsung di Blade.

---

## 24. Rules Search dan Filter Admin

Fitur search wajib ada pada:

1. Data siswa
2. Data jurusan
3. Data kriteria
4. Data pertanyaan
5. Data hasil rekomendasi
6. Log training
7. Dataset training

Search data siswa:

- nama
- nisn
- kelas
- asal_sekolah

Search jurusan:

- nama_jurusan

Search pertanyaan:

- pertanyaan
- nama_kriteria

Search hasil rekomendasi:

- nama siswa
- nisn
- jurusan

Search log training:

- model_name
- version

Rules:

1. Gunakan pagination.
2. Jangan gunakan get() untuk data besar.
3. Gunakan paginate(10), paginate(15), atau paginate(25).
4. Filter harus mempertahankan query string.
5. Pilih kolom yang diperlukan saja.
6. Hindari select * untuk data besar.

---

## 25. Rules Relasi Model

User:
- hasOne Student

Student:
- belongsTo User
- hasMany StudentAnswer
- hasMany AcademicScore
- hasMany RecommendationSession

Major:
- hasMany RecommendationResult
- hasMany TrainingDataset

Criteria:
- hasMany Question
- hasMany TrainingDataset

Question:
- belongsTo Criteria
- hasMany QuestionOption
- hasMany StudentAnswer

QuestionOption:
- belongsTo Question

StudentAnswer:
- belongsTo Student
- belongsTo Question
- belongsTo QuestionOption jika kolom question_option_id tersedia

Subject:
- hasMany AcademicScore

AcademicScore:
- belongsTo Student
- belongsTo Subject

RecommendationSession:
- belongsTo Student
- hasMany RecommendationResult

RecommendationResult:
- belongsTo RecommendationSession
- belongsTo Major

TrainingDataset:
- belongsTo Criteria
- belongsTo Major menggunakan jurusan_id

MlModel:
- hasMany TrainingLog

TrainingLog:
- belongsTo MlModel

---

## 26. Rules Data yang Tidak Boleh Dihapus Permanen

Data berikut sebaiknya tidak dihapus permanen jika sudah dipakai:

1. majors yang sudah digunakan recommendation_results atau training_datasets.
2. criteria yang sudah digunakan questions atau training_datasets.
3. questions yang sudah dijawab student_answers.
4. subjects yang sudah digunakan academic_scores.
5. students yang sudah memiliki recommendation_sessions.
6. ml_models yang sudah memiliki training_logs.
7. training_logs untuk kebutuhan audit.
8. recommendation_results untuk riwayat siswa.

Gunakan salah satu pendekatan:

- soft delete
- is_active
- status

---

## 27. Rules Audit Sederhana

Minimal setiap tabel memiliki:

- created_at
- updated_at

Untuk fitur penting, simpan log melalui training_logs atau activity log jika nanti ditambahkan.

Fitur yang perlu audit:

1. Training model
2. Aktivasi model
3. Export laporan
4. Reset password admin
5. Hapus atau nonaktifkan master data

---

## 28. Rules Error Handling Admin

1. Jika data berhasil disimpan, tampilkan flash success.
2. Jika validasi gagal, tampilkan error di form.
3. Jika data tidak boleh dihapus karena sudah dipakai, tampilkan warning.
4. Jika FastAPI error saat training, simpan training log sebagai failed.
5. Jangan tampilkan stack trace ke admin.
6. Semua proses penting harus menggunakan try-catch di Service, bukan di Controller jika logic kompleks.

---

## 29. Rules Queue

Gunakan queue untuk proses berat:

1. Training model
2. Export laporan besar
3. Generate PDF massal jika ada
4. Import dataset besar

Rules:

1. Tombol retrain hanya membuat job.
2. Job menjalankan proses FastAPI.
3. Status training harus bisa dipantau dari halaman training log.
4. Jika job gagal, simpan status failed.

---

## 30. Checklist Sebelum Fitur Admin Dianggap Selesai

Setiap fitur admin wajib memenuhi checklist:

1. Route sudah memakai middleware auth dan role.
2. Controller berada di folder Admin.
3. Validasi memakai Form Request.
4. Logic utama berada di Service.
5. Query kompleks masuk Repository.
6. View berada di folder resources/views/admin.
7. Halaman memakai layout admin.
8. Ada pagination untuk list data.
9. Ada search/filter jika data berpotensi banyak.
10. Ada flash message success/error.
11. Ada konfirmasi sebelum delete.
12. Foreign key divalidasi.
13. Tidak ada password ditampilkan.
14. Tidak ada query N+1.
15. Data yang sudah dipakai tidak dihapus permanen sembarangan.