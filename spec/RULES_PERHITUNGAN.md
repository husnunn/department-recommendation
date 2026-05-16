# RULES PERHITUNGAN MODEL REKOMENDASI JURUSAN
# Laravel + FastAPI + Scikit-learn
# Model Utama: Random Forest Classifier
# Model Pembanding: SVM dan KNN

## 1. Tujuan Rules

Rules ini digunakan sebagai pedoman alur perhitungan sistem rekomendasi jurusan kuliah.

Sistem menghitung rekomendasi jurusan berdasarkan:

1. Jawaban kuesioner minat dan bakat siswa.
2. Nilai akademik siswa.
3. Model Machine Learning aktif.
4. Output probabilitas kecocokan jurusan.

Model utama yang digunakan:

- Random Forest Classifier

Model pembanding:

- SVM
- KNN

Output akhir:

- Top-3 rekomendasi jurusan
- Score/probabilitas kecocokan
- Riwayat rekomendasi siswa

---

## 2. Prinsip Utama Perhitungan

1. Laravel tidak menghitung model Machine Learning secara langsung.
2. Laravel hanya:
   - Validasi input siswa.
   - Mengambil jawaban kuesioner.
   - Mengambil nilai akademik.
   - Menghitung skor awal minat/bakat.
   - Menyusun payload.
   - Mengirim payload ke FastAPI.
   - Menerima hasil prediksi.
   - Menyimpan hasil rekomendasi.

3. FastAPI bertugas:
   - Melakukan preprocessing data.
   - Memuat model aktif.
   - Melakukan prediksi.
   - Menghasilkan Top-3 jurusan.
   - Menghasilkan probabilitas/skor kecocokan.

4. Model aktif ditentukan dari tabel:
   - ml_models

5. Hasil rekomendasi disimpan ke:
   - recommendation_sessions
   - recommendation_results

6. Log training disimpan ke:
   - training_logs

---

## 3. Data Input yang Digunakan

Data utama:

1. student_answers
   Digunakan untuk menghitung skor minat dan bakat siswa.

2. questions
   Digunakan untuk mengetahui pertanyaan milik kriteria tertentu.

3. criteria
   Digunakan untuk mengelompokkan jawaban siswa berdasarkan kriteria.

4. academic_scores
   Digunakan untuk mengambil nilai akademik siswa.

5. subjects
   Digunakan untuk mengetahui nama mata pelajaran dan status wajib.

6. training_datasets
   Digunakan sebagai data training model Machine Learning.

7. majors
   Digunakan sebagai label jurusan dan output rekomendasi.

---

## 4. Skala Jawaban Kuesioner

Gunakan skala Likert 1 sampai 5.

Mapping jawaban:

1 = Sangat Tidak Setuju
2 = Tidak Setuju
3 = Netral
4 = Setuju
5 = Sangat Setuju

Rules:

1. Setiap jawaban siswa wajib bernilai angka 1 sampai 5.
2. Nilai answer disimpan di student_answers.answer.
3. Jika memakai question_options, score opsi tetap harus konsisten dengan angka 1 sampai 5.
4. Jangan mengubah makna score setelah sudah ada siswa yang mengisi.
5. Semua pertanyaan aktif wajib dijawab sebelum proses rekomendasi dijalankan.

---

## 5. Perhitungan Skor Kriteria

Setiap criteria memiliki beberapa questions.

Contoh criteria:

- Logika
- Sosial
- Bahasa
- Kreativitas
- Analitis

Rumus skor criteria:

criteria_score = total_jawaban_pada_criteria / jumlah_pertanyaan_pada_criteria

Contoh:

Criteria Logika memiliki 4 pertanyaan.

Jawaban siswa:
- Q1 = 5
- Q2 = 4
- Q3 = 4
- Q4 = 5

criteria_score_logika = (5 + 4 + 4 + 5) / 4
criteria_score_logika = 4.5

Rules:

1. Hitung rata-rata jawaban per criteria.
2. Jangan hanya menjumlahkan semua jawaban tanpa pengelompokan criteria.
3. Criteria yang tidak memiliki jawaban lengkap tidak boleh diproses.
4. Skor criteria digunakan sebagai fitur minat/bakat untuk model.
5. Skor criteria harus konsisten antara training dan prediksi.

