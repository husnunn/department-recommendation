# RULES PEMBUATAN FASTAPI ASLI
# Sistem Rekomendasi Jurusan Kuliah
# Laravel + FastAPI + Scikit-learn

## 1. Tujuan Rules

Rules ini digunakan untuk membuat layanan FastAPI asli yang bertugas sebagai Machine Learning Service.

FastAPI bertugas untuk:

- Menerima request training dari Laravel.
- Melatih model Machine Learning menggunakan dataset training.
- Menyimpan model dalam format .joblib.
- Menerima request prediksi dari Laravel.
- Menghasilkan Top-3 rekomendasi jurusan.
- Mengembalikan score/probabilitas kecocokan.
- Mengembalikan metrik evaluasi model.

FastAPI tidak bertugas untuk:

- Login siswa.
- Login admin.
- Mengakses session Laravel.
- Mengelola halaman admin.
- Mengelola halaman user.
- Menyimpan hasil rekomendasi ke MySQL Laravel secara langsung.
- Menentukan role user.
- Mengakses data siswa langsung tanpa request dari Laravel.

Laravel tetap menjadi pusat:

- Auth.
- Role.
- Database utama.
- Validasi bisnis.
- Penyimpanan recommendation_sessions.
- Penyimpanan recommendation_results.
- Penyimpanan ml_models.
- Penyimpanan training_logs.

---

## 2. Prinsip Arsitektur

Alur training:

Admin Laravel
-> POST /admin/training-model
-> Laravel validasi dataset
-> Laravel kirim dataset ke FastAPI /train
-> FastAPI training model
-> FastAPI simpan file .joblib
-> FastAPI balas metadata model dan metrik
-> Laravel simpan ml_models dan training_logs
-> Laravel aktifkan model jika training sukses

Alur prediksi:

Siswa klik Proses Rekomendasi
-> Laravel ambil jawaban dan nilai dari database
-> Laravel hitung fitur siswa
-> Laravel cek model aktif
-> Laravel kirim payload ke FastAPI /predict
-> FastAPI load model aktif
-> FastAPI prediksi Top-3
-> FastAPI balas major_id dan score
-> Laravel simpan recommendation_results
-> Laravel tampilkan hasil ke user

Rules utama:

1. Siswa tidak boleh memanggil FastAPI langsung.
2. Admin tidak membuka FastAPI langsung dari browser untuk operasional.
3. Laravel adalah satu-satunya client resmi FastAPI.
4. FastAPI harus diberi token internal jika sudah masuk production.
5. FastAPI tidak boleh menyimpan hasil rekomendasi langsung ke database Laravel.
6. FastAPI hanya mengembalikan response JSON.
7. Laravel yang menyimpan hasil ke database.

---

## 3. Struktur Folder FastAPI

Letakkan FastAPI di root project Laravel dalam folder terpisah:

rekomendasi-jurusan/
├── app/
├── database/
├── resources/
├── routes/
├── public/
├── composer.json
│
└── ml-service/
    ├── main.py
    ├── requirements.txt
    ├── .env.example
    ├── README.md
    │
    ├── app/
    │   ├── __init__.py
    │   ├── core/
    │   │   ├── __init__.py
    │   │   ├── config.py
    │   │   └── security.py
    │   │
    │   ├── schemas/
    │   │   ├── __init__.py
    │   │   ├── prediction_schema.py
    │   │   └── training_schema.py
    │   │
    │   ├── services/
    │   │   ├── __init__.py
    │   │   ├── preprocessing_service.py
    │   │   ├── training_service.py
    │   │   └── prediction_service.py
    │   │
    │   ├── models/
    │   │   ├── __init__.py
    │   │   └── model_loader.py
    │   │
    │   └── api/
    │       ├── __init__.py
    │       └── routes.py
    │
    └── storage/
        └── models/
            └── .gitkeep

Rules struktur:

1. main.py hanya untuk membuat instance FastAPI dan include router.
2. Endpoint API diletakkan di app/api/routes.py.
3. Schema request/response diletakkan di app/schemas.
4. Logic training diletakkan di app/services/training_service.py.
5. Logic prediksi diletakkan di app/services/prediction_service.py.
6. Logic preprocessing diletakkan di app/services/preprocessing_service.py.
7. Logic load/save model diletakkan di app/models/model_loader.py.
8. Config diletakkan di app/core/config.py.
9. Security/token diletakkan di app/core/security.py.
10. File model disimpan di ml-service/storage/models.

---

## 4. Dependency Wajib

File requirements.txt wajib berisi:

fastapi
uvicorn
pydantic
pydantic-settings
scikit-learn
pandas
numpy
joblib
python-dotenv

Opsional:

pytest
httpx

Rules:

1. Gunakan scikit-learn untuk Random Forest, SVM, dan KNN.
2. Gunakan joblib untuk menyimpan model.
3. Gunakan pandas/numpy untuk pengolahan data tabular.
4. Gunakan pydantic untuk validasi request.
5. Jangan install dependency yang tidak dibutuhkan.

---

## 5. Environment FastAPI

Buat file:

ml-service/.env

Isi contoh:

ML_SERVICE_NAME="Recommendation Major ML Service"
ML_SERVICE_TOKEN="secret-token-local"
MODEL_STORAGE_PATH="storage/models"
DEFAULT_ALGORITHM="random_forest"

Rules:

1. Token FastAPI harus sama dengan token yang dikirim Laravel.
2. Di lokal token boleh sederhana.
3. Di production token harus kuat.
4. Jangan commit file .env.
5. Commit hanya .env.example.

File .env.example:

ML_SERVICE_NAME="Recommendation Major ML Service"
ML_SERVICE_TOKEN="change-me"
MODEL_STORAGE_PATH="storage/models"
DEFAULT_ALGORITHM="random_forest"

---

## 6. Konfigurasi Laravel untuk FastAPI

Laravel .env:

ML_SERVICE_STUB=false
ML_SERVICE_URL=http://127.0.0.1:8001
ML_SERVICE_TOKEN=secret-token-local
ML_SERVICE_PREDICT_PATH=/predict
ML_SERVICE_TRAIN_PATH=/train
ML_SERVICE_TIMEOUT=60

Rules:

1. Jika memakai FastAPI asli, ML_SERVICE_STUB harus false.
2. ML_SERVICE_URL wajib diisi.
3. ML_SERVICE_TOKEN harus sama dengan token di FastAPI.
4. Laravel mengirim token lewat header Authorization.
5. Jika FastAPI belum siap, pakai ML_SERVICE_STUB=true dulu.

---

## 7. Endpoint Wajib FastAPI

FastAPI minimal memiliki endpoint:

1. GET /
2. GET /health
3. POST /train
4. POST /predict

Rules endpoint:

1. GET / hanya untuk cek service hidup.
2. GET /health untuk health check.
3. POST /train untuk training model.
4. POST /predict untuk prediksi Top-3 jurusan.
5. POST /train dan POST /predict wajib memakai token.
6. Response harus JSON stabil.
7. Jangan mengubah struktur response tanpa mengubah Laravel client.

---

## 8. Kontrak Header Security

Laravel wajib mengirim:

Authorization: Bearer {ML_SERVICE_TOKEN}
Content-Type: application/json

FastAPI wajib validasi:

1. Header Authorization ada.
2. Format Bearer token benar.
3. Token sama dengan ML_SERVICE_TOKEN.
4. Jika token salah, return HTTP 401.

Rules:

1. Jangan biarkan endpoint /train dan /predict terbuka publik.
2. Health check boleh tanpa token.
3. Endpoint root boleh tanpa token.
4. Token bukan untuk user login, hanya server-to-server.

---

## 9. Feature Order Wajib

FastAPI harus memakai feature_order tetap:

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

1. Feature order saat training dan prediksi harus sama.
2. Laravel harus mengirim fitur dengan nama yang sama.
3. FastAPI harus menyusun array fitur berdasarkan feature_order.
4. Jangan mengandalkan urutan JSON dari request.
5. Jika feature_order berubah, versi model baru wajib dibuat.
6. Simpan feature_order bersama artefak model.

---

## 10. Kontrak Request /train

Laravel mengirim dataset ke FastAPI.