---

## 6. Normalisasi Skor Kriteria

Agar skala kuesioner lebih mudah digabung dengan nilai akademik, gunakan normalisasi ke skala 0 sampai 100.

Rumus:

criteria_score_100 = ((criteria_score - 1) / 4) * 100

Contoh:

criteria_score = 4.5

criteria_score_100 = ((4.5 - 1) / 4) * 100
criteria_score_100 = 87.5

Mapping:

1.0 = 0
2.0 = 25
3.0 = 50
4.0 = 75
5.0 = 100

Rules:

1. Gunakan hasil normalisasi 0 sampai 100 untuk fitur model.
2. Jangan mencampur skala 1-5 dan 0-100 dalam satu model.
3. Jika saat training memakai skala 0-100, saat prediksi juga wajib memakai skala 0-100.
4. Simpan aturan normalisasi ini secara konsisten di FastAPI dan dokumentasi project.

---

## 7. Perhitungan Nilai Akademik

Nilai akademik berasal dari tabel:

- academic_scores
- subjects

Subject wajib minimal:

- Matematika
- Bahasa Indonesia
- Bahasa Inggris
- IPA
- IPS

Fitur akademik utama:

- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips

Rules:

1. Nilai akademik harus numerik.
2. Nilai akademik berada pada rentang 0 sampai 100.
3. Semua mata pelajaran wajib harus diisi sebelum rekomendasi.
4. Jika nilai tidak lengkap, sistem tidak boleh menjalankan prediksi.
5. Subject opsional boleh digunakan sebagai fitur tambahan jika model memang dilatih dengan fitur tersebut.
6. Jangan mengirim fitur tambahan ke FastAPI jika model belum dilatih dengan fitur tersebut.

---

## 8. Feature Vector yang Dikirim ke FastAPI

Feature vector adalah data akhir yang dikirim Laravel ke FastAPI.

Format fitur minimal:

- score_logika
- score_sosial
- score_bahasa
- score_kreativitas
- score_analitis
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips

Contoh payload:

{
  "student_id": 1,
  "features": {
    "score_logika": 87.5,
    "score_sosial": 75.0,
    "score_bahasa": 80.0,
    "score_kreativitas": 70.0,
    "score_analitis": 85.0,
    "nilai_mtk": 88,
    "nilai_bindo": 82,
    "nilai_bing": 80,
    "nilai_ipa": 86,
    "nilai_ips": 78
  }
}

Rules:

1. Nama fitur harus konsisten antara Laravel dan FastAPI.
2. Urutan fitur harus konsisten saat training dan prediksi.
3. Jangan mengubah nama fitur tanpa mengubah pipeline training.
4. Jika ada fitur baru, model harus retraining.
5. Payload harus divalidasi sebelum dikirim ke FastAPI.

---

## 9. Feature Order Wajib

Urutan fitur yang direkomendasikan:

1. score_logika
2. score_sosial
3. score_bahasa
4. score_kreativitas
5. score_analitis
6. nilai_mtk
7. nilai_bindo
8. nilai_bing
9. nilai_ipa
10. nilai_ips

Rules:

1. Feature order wajib sama di training dan inference.
2. Jangan mengandalkan urutan object JSON secara sembarangan.
3. Di FastAPI, susun ulang fitur ke array sesuai urutan tetap.
4. Simpan daftar feature_order bersama file model.
5. Jika feature_order berubah, buat versi model baru.

---

## 10. Label Output Model

Label model berasal dari tabel:

- majors

Dalam dataset training, label jurusan disimpan pada:

- training_datasets.jurusan_id

Rules:

1. Setiap data training wajib memiliki jurusan_id.
2. jurusan_id harus mengacu ke majors.id.
3. Model tidak boleh memprediksi jurusan yang tidak ada di tabel majors.
4. Saat training, jurusan_id dapat di-encode menjadi label numerik.
5. Saat prediksi, label numerik harus dikembalikan lagi ke major_id.
6. Laravel menyimpan hasil akhir menggunakan major_id.

---

## 11. Rules Random Forest sebagai Model Utama

Random Forest digunakan sebagai model utama.

Rules:

1. Gunakan RandomForestClassifier dari Scikit-learn.
2. Random Forest cocok untuk data tabular.
3. Random Forest dapat menghasilkan probabilitas menggunakan predict_proba.
4. Output predict_proba digunakan untuk menentukan Top-3 jurusan.
5. Model Random Forest yang aktif disimpan di ml_models.
6. Model file disimpan dalam format .joblib atau .pkl.
7. Model aktif harus memiliki is_active = true.
8. Hanya satu model boleh aktif pada satu waktu.

Parameter awal yang disarankan:

model_name = "Random Forest"
algorithm = "random_forest"
n_estimators = 100
random_state = 42
class_weight = "balanced" jika dataset tidak seimbang

Rules parameter:

1. random_state wajib ditentukan agar hasil training lebih mudah diuji ulang.
2. n_estimators boleh dinaikkan jika dataset bertambah besar.
3. class_weight balanced digunakan jika jumlah data per jurusan tidak seimbang.
4. Jangan terlalu banyak mengubah parameter tanpa mencatat versi model.

---

## 12. Rules SVM sebagai Model Pembanding

SVM digunakan sebagai model pembanding, bukan model utama.

Rules:

1. Gunakan SVC dari Scikit-learn.
2. Jika ingin menghasilkan probabilitas, SVM wajib menggunakan probability = true.
3. SVM sensitif terhadap skala fitur.
4. Gunakan StandardScaler sebelum SVM.
5. SVM sebaiknya dibuat dalam Pipeline.
6. Hasil SVM digunakan sebagai pembanding accuracy, precision, recall, dan f1_score.

Parameter awal yang disarankan:

algorithm = "svm"
kernel = "rbf"
probability = true
random_state = 42

Rules penting:

1. Jangan gunakan SVM untuk Top-3 probabilitas jika probability = false.
2. Jika SVM dipilih sebagai model aktif, pastikan output predict_proba tersedia.
3. Gunakan scaler yang sama antara training dan prediksi.
4. Simpan pipeline lengkap, bukan hanya model SVM.

---

## 13. Rules KNN sebagai Model Pembanding

KNN digunakan sebagai model pembanding.

Rules:

1. Gunakan KNeighborsClassifier dari Scikit-learn.
2. KNN sensitif terhadap skala fitur.
3. Gunakan StandardScaler sebelum KNN.
4. KNN dapat menghasilkan probabilitas menggunakan predict_proba.
5. KNN sebaiknya dibuat dalam Pipeline.
6. Hasil KNN digunakan sebagai pembanding metrik evaluasi.

Parameter awal yang disarankan:

algorithm = "knn"
n_neighbors = 5
weights = "distance"

Rules penting:

1. Jangan memakai KNN tanpa scaling.
2. n_neighbors harus disesuaikan dengan jumlah dataset.
3. Jika dataset masih sedikit, n_neighbors jangan terlalu besar.
4. Simpan pipeline lengkap, bukan hanya model KNN.

---

## 14. Rules Preprocessing

Preprocessing harus dilakukan sama saat training dan prediksi.

Langkah preprocessing:

1. Ambil data training dari training_datasets.
2. Bentuk feature matrix X.
3. Bentuk label y dari jurusan_id.
4. Normalisasi atau scaling sesuai model.
5. Split data training dan testing.
6. Train model.
7. Evaluasi model.
8. Simpan model.

Rules:

1. Jangan melakukan preprocessing berbeda antara training dan prediksi.
2. Jika memakai StandardScaler, simpan scaler bersama model dalam Pipeline.
3. Jika memakai LabelEncoder, simpan mapping label.
4. Jika ada missing value, tentukan strategi tetap.
5. Jangan membuang kolom saat prediksi jika kolom dipakai saat training.
6. Jangan menambahkan kolom saat prediksi jika kolom tidak dipakai saat training.

---

## 15. Rules Missing Value

Jika data siswa tidak lengkap:

1. Jika jawaban kuesioner belum lengkap:
   - Jangan jalankan rekomendasi.
   - Tampilkan pesan: "Kuesioner belum lengkap."

2. Jika nilai akademik wajib belum lengkap:
   - Jangan jalankan rekomendasi.
   - Tampilkan pesan: "Nilai akademik wajib belum lengkap."

3. Jika subject opsional kosong:
   - Boleh diabaikan jika tidak termasuk fitur model.

4. Jika dataset training memiliki missing value:
   - Data tidak valid jangan dipakai training.
   - Admin harus memperbaiki dataset.

Rules:

1. Jangan isi nilai kosong dengan 0 tanpa alasan.
2. Jangan memprediksi jika fitur utama kosong.
3. Validasi kelengkapan dilakukan di Laravel sebelum request ke FastAPI.
4. Validasi struktur fitur dilakukan lagi di FastAPI.

---

## 16. Rules Training Model

Alur training:

1. Admin klik Training Ulang Model.
2. Laravel mengambil data dari training_datasets.
3. Laravel mengirim dataset atau trigger training ke FastAPI.
4. FastAPI membentuk X dan y.
5. FastAPI melakukan train-test split.
6. FastAPI melatih model.
7. FastAPI menghitung metrik evaluasi.
8. FastAPI menyimpan file model.
9. FastAPI mengembalikan metadata model ke Laravel.
10. Laravel menyimpan data ke ml_models.
11. Laravel menyimpan metrik ke training_logs.
12. Laravel mengaktifkan model terbaru.

Rules:

1. Training tidak boleh berjalan jika dataset kosong.
2. Training tidak boleh berjalan jika jumlah label jurusan kurang dari 2.
3. Training tidak boleh berjalan jika data per jurusan terlalu sedikit.
4. Training harus menghasilkan accuracy, precision, recall, dan f1_score.
5. Setiap training harus membuat versi model baru.
6. Model lama tidak langsung dihapus.
7. Hanya satu model boleh aktif.
8. Proses training sebaiknya dijalankan melalui queue/job.

---

## 17. Rules Evaluasi Model

Metrik yang wajib dihitung:

1. accuracy
2. precision
3. recall
4. f1_score

Gunakan average:

average = "weighted"

Alasan:

- Dataset jurusan kemungkinan tidak seimbang.
- Weighted average lebih aman untuk multi-class classification.

Rules:

1. Semua metrik disimpan ke training_logs.
2. Metrik harus ditampilkan di dashboard admin.
3. Metrik harus ditampilkan di halaman log training.
4. Jika model baru memiliki hasil buruk, admin boleh tetap memakai model lama.
5. Jangan hanya mengandalkan accuracy jika dataset tidak seimbang.
6. Precision, recall, dan f1_score tetap harus disimpan.

---

## 18. Rules Pemilihan Model Aktif

Model aktif berasal dari tabel:

- ml_models

Kolom utama:

- model_name
- version
- trained_at
- is_active

Rules:

1. Hanya satu model memiliki is_active = true.
2. Model aktif digunakan untuk prediksi siswa.
3. Jika tidak ada model aktif, rekomendasi tidak boleh dijalankan.
4. Admin harus melihat warning: "Belum ada model aktif."
5. Saat model baru diaktifkan, model lama harus diubah menjadi is_active = false.
6. Aktivasi model harus menggunakan database transaction.
7. Model aktif harus memiliki file model yang tersedia di storage/FastAPI.

---

## 19. Rules Prediksi Rekomendasi

Alur prediksi:

1. Siswa menyelesaikan kuesioner.
2. Siswa mengisi nilai akademik wajib.
3. Laravel validasi kelengkapan data.
4. Laravel menghitung skor criteria.
5. Laravel menyusun payload fitur.
6. Laravel membuat recommendation_session dengan status processing.
7. Laravel mengirim payload ke FastAPI.
8. FastAPI memuat model aktif.
9. FastAPI menjalankan predict_proba.
10. FastAPI mengambil Top-3 probabilitas tertinggi.
11. FastAPI mengembalikan major_id dan score.
12. Laravel menyimpan hasil ke recommendation_results.
13. Laravel mengubah session menjadi completed.
14. User melihat hasil rekomendasi.