Format request:

{
  "algorithm": "random_forest",
  "version": "v202605161200",
  "datasets": [
    {
      "score_logika": 85,
      "score_sosial": 70,
      "score_bahasa": 75,
      "score_kreativitas": 60,
      "score_analitis": 88,
      "nilai_mtk": 90,
      "nilai_bindo": 80,
      "nilai_bing": 82,
      "nilai_ipa": 87,
      "nilai_ips": 75,
      "jurusan_id": 1
    }
  ]
}

Field wajib:

- algorithm
- version
- datasets
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

Rules validasi:

1. datasets tidak boleh kosong.
2. Minimal ada 2 jurusan berbeda.
3. Semua fitur numerik.
4. Semua fitur berada dalam rentang 0 sampai 100.
5. jurusan_id wajib integer.
6. algorithm hanya boleh random_forest, svm, atau knn.
7. version wajib string dan unik di Laravel.
8. FastAPI tidak perlu cek version ke database Laravel.
9. Laravel yang memastikan version unik.
10. FastAPI hanya memakai version untuk nama file model.

---

## 11. Kontrak Response /train Sukses

Response sukses:

{
  "success": true,
  "message": "Training model berhasil.",
  "model": {
    "model_name": "Random Forest",
    "algorithm": "random_forest",
    "version": "v202605161200",
    "model_path": "storage/models/random_forest_v202605161200.joblib",
    "trained_at": "2026-05-16T12:00:00"
  },
  "metrics": {
    "accuracy": 0.85,
    "precision": 0.84,
    "recall": 0.85,
    "f1_score": 0.84
  },
  "feature_order": [
    "score_logika",
    "score_sosial",
    "score_bahasa",
    "score_kreativitas",
    "score_analitis",
    "nilai_mtk",
    "nilai_bindo",
    "nilai_bing",
    "nilai_ipa",
    "nilai_ips"
  ]
}

Rules:

1. success harus true jika training berhasil.
2. model_path harus path relatif dari folder ml-service.
3. metrics wajib ada.
4. Nilai metrics berada 0 sampai 1.
5. Laravel menyimpan metrics ke training_logs.
6. Laravel menyimpan model ke ml_models.
7. Laravel mengaktifkan model setelah response sukses.

---

## 12. Kontrak Response /train Gagal

Response gagal:

{
  "success": false,
  "message": "Dataset tidak valid: minimal harus ada 2 jurusan berbeda."
}

Rules:

1. success harus false jika training gagal.
2. message harus jelas.
3. Jangan return stack trace ke Laravel.
4. HTTP status boleh 422 untuk validasi.
5. HTTP status boleh 500 untuk error internal.
6. Laravel menyimpan training_logs failed.
7. Laravel tidak boleh mengaktifkan model gagal.

---

## 13. Kontrak Request /predict

Laravel mengirim fitur siswa dan model aktif.

Format request:

{
  "model": {
    "algorithm": "random_forest",
    "version": "v202605161200",
    "model_path": "storage/models/random_forest_v202605161200.joblib"
  },
  "features": {
    "score_logika": 87.5,
    "score_sosial": 75,
    "score_bahasa": 80,
    "score_kreativitas": 70,
    "score_analitis": 85,
    "nilai_mtk": 88,
    "nilai_bindo": 82,
    "nilai_bing": 80,
    "nilai_ipa": 86,
    "nilai_ips": 78
  }
}

Field wajib:

- model.algorithm
- model.version
- model.model_path
- features.score_logika
- features.score_sosial
- features.score_bahasa
- features.score_kreativitas
- features.score_analitis
- features.nilai_mtk
- features.nilai_bindo
- features.nilai_bing
- features.nilai_ipa
- features.nilai_ips

Rules:

1. FastAPI load model berdasarkan model_path dari Laravel.
2. FastAPI tidak memilih model aktif sendiri dari database.
3. Laravel yang menentukan model aktif.
4. Semua fitur harus numerik.
5. Semua fitur harus 0 sampai 100.
6. Jika model_path tidak ditemukan, return error.
7. Jika model tidak punya predict_proba, return error.
8. FastAPI wajib mengembalikan Top-3 jika jumlah class memungkinkan.