Rules:

1. Satu proses prediksi menghasilkan satu recommendation_session.
2. Satu session minimal menyimpan 3 recommendation_results jika Top-3 tersedia.
3. Jika jumlah jurusan kurang dari 3, tampilkan sesuai jumlah jurusan yang tersedia.
4. Score disimpan sebagai persentase 0 sampai 100.
5. Ranking disimpan jika kolom rank tersedia.
6. Jika FastAPI gagal, session menjadi failed.
7. Jangan menampilkan hasil prediksi jika session gagal.

---

## 20. Rumus Konversi Probabilitas ke Score

Output model:

predict_proba = 0.9234

Konversi ke persentase:

score = predict_proba * 100

Contoh:

0.9234 * 100 = 92.34

Rules:

1. Simpan score ke recommendation_results.score.
2. Format tampilan score maksimal 2 angka desimal.
3. Score 92.34 berarti kecocokan 92.34%.
4. Jangan mengubah probabilitas menjadi kategori tanpa menyimpan score asli.
5. Urutkan rekomendasi berdasarkan score terbesar.

---

## 21. Rules Top-3 Jurusan

Top-3 diambil dari probabilitas tertinggi.

Contoh output FastAPI:

[
  {
    "rank": 1,
    "major_id": 1,
    "major_name": "Teknik Informatika",
    "score": 92.34
  },
  {
    "rank": 2,
    "major_id": 2,
    "major_name": "Sistem Informasi",
    "score": 87.11
  },
  {
    "rank": 3,
    "major_id": 3,
    "major_name": "Data Science",
    "score": 81.45
  }
]

Rules:

1. Rank 1 adalah score tertinggi.
2. Rank 2 adalah score tertinggi kedua.
3. Rank 3 adalah score tertinggi ketiga.
4. Jangan menampilkan jurusan dengan score kosong.
5. Jangan menampilkan jurusan yang tidak aktif jika sistem memakai is_active.
6. Simpan semua Top-3 ke recommendation_results.
7. Tampilkan Top-3 di halaman hasil siswa.
8. Admin bisa melihat Top-3 di halaman detail rekomendasi.

---

## 22. Rules Penyimpanan Recommendation Session

Tabel:

- recommendation_sessions

Data yang disimpan:

- student_id
- status jika tersedia
- started_at jika tersedia
- finished_at jika tersedia
- created_at
- updated_at

Rules:

1. Buat session sebelum request ke FastAPI.
2. Status awal adalah processing.
3. Jika prediksi berhasil, status menjadi completed.
4. Jika prediksi gagal, status menjadi failed.
5. Satu siswa boleh memiliki banyak session.
6. Riwayat rekomendasi siswa diambil dari session yang completed.

---

## 23. Rules Penyimpanan Recommendation Results

Tabel:

- recommendation_results

Data yang disimpan:

- session_id
- major_id
- score
- rank jika tersedia

Rules:

1. Setiap hasil harus terhubung ke recommendation_sessions.
2. Setiap hasil harus terhubung ke majors.
3. Score wajib numerik.
4. Score berada pada rentang 0 sampai 100.
5. Dalam satu session, major_id tidak boleh duplikat.
6. Dalam satu session, rank tidak boleh duplikat.
7. Data hasil rekomendasi tidak boleh dihapus sembarangan karena menjadi riwayat siswa.

---

## 24. Rules Mapping Dataset Training

Dataset training minimal harus memiliki:

- score_logika
- score_sosial
- score_bahasa
- score_kreativitas
- score_analitis
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips
- jurusan_id

Namun pada desain database awal, training_datasets hanya memiliki:

- criteria_id
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips
- jurusan_id

Rules penting:

1. Jika tetap memakai desain training_datasets awal, model hanya menerima satu criteria_id sebagai fitur minat/bakat.
2. Ini kurang ideal karena skor semua criteria siswa tidak tersimpan lengkap di dataset training.
3. Untuk model yang lebih baik, tambahkan kolom skor criteria ke training_datasets.
4. Kolom yang disarankan:
   - score_logika
   - score_sosial
   - score_bahasa
   - score_kreativitas
   - score_analitis

Rekomendasi perubahan training_datasets:

training_datasets:
- id
- score_logika
- score_sosial
- score_bahasa
- score_kreativitas
- score_analitis
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips
- jurusan_id
- created_at
- updated_at

Rules:

1. Model prediksi siswa harus menggunakan fitur yang sama dengan dataset training.
2. Jika siswa memiliki 5 skor criteria, dataset training juga harus punya 5 skor criteria.
3. Jangan training model dengan criteria_id saja jika saat prediksi ingin memakai semua skor minat/bakat.
4. criteria_id boleh tetap dipakai untuk analisis, tetapi bukan fitur utama model.
5. Untuk skripsi, jelaskan bahwa fitur minat/bakat direpresentasikan sebagai skor rata-rata tiap criteria.

---

## 25. Rules Jika Tetap Memakai criteria_id

Jika database belum ingin diubah dan tetap memakai criteria_id:

Fitur model menjadi:

- criteria_id
- nilai_mtk
- nilai_bindo
- nilai_bing
- nilai_ipa
- nilai_ips

Rules:

1. Laravel harus menentukan criteria dominan siswa.
2. Criteria dominan adalah criteria dengan skor tertinggi.
3. Kirim criteria_id dominan ke FastAPI.
4. Model memprediksi jurusan berdasarkan criteria dominan dan nilai akademik.
5. Cara ini lebih sederhana, tetapi kurang menggambarkan keseluruhan minat/bakat siswa.

Rumus criteria dominan:

criteria_dominan = criteria dengan criteria_score_100 tertinggi

Contoh:

Logika = 87.5
Sosial = 75
Bahasa = 80
Kreativitas = 70
Analitis = 85

criteria_dominan = Logika

Payload:

{
  "criteria_id": 1,
  "nilai_mtk": 88,
  "nilai_bindo": 82,
  "nilai_bing": 80,
  "nilai_ipa": 86,
  "nilai_ips": 78
}

Catatan:

Pendekatan ini boleh digunakan untuk versi awal, tetapi untuk hasil rekomendasi yang lebih baik, gunakan skor semua criteria sebagai fitur.

---

## 26. Rules Model Versioning

Setiap model hasil training harus punya versi.

Format versi:

vYYYYMMDDHHMM

Contoh:

v202605141030

Data ml_models:

- model_name = Random Forest
- algorithm = random_forest
- version = v202605141030
- trained_at = waktu training selesai
- is_active = true

Rules:

1. Versi model tidak boleh duplikat.
2. Setiap training menghasilkan versi baru.
3. Model lama tetap disimpan.
4. Hanya model aktif yang dipakai prediksi.
5. Jika model baru gagal, model aktif lama tetap digunakan.

---

## 27. Rules FastAPI Response

FastAPI wajib mengembalikan response yang jelas.

Response sukses:

{
  "success": true,
  "model": {
    "model_name": "Random Forest",
    "version": "v202605141030"
  },
  "results": [
    {
      "rank": 1,
      "major_id": 1,
      "score": 92.34
    },
    {
      "rank": 2,
      "major_id": 2,
      "score": 87.11
    },
    {
      "rank": 3,
      "major_id": 3,
      "score": 81.45
    }
  ]
}

Response gagal:

{
  "success": false,
  "message": "Model aktif tidak ditemukan."
}

Rules:

1. Laravel hanya menyimpan hasil jika success = true.
2. Jika success = false, recommendation_session menjadi failed.
3. major_id harus valid di database Laravel.
4. score harus numerik.
5. Laravel tetap harus validasi response dari FastAPI.

---

## 28. Rules Tampilan Hasil ke User

Halaman hasil siswa menampilkan:

1. Nama siswa.
2. Tanggal rekomendasi.
3. Top-3 jurusan.
4. Score kecocokan dalam persen.
5. Deskripsi jurusan.
6. Tombol download PDF.

Rules:

1. Jangan tampilkan istilah teknis seperti predict_proba ke siswa.
2. Gunakan label "Persentase Kecocokan".
3. Tampilkan jurusan dengan score tertinggi sebagai rekomendasi utama.
4. Tampilkan Top-3 agar siswa punya alternatif.
5. Beri catatan bahwa hasil adalah rekomendasi, bukan keputusan mutlak.

---

## 29. Rules Tampilan Hasil ke Admin

Admin dapat melihat:

1. Nama siswa.
2. NISN.
3. Kelas.
4. Tanggal rekomendasi.
5. Top-3 jurusan.
6. Score.
7. Model yang digunakan jika tersedia.
8. Riwayat rekomendasi.

Rules:

1. Admin boleh filter berdasarkan kelas.
2. Admin boleh filter berdasarkan jurusan.
3. Admin boleh export hasil.
4. Admin dapat melihat statistik jurusan paling sering direkomendasikan.
5. Admin dapat melihat rata-rata score per jurusan.

---

## 30. Rules Keamanan dan Validasi

1. Siswa hanya boleh membuat rekomendasi untuk dirinya sendiri.
2. Siswa hanya boleh melihat riwayat rekomendasi miliknya sendiri.
3. Admin boleh melihat semua hasil rekomendasi.
4. FastAPI tidak boleh menerima request publik tanpa pengamanan.
5. Gunakan token internal antara Laravel dan FastAPI jika memungkinkan.
6. Laravel harus validasi data sebelum mengirim ke FastAPI.
7. FastAPI harus validasi ulang payload.
8. Jangan percaya data dari frontend.

---

## 31. Rules Implementasi Laravel

Service yang disarankan:

app/Services/Recommendation/RecommendationService.php
app/Services/Recommendation/RecommendationPayloadBuilder.php
app/Services/Recommendation/RecommendationResultService.php
app/Services/MachineLearning/FastApiClient.php

Tanggung jawab:

RecommendationPayloadBuilder:
- Mengambil jawaban siswa.
- Menghitung skor criteria.
- Mengambil nilai akademik.
- Menyusun payload FastAPI.

RecommendationService:
- Validasi kelengkapan data.
- Membuat recommendation_session.
- Memanggil FastApiClient.
- Mengatur status session.

RecommendationResultService:
- Menyimpan Top-3 hasil rekomendasi.

FastApiClient:
- Mengirim payload ke FastAPI.
- Menerima response.
- Menghandle timeout/error.

Rules:

1. Controller tidak menghitung skor langsung.
2. Controller hanya memanggil RecommendationService.
3. Semua proses simpan hasil harus memakai DB transaction.
4. Jika terjadi error, session harus ditandai failed.
5. Jangan panggil Python langsung dari file PHP.

---

## 32. Rules Implementasi FastAPI

Module yang disarankan:

ml-service/
- main.py
- schemas.py
- services/
  - prediction_service.py
  - training_service.py
  - preprocessing_service.py
- models/
  - model_loader.py
- storage/
  - models/

Rules:

1. Prediction logic tidak ditulis langsung di endpoint.
2. Training logic tidak ditulis langsung di endpoint.
3. Gunakan service terpisah.
4. Simpan model dengan joblib.
5. Simpan feature_order bersama model.
6. Gunakan Pipeline untuk model yang membutuhkan scaler.
7. Response harus stabil agar mudah dipakai Laravel.

---

## 33. Rules Kesimpulan Model yang Dipakai

Untuk versi awal project:

Gunakan Random Forest sebagai model utama.

Alasan:

1. Cocok untuk data tabular.
2. Bisa menghasilkan probabilitas.
3. Lebih mudah dijelaskan untuk skripsi.
4. Tidak terlalu sensitif terhadap scaling dibanding SVM dan KNN.
5. Cocok sebagai baseline utama.

Gunakan SVM dan KNN sebagai pembanding.

Rules akhir:

1. Model aktif default adalah Random Forest.
2. SVM dan KNN digunakan untuk membandingkan metrik.
3. Model dengan metrik terbaik boleh diaktifkan oleh admin jika dibutuhkan.
4. Semua model harus menghasilkan Top-3 probabilitas.
5. Hasil rekomendasi tetap disimpan dengan format yang sama, apapun modelnya.