---

## 14. Kontrak Response /predict Sukses

Response sukses:

{
  "success": true,
  "model": {
    "algorithm": "random_forest",
    "version": "v202605161200"
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

Rules:

1. score dikembalikan dalam skala 0 sampai 100.
2. rank dimulai dari 1.
3. results diurutkan dari score terbesar.
4. major_id harus berasal dari label training.
5. FastAPI tidak perlu mengembalikan nama jurusan.
6. Laravel mengambil nama jurusan dari tabel majors.
7. Jika model hanya punya 2 class, kembalikan 2 hasil.
8. Jika model punya 1 class, model tidak valid untuk rekomendasi.

---

## 15. Kontrak Response /predict Gagal

Response gagal:

{
  "success": false,
  "message": "Model file tidak ditemukan."
}

Rules:

1. success harus false.
2. message harus jelas.
3. Jangan return stack trace.
4. Laravel akan mengubah recommendation_sessions menjadi failed.
5. Laravel tidak menyimpan recommendation_results jika prediksi gagal.

---

## 16. Rules Model Random Forest

Random Forest adalah model utama.

Rules:

1. Gunakan RandomForestClassifier.
2. Gunakan n_estimators = 100 sebagai default.
3. Gunakan random_state = 42.
4. Gunakan class_weight = balanced jika dataset tidak seimbang.
5. Random Forest boleh tidak memakai StandardScaler.
6. Harus mendukung predict_proba.
7. Output predict_proba dipakai untuk Top-3.

Nama model:

model_name = "Random Forest"
algorithm = "random_forest"

---

## 17. Rules Model SVM

SVM adalah model pembanding.

Rules:

1. Gunakan SVC.
2. Gunakan probability = true.
3. Gunakan StandardScaler.
4. Simpan dalam Pipeline.
5. Gunakan kernel = rbf sebagai default.
6. Harus mendukung predict_proba.
7. Jika probability=false, model tidak boleh dipakai untuk Top-3.

Nama model:

model_name = "SVM"
algorithm = "svm"

---

## 18. Rules Model KNN

KNN adalah model pembanding.

Rules:

1. Gunakan KNeighborsClassifier.
2. Gunakan StandardScaler.
3. Simpan dalam Pipeline.
4. Gunakan n_neighbors = 5 default.
5. Jika dataset kecil, n_neighbors harus disesuaikan agar tidak melebihi jumlah data training.
6. Harus mendukung predict_proba.

Nama model:

model_name = "KNN"
algorithm = "knn"

---

## 19. Rules Training Service

Training service wajib melakukan:

1. Validasi jumlah dataset.
2. Validasi minimal 2 jurusan berbeda.
3. Validasi semua fitur lengkap.
4. Validasi semua fitur 0 sampai 100.
5. Membentuk X dari feature_order.
6. Membentuk y dari jurusan_id.
7. Train-test split.
8. Melatih model sesuai algorithm.
9. Menghitung metrics.
10. Menyimpan model dengan joblib.
11. Mengembalikan response standar.

Rules train-test split:

1. Gunakan test_size = 0.2 jika dataset cukup.
2. Gunakan random_state = 42.
3. Gunakan stratify = y jika setiap class punya minimal 2 data.
4. Jika data terlalu kecil untuk stratify, training boleh tetap berjalan dengan fallback sederhana.
5. Jika dataset terlalu kecil sekali, return error validasi.

Metrics:

1. accuracy
2. precision weighted
3. recall weighted
4. f1 weighted

---

## 20. Rules Prediction Service

Prediction service wajib melakukan:

1. Validasi model_path.
2. Load model dengan joblib.
3. Validasi feature_order.
4. Susun fitur sesuai feature_order.
5. Jalankan predict_proba.
6. Ambil class probabilitas tertinggi.
7. Ambil Top-3.
8. Konversi probabilitas menjadi score persen.
9. Return major_id dan score.

Rules Top-3:

1. Ambil probability terbesar.
2. Score = probability * 100.
3. Round score maksimal 2 angka desimal.
4. Rank berdasarkan urutan score.
5. major_id berasal dari model.classes_.
6. Jangan return major_id yang tidak ada di classes_.
7. Laravel tetap memvalidasi major_id ke tabel majors.

---

## 21. Rules Penyimpanan Model

FastAPI menyimpan model ke:

ml-service/storage/models/

Format nama file:

{algorithm}_{version}.joblib

Contoh:

random_forest_v202605161200.joblib
svm_v202605161230.joblib
knn_v202605161245.joblib

Rules:

1. Nama file tidak boleh mengandung spasi.
2. Gunakan version dari Laravel.
3. Path response harus relatif.
4. Jangan simpan model di folder public Laravel.
5. Jangan commit file model besar ke git.
6. Tambahkan storage/models/*.joblib ke .gitignore.
7. Commit hanya .gitkeep.

---

## 22. Rules File Model Content

File joblib sebaiknya menyimpan object dictionary:

{
  "model": trained_model,
  "algorithm": "random_forest",
  "version": "v202605161200",
  "feature_order": [...],
  "classes": [1, 2, 3],
  "metrics": {...}
}

Rules:

1. Jangan simpan model saja tanpa metadata.
2. Simpan feature_order.
3. Simpan algorithm.
4. Simpan version.
5. Simpan classes.
6. Simpan metrics.
7. Prediction service harus memastikan feature_order cocok.

---

## 23. Rules Error Handling

FastAPI tidak boleh mengirim stack trace ke Laravel.

Rules:

1. Error validasi return HTTP 422.
2. Token salah return HTTP 401.
3. Model tidak ditemukan return HTTP 404.
4. Error internal return HTTP 500 dengan message umum.
5. Response harus tetap JSON.
6. Laravel menyimpan message ke training_logs atau recommendation_sessions jika diperlukan.

Contoh error validasi:

{
  "success": false,
  "message": "Dataset tidak boleh kosong."
}

---

## 24. Rules Logging

FastAPI boleh log ke console.

Log minimal:

1. Training dimulai.
2. Training selesai.
3. Model disimpan.
4. Predict dimulai.
5. Predict selesai.
6. Error singkat.

Rules:

1. Jangan log password.
2. Jangan log token.
3. Jangan log data sensitif siswa secara berlebihan.
4. Untuk lokal cukup console log.
5. Untuk production bisa pakai file logging.

---

## 25. Rules Agar Tidak Bentrok dengan Laravel

1. FastAPI tidak membuat row ml_models.
2. FastAPI tidak membuat row training_logs.
3. FastAPI tidak membuat row recommendation_sessions.
4. FastAPI tidak membuat row recommendation_results.
5. FastAPI tidak membaca session user Laravel.
6. FastAPI tidak menerima student_id sebagai sumber otorisasi.
7. FastAPI tidak menyimpan hasil rekomendasi ke MySQL.
8. FastAPI hanya menerima dataset/features dari Laravel.
9. FastAPI hanya mengembalikan metadata dan hasil prediksi.
10. Laravel tetap menjadi sumber kebenaran database.

---

## 26. Rules Laravel FastApiClient

Laravel FastApiClient wajib punya method:

1. train(array $payload): array
2. predict(array $payload): array

Rules:

1. Jika ML_SERVICE_STUB=true, jangan panggil FastAPI.
2. Jika ML_SERVICE_STUB=false, ML_SERVICE_URL wajib ada.
3. Header Authorization wajib dikirim.
4. Timeout wajib diset.
5. Response failed harus dilempar sebagai exception.
6. Message error dari FastAPI harus ditangkap dan disimpan.

Header Laravel:

Authorization: Bearer {ML_SERVICE_TOKEN}
Accept: application/json
Content-Type: application/json

---

## 27. Rules Local Development

Langkah setup lokal:

1. Masuk folder ml-service.
2. Buat virtual environment.
3. Install requirements.
4. Jalankan uvicorn.
5. Set Laravel ML_SERVICE_STUB=false.
6. Set Laravel ML_SERVICE_URL=http://127.0.0.1:8001.
7. Jalankan training dari admin.

Command:

cd ml-service
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
uvicorn main:app --reload --port 8001

Laravel .env:

ML_SERVICE_STUB=false
ML_SERVICE_URL=http://127.0.0.1:8001
ML_SERVICE_TOKEN=secret-token-local
QUEUE_CONNECTION=sync

---

## 28. Rules Testing Manual

Test health:

GET http://127.0.0.1:8001/health

Expected:

{
  "success": true,
  "message": "ML service is healthy"
}

Test train:

POST http://127.0.0.1:8001/train

Header:

Authorization: Bearer secret-token-local

Body:

{
  "algorithm": "random_forest",
  "version": "v202605161200",
  "datasets": [
    {
      "score_logika": 85,
      "score_sosial": 70,
      "score_bahasa": 75,
      "score_kreativitas": 60,
      "score_analitis": 88,
      "nilai_mtk": 90,
      "nilai_bindo": 80,
      "nilai_bing": 82,
      "nilai_ipa": 87,
      "nilai_ips": 75,
      "jurusan_id": 1
    },
    {
      "score_logika": 60,
      "score_sosial": 90,
      "score_bahasa": 82,
      "score_kreativitas": 70,
      "score_analitis": 65,
      "nilai_mtk": 75,
      "nilai_bindo": 85,
      "nilai_bing": 83,
      "nilai_ipa": 72,
      "nilai_ips": 90,
      "jurusan_id": 2
    }
  ]
}

Catatan:

Dataset test di atas terlalu kecil untuk hasil model bagus.
Untuk training nyata, dataset harus lebih banyak.

---

## 29. Rules .gitignore

Tambahkan ke .gitignore root project:

ml-service/venv/
ml-service/.env
ml-service/storage/models/*.joblib
ml-service/storage/models/*.pkl
ml-service/__pycache__/
ml-service/app/**/__pycache__/

Rules:

1. Jangan commit virtual environment.
2. Jangan commit .env.
3. Jangan commit model artefak besar.
4. Commit .env.example dan .gitkeep.

---

## 30. Output Akhir yang Diharapkan

Setelah FastAPI asli selesai:

1. GET /health berhasil.
2. POST /train berhasil membuat file .joblib.
3. Response /train mengembalikan model_path dan metrics.
4. Laravel menyimpan ml_models.
5. Laravel menyimpan training_logs.
6. Laravel mengaktifkan model baru.
7. POST /predict berhasil mengembalikan Top-3.
8. Laravel menyimpan recommendation_results.
9. User bisa melihat hasil rekomendasi.
10. Training gagal tidak menghapus model aktif lama.

---

## 31. Checklist Implementasi FastAPI Asli

Sebelum dianggap selesai, pastikan:

1. Folder ml-service sudah ada.
2. requirements.txt sudah ada.
3. main.py sudah ada.
4. app/api/routes.py sudah ada.
5. app/core/config.py sudah ada.
6. app/core/security.py sudah ada.
7. app/schemas/training_schema.py sudah ada.
8. app/schemas/prediction_schema.py sudah ada.
9. app/services/preprocessing_service.py sudah ada.
10. app/services/training_service.py sudah ada.
11. app/services/prediction_service.py sudah ada.
12. app/models/model_loader.py sudah ada.
13. Endpoint /health berjalan.
14. Endpoint /train berjalan.
15. Endpoint /predict berjalan.
16. Token security berjalan.
17. Model tersimpan sebagai .joblib.
18. Feature order tersimpan di model.
19. Response train sesuai kontrak Laravel.
20. Response predict sesuai kontrak Laravel.
21. Error response tidak menampilkan stack trace.
22. Laravel .env sudah ML_SERVICE_STUB=false.
23. Laravel .env sudah ML_SERVICE_URL terisi.
24. Laravel FastApiClient mengirim token.
25. Admin training model tidak lagi gagal karena URL kosong.

Untuk kondisi kamu sekarang, langkah pertama yang harus dilakukan adalah membuat folder ml-service, menjalankan FastAPI di port 8001, lalu set Laravel:
ML_SERVICE_STUB=false
ML_SERVICE_URL=http://127.0.0.1:8001
ML_SERVICE_TOKEN=secret-token-local