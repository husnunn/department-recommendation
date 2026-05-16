# RULES FRONTEND USER / SISWA
# Sistem Rekomendasi Jurusan Kuliah
# Inertia.js + Vue 3 + Laravel Backend + FastAPI ML

## 1. Tujuan Rules

Rules ini digunakan untuk mengatur alur frontend bagian User/Siswa agar:

- Tidak bentrok dengan backend Laravel.
- Tidak bentrok dengan database.
- Tidak bentrok dengan rules admin.
- Tidak bentrok dengan rules perhitungan model ML.
- User tahu data apa yang wajib diisi dan tidak wajib.
- Frontend hanya mengirim data sesuai kebutuhan backend.
- Frontend tidak melakukan perhitungan ML langsung.
- Frontend hanya menampilkan status, form, validasi ringan, dan hasil rekomendasi.

Frontend user menggunakan:

- Inertia.js
- Vue 3
- Tailwind CSS

Backend tetap menangani:

- Validasi utama.
- Penyimpanan data.
- Perhitungan skor criteria.
- Request ke FastAPI.
- Penyimpanan hasil rekomendasi.

---

## 2. Prinsip Utama Frontend User

1. Frontend user tidak boleh menghitung hasil rekomendasi sendiri.
2. Frontend user tidak boleh memanggil FastAPI langsung.
3. Frontend user hanya berkomunikasi dengan Laravel.
4. Laravel yang akan memvalidasi data dan mengirim ke FastAPI.
5. Semua data wajib tetap divalidasi di backend meskipun frontend sudah validasi.
6. Frontend hanya memberi UX agar siswa mudah mengisi data.
7. Frontend harus menampilkan status kelengkapan data siswa.
8. Tombol "Proses Rekomendasi" hanya aktif jika data wajib sudah lengkap.

---

## 3. Alur Utama User

Urutan alur user:

1. Register
2. Login
3. Dashboard Siswa
4. Lengkapi Profil
5. Isi Kuesioner Minat & Bakat
6. Input Nilai Akademik
7. Proses Rekomendasi
8. Lihat Hasil Rekomendasi
9. Lihat Riwayat Rekomendasi
10. Download PDF

Rules:

1. User yang belum login tidak boleh masuk halaman siswa.
2. User dengan role siswa tidak boleh masuk halaman admin.
3. User siswa hanya boleh melihat data miliknya sendiri.
4. User tidak boleh memilih jurusan rekomendasi sendiri.
5. Jurusan rekomendasi berasal dari hasil ML.
6. Riwayat rekomendasi hanya menampilkan data milik siswa yang login.

---

## 4. Struktur Halaman User

Gunakan struktur halaman berikut:

resources/js/Pages/User/
├── Dashboard/
│   └── Index.vue
├── Profile/
│   ├── Complete.vue
│   └── Edit.vue
├── Questionnaire/
│   └── Index.vue
├── AcademicScore/
│   └── Index.vue
├── Recommendation/
│   ├── Show.vue
│   └── History.vue
└── Report/
    └── Download.vue

Halaman auth:

resources/js/Pages/Auth/
├── Login.vue
└── Register.vue

Layout:

resources/js/Layouts/
├── UserLayout.vue
├── GuestLayout.vue
└── AuthLayout.vue

Reusable components:

resources/js/Components/
├── base/
├── forms/
├── cards/
├── navigation/
└── feedback/

---

## 5. Rules Register Siswa

Halaman:

resources/js/Pages/Auth/Register.vue

Field register:

1. Nama Lengkap
2. Username
3. Password
4. Konfirmasi Password

Field wajib:

- nama
- username
- password
- password_confirmation

Field tidak wajib:

- email
- nisn
- kelas
- asal_sekolah

Rules:

1. Register siswa hanya membuat akun role siswa.
2. Email tidak wajib karena login memakai username.
3. Confirm password tidak disimpan ke database.
4. Nama disimpan ke tabel students.nama.
5. Username dan password disimpan ke tabel users.
6. Role otomatis siswa.
7. Siswa tidak boleh memilih role saat register.
8. Siswa tidak boleh membuat akun admin.
9. Setelah register berhasil, arahkan ke dashboard atau halaman lengkapi profil.

Payload register yang dikirim frontend:

{
  "nama": "Ahmad Zainul",
  "username": "ahmadzainul",
  "password": "password123",
  "password_confirmation": "password123"
}

Validasi frontend:

- nama wajib diisi.
- username wajib diisi.
- username minimal 4 karakter.
- username hanya huruf, angka, underscore, atau dash.
- password minimal 8 karakter.
- password_confirmation harus sama dengan password.

Catatan:

Validasi frontend hanya untuk UX.
Validasi utama tetap di backend.

---

## 6. Rules Login Siswa

Halaman:

resources/js/Pages/Auth/Login.vue

Field login:

1. Username
2. Password

Field wajib:

- username
- password

Field tidak wajib:

- email

Rules:

1. Login menggunakan username.
2. Jangan tampilkan input email.
3. Jika user berhasil login dan role siswa, arahkan ke dashboard siswa.
4. Jika role admin/superadmin, arahkan sesuai backend.
5. Jika login gagal, tampilkan pesan error dari backend.

Payload login:

{
  "username": "ahmadzainul",
  "password": "password123"
}

---

## 7. Rules Dashboard Siswa

Halaman:

resources/js/Pages/User/Dashboard/Index.vue

Dashboard menampilkan:

1. Sapaan nama siswa.
2. Status profil.
3. Status kuesioner.
4. Status nilai akademik.
5. Status rekomendasi terakhir.
6. Tombol lanjutkan proses.
7. Ringkasan Top-3 rekomendasi terakhir jika sudah ada.

Status yang ditampilkan:

- Profil Belum Lengkap
- Kuesioner Belum Diisi
- Kuesioner Selesai
- Nilai Akademik Belum Lengkap
- Siap Diproses
- Rekomendasi Selesai

Rules:

1. Dashboard tidak boleh menampilkan data siswa lain.
2. Dashboard hanya membaca props dari Inertia.
3. Dashboard tidak menghitung status sendiri jika backend sudah mengirim status.
4. Jika status profil belum lengkap, tombol utama mengarah ke halaman profil.
5. Jika kuesioner belum selesai, tombol utama mengarah ke kuesioner.
6. Jika nilai akademik belum lengkap, tombol utama mengarah ke input nilai.
7. Jika semua lengkap, tampilkan tombol proses rekomendasi.

---

## 8. Rules Profil Siswa

Halaman:

resources/js/Pages/User/Profile/Complete.vue
resources/js/Pages/User/Profile/Edit.vue

Field profil:

1. Nama Lengkap
2. NISN
3. Kelas
4. Asal Sekolah

Field wajib untuk register:

- nama

Field wajib untuk proses rekomendasi:

- nama

Field direkomendasikan wajib untuk kebutuhan admin/laporan:

- nisn
- kelas
- asal_sekolah

Field tidak wajib secara model ML:

- nisn
- kelas
- asal_sekolah

Rules:

1. Nama wajib ada karena digunakan sebagai identitas siswa.
2. NISN tidak dipakai dalam perhitungan ML.
3. Kelas tidak dipakai dalam perhitungan ML.
4. Asal sekolah tidak dipakai dalam perhitungan ML.
5. NISN, kelas, dan asal_sekolah berguna untuk filter admin dan laporan.
6. Frontend boleh menandai NISN, kelas, dan asal sekolah sebagai "Disarankan diisi".
7. Jangan mengirim field yang tidak ada di database.
8. Jangan mengubah username dari halaman profil kecuali ada fitur khusus.

Rekomendasi UX:

- Nama Lengkap: Wajib
- NISN: Disarankan
- Kelas: Disarankan
- Asal Sekolah: Disarankan

Contoh form profil:

Nama Lengkap:
Ahmad Zainul

NISN:
0067891234

Kelas:
XII IPA 1

Asal Sekolah:
MAN 3 Jombang

Payload update profil:

{
  "nama": "Ahmad Zainul",
  "nisn": "0067891234",
  "kelas": "XII IPA 1",
  "asal_sekolah": "MAN 3 Jombang"
}

---

## 9. Rules Kuesioner Minat & Bakat

Halaman:

resources/js/Pages/User/Questionnaire/Index.vue

Database terkait:

- criteria
- questions
- question_options
- student_answers

Data ditampilkan:

1. Kriteria
2. Pertanyaan
3. Opsi jawaban Likert

Opsi jawaban:

1 = Sangat Tidak Setuju
2 = Tidak Setuju
3 = Netral
4 = Setuju
5 = Sangat Setuju

Field wajib:

- Semua pertanyaan aktif wajib dijawab.

Field tidak wajib:

- Tidak ada field opsional pada kuesioner aktif.

Rules:

1. Pertanyaan diambil dari backend.
2. Frontend tidak boleh hardcode pertanyaan.
3. Frontend tidak boleh hardcode criteria sebagai sumber utama.
4. Frontend boleh hardcode label UI jika backend sudah mengirim opsi.
5. Semua pertanyaan aktif harus dijawab sebelum submit.
6. Jika ada 20 pertanyaan aktif, maka 20 jawaban wajib dikirim.
7. Jawaban harus angka 1 sampai 5.
8. Frontend tidak menghitung skor akhir criteria.
9. Backend yang menghitung rata-rata per criteria.
10. Jika siswa sudah pernah mengisi, frontend boleh menampilkan jawaban sebelumnya.
11. Submit ulang akan update jawaban lama, bukan membuat duplikasi.

Contoh payload kuesioner:

{
  "answers": [
    {
      "question_id": 1,
      "answer": 5
    },
    {
      "question_id": 2,
      "answer": 4
    },
    {
      "question_id": 3,
      "answer": 3
    }
  ]
}

UX rules:

1. Gunakan radio button atau card option.
2. Jangan gunakan input text bebas untuk jawaban Likert.
3. Tampilkan progress, contoh: "12 dari 20 pertanyaan sudah dijawab".
4. Tampilkan warning jika ada pertanyaan belum dijawab.
5. Tombol submit disabled jika masih ada pertanyaan belum dijawab.
6. Setelah submit berhasil, arahkan ke halaman input nilai akademik.

---

## 10. Rules Nilai Akademik

Halaman:

resources/js/Pages/User/AcademicScore/Index.vue

Database terkait:

- subjects
- academic_scores

Subject berasal dari admin.

Subject wajib minimal sesuai rules perhitungan:

1. Matematika
2. Bahasa Indonesia
3. Bahasa Inggris
4. IPA
5. IPS

Field wajib:

- Semua subject dengan is_required = true

Field tidak wajib:

- Subject dengan is_required = false

Rules:

1. Frontend mengambil daftar subject dari backend.
2. Frontend tidak boleh hardcode seluruh subject sebagai sumber utama.
3. Frontend boleh menampilkan subject wajib di bagian atas.
4. Subject dengan is_required true wajib diisi.
5. Subject dengan is_required false boleh kosong.
6. Nilai harus angka.
7. Nilai minimal 0.
8. Nilai maksimal 100.
9. Nilai boleh decimal jika backend menggunakan decimal.
10. Jika input kosong pada subject opsional, jangan kirim sebagai 0.
11. Jangan mengubah nilai kosong menjadi 0 di frontend.
12. Backend tetap validasi ulang nilai.
13. Tombol simpan boleh aktif jika semua subject wajib sudah terisi.
14. Tombol proses rekomendasi hanya aktif jika semua subject wajib sudah tersimpan.

---

## 11. Contoh Pengisian Nilai Akademik

Contoh nilai yang benar:

Matematika:
88

Bahasa Indonesia:
84

Bahasa Inggris:
82

IPA:
86

IPS:
80

Fisika:
kosong jika opsional

Kimia:
kosong jika opsional

Biologi:
kosong jika opsional

Ekonomi:
kosong jika opsional

Informatika:
90 jika siswa punya nilai tersebut

Contoh payload yang benar:

{
  "scores": [
    {
      "subject_id": 1,
      "nilai": 88
    },
    {
      "subject_id": 2,
      "nilai": 84
    },
    {
      "subject_id": 3,
      "nilai": 82
    },
    {
      "subject_id": 4,
      "nilai": 86
    },
    {
      "subject_id": 5,
      "nilai": 80
    },
    {
      "subject_id": 10,
      "nilai": 90
    }
  ]
}

Contoh payload yang tidak disarankan:

{
  "scores": [
    {
      "subject_id": 6,
      "nilai": 0
    }
  ]
}

Alasan:

- Nilai 0 berarti siswa benar-benar mendapat nilai 0.
- Jika subject opsional tidak diisi, lebih baik tidak dikirim.
- Jangan gunakan 0 sebagai pengganti kosong.

---

## 12. Validasi Nilai Akademik di Frontend

Rules input nilai:

1. Hanya angka.
2. Boleh decimal maksimal 2 angka di belakang koma.
3. Minimal 0.
4. Maksimal 100.
5. Tidak boleh huruf.
6. Tidak boleh simbol selain titik desimal jika decimal didukung.
7. Tidak boleh minus.
8. Subject wajib tidak boleh kosong.
9. Subject opsional boleh kosong.

Contoh nilai valid:

- 75
- 80
- 88.5
- 92.25
- 100
- 0

Contoh nilai tidak valid:

- 101
- -5
- delapan puluh
- 80abc
- kosong untuk subject wajib

Pesan error yang disarankan:

- "Nilai wajib diisi."
- "Nilai harus berupa angka."
- "Nilai minimal 0."
- "Nilai maksimal 100."
- "Gunakan maksimal 2 angka desimal."

---

## 13. Rules Kelengkapan Data Sebelum Rekomendasi

Tombol "Proses Rekomendasi" hanya aktif jika:

1. User sudah login.
2. Role user adalah siswa.
3. Student profile tersedia.
4. Nama siswa tersedia.
5. Semua pertanyaan aktif sudah dijawab.
6. Semua subject wajib sudah memiliki nilai.
7. Ada model ML aktif di backend.
8. Backend menyatakan data siap diproses.

Rules:

1. Frontend boleh menampilkan status "Belum Siap".
2. Frontend tidak boleh memaksa proses jika backend mengembalikan status belum siap.
3. Jika model aktif belum tersedia, tampilkan pesan:
   "Model rekomendasi belum tersedia. Silakan hubungi admin."
4. Jika kuesioner belum lengkap, arahkan ke halaman kuesioner.
5. Jika nilai akademik belum lengkap, arahkan ke halaman input nilai.
6. Jika profil belum lengkap, arahkan ke halaman profil.

---

## 14. Rules Proses Rekomendasi

Halaman:

resources/js/Pages/User/Recommendation/Show.vue

Action:

- Klik tombol "Proses Rekomendasi"

Frontend mengirim request ke Laravel:

POST /user/recommendations/process

Payload:

{}

Rules:

1. Frontend tidak perlu mengirim semua jawaban lagi.
2. Frontend tidak perlu mengirim semua nilai lagi.
3. Backend mengambil data dari database.
4. Backend membuat recommendation_session.
5. Backend memanggil FastAPI.
6. Backend menyimpan recommendation_results.
7. Frontend menerima hasil atau redirect ke halaman hasil.
8. Saat proses berjalan, tombol harus disabled.
9. Tampilkan loading state.
10. Hindari double click dengan disabled button.
11. Jika gagal, tampilkan pesan error dari backend.

Pesan loading:

"Sedang memproses rekomendasi jurusan..."

Pesan gagal:

"Rekomendasi belum dapat diproses. Periksa kembali kuesioner dan nilai akademik."

---

## 15. Rules Hasil Rekomendasi

Halaman:

resources/js/Pages/User/Recommendation/Show.vue

Data yang ditampilkan:

1. Nama siswa.
2. Tanggal rekomendasi.
3. Top-3 jurusan.
4. Persentase kecocokan.
5. Deskripsi jurusan.
6. Tombol download PDF.
7. Tombol lihat riwayat.
8. Tombol proses ulang jika diperbolehkan.

Rules:

1. Hasil berasal dari recommendation_results.
2. Score ditampilkan sebagai persentase.
3. Tampilkan maksimal 2 angka desimal.
4. Jangan tampilkan istilah teknis seperti predict_proba.
5. Gunakan label "Persentase Kecocokan".
6. Rank 1 ditampilkan sebagai rekomendasi utama.
7. Rank 2 dan 3 ditampilkan sebagai alternatif.
8. Jika hanya ada 1 atau 2 hasil, tampilkan sesuai data yang tersedia.
9. Jangan membuat hasil dummy di frontend.
10. Jangan menampilkan hasil jika session status failed.

Contoh tampilan:

Rekomendasi Utama:
Teknik Informatika
Persentase Kecocokan: 92.34%

Alternatif 1:
Sistem Informasi
Persentase Kecocokan: 87.11%

Alternatif 2:
Data Science
Persentase Kecocokan: 81.45%

Catatan tampilan:

"Hasil ini adalah rekomendasi berdasarkan data kuesioner dan nilai akademik. Keputusan akhir tetap dapat mempertimbangkan minat pribadi, konsultasi guru BK, dan pilihan kampus."

---

## 16. Rules Riwayat Rekomendasi

Halaman:

resources/js/Pages/User/Recommendation/History.vue

Data yang ditampilkan:

1. Tanggal rekomendasi.
2. Rekomendasi utama.
3. Score rekomendasi utama.
4. Status session.
5. Tombol detail.
6. Tombol download PDF jika session completed.

Rules:

1. Riwayat hanya milik siswa yang login.
2. Jangan tampilkan riwayat siswa lain.
3. Gunakan pagination jika data banyak.
4. Session failed boleh ditampilkan dengan status gagal, tetapi tidak perlu tombol PDF.
5. Session processing boleh ditampilkan dengan status sedang diproses.
6. Session completed boleh dibuka detail.

Table columns:

- Tanggal
- Rekomendasi Utama
- Score
- Status
- Aksi

---

## 17. Rules Download PDF

Halaman:

resources/js/Pages/User/Report/Download.vue

Action:

- Download hasil rekomendasi sebagai PDF

Rules:

1. PDF hanya bisa diunduh untuk session milik siswa yang login.
2. PDF hanya bisa diunduh jika session status completed.
3. PDF berisi Top-3 rekomendasi.
4. PDF tidak boleh berisi data siswa lain.
5. Frontend hanya membuka URL download dari Laravel.
6. Laravel yang generate PDF.

Contoh tombol:

Download PDF

Endpoint contoh:

GET /user/recommendations/{session}/download

---

## 18. Rules Status Badge User

Gunakan status badge untuk memudahkan siswa.

Status profil:

- Lengkap
- Belum Lengkap

Status kuesioner:

- Belum Diisi
- Sebagian Terisi
- Selesai

Status nilai akademik:

- Belum Diisi
- Belum Lengkap
- Lengkap

Status rekomendasi:

- Belum Diproses
- Diproses
- Selesai
- Gagal

Rules warna:

- Hijau: Selesai / Lengkap
- Kuning: Sebagian / Perlu Dilengkapi
- Biru: Diproses
- Merah: Gagal / Error
- Abu-abu: Belum Ada

---

## 19. Rules Navigasi User

Menu user:

1. Dashboard
2. Profil Saya
3. Kuesioner
4. Nilai Akademik
5. Hasil Rekomendasi
6. Riwayat
7. Logout

Rules:

1. Jangan tampilkan menu admin.
2. Gunakan UserLayout.
3. Navbar dan sidebar harus reusable.
4. Highlight menu aktif berdasarkan route.
5. Pada mobile, gunakan bottom navigation atau hamburger menu.
6. Logout harus memanggil endpoint Laravel.

---

## 20. Rules Reusable Component User

Komponen yang wajib reusable:

1. UserNavbar
2. UserSidebar
3. UserFooter
4. BaseCard
5. BaseButton
6. BaseInput
7. BaseSelect
8. BaseBadge
9. BaseAlert
10. BaseTable
11. BasePagination
12. FormError
13. LoadingState
14. EmptyState
15. RecommendationCard
16. ScoreInput
17. QuestionnaireOption
18. ProgressStepper

Rules:

1. Jangan menulis ulang card rekomendasi di banyak halaman.
2. Jangan menulis ulang input nilai di banyak halaman.
3. Jangan menulis ulang status badge di banyak halaman.
4. Jangan menulis ulang table riwayat jika bisa memakai BaseTable.
5. Komponen menerima data lewat props.
6. Komponen mengirim event lewat emit.
7. Komponen tidak mengambil data langsung dari API.

---

## 21. Rules Frontend Tidak Boleh Melakukan Ini

Dilarang:

1. Frontend menghitung rekomendasi jurusan sendiri.
2. Frontend menentukan major_id hasil rekomendasi.
3. Frontend memanggil FastAPI langsung.
4. Frontend mengirim role saat register.
5. Frontend mengizinkan siswa memilih role.
6. Frontend menganggap nilai kosong sebagai 0.
7. Frontend menampilkan data siswa lain.
8. Frontend membuat hasil rekomendasi dummy.
9. Frontend mengubah score hasil ML.
10. Frontend mengizinkan proses rekomendasi saat data wajib belum lengkap.
11. Frontend hardcode subject wajib jika backend sudah mengirim subject.
12. Frontend hardcode pertanyaan jika backend sudah mengirim pertanyaan.

---

## 22. Mapping Field Wajib dan Tidak Wajib

Register:

Wajib:
- nama
- username
- password
- password_confirmation

Tidak wajib:
- email
- nisn
- kelas
- asal_sekolah

Profil:

Wajib minimal:
- nama

Disarankan:
- nisn
- kelas
- asal_sekolah

Tidak dipakai ML:
- nisn
- kelas
- asal_sekolah

Kuesioner:

Wajib:
- semua pertanyaan aktif

Tidak wajib:
- tidak ada

Nilai akademik:

Wajib:
- semua subject dengan is_required = true

Tidak wajib:
- semua subject dengan is_required = false

Proses rekomendasi:

Wajib:
- profil siswa tersedia
- nama siswa tersedia
- kuesioner selesai
- nilai wajib lengkap
- model aktif tersedia di backend

Tidak wajib:
- nilai subject opsional
- email
- upload file
- memilih jurusan manual

---

## 23. Contoh User Flow Lengkap

Contoh siswa baru:

1. Siswa register:
   - Nama: Ahmad Zainul
   - Username: ahmadzainul
   - Password: password123
   - Konfirmasi Password: password123

2. Siswa login:
   - Username: ahmadzainul
   - Password: password123

3. Siswa lengkapi profil:
   - Nama: Ahmad Zainul
   - NISN: 0067891234
   - Kelas: XII IPA 1
   - Asal Sekolah: MAN 3 Jombang

4. Siswa isi kuesioner:
   - Semua pertanyaan dijawab dengan skala 1 sampai 5.

5. Siswa input nilai:
   - Matematika: 88
   - Bahasa Indonesia: 84
   - Bahasa Inggris: 82
   - IPA: 86
   - IPS: 80
   - Informatika: 90 jika tersedia
   - Subject opsional lain boleh kosong.

6. Siswa klik:
   - Proses Rekomendasi

7. Sistem menampilkan:
   - Teknik Informatika: 92.34%
   - Sistem Informasi: 87.11%
   - Data Science: 81.45%

8. Siswa dapat:
   - Lihat detail
   - Download PDF
   - Lihat riwayat

---

## 24. Rules agar Tidak Bentrok dengan Backend

1. Nama field frontend harus sama dengan validasi backend.
2. Register menggunakan password_confirmation, bukan confirm_password.
3. Login menggunakan username, bukan email.
4. Input nilai menggunakan subject_id dan nilai.
5. Input kuesioner menggunakan question_id dan answer.
6. Frontend tidak mengirim student_id saat simpan data siswa login.
7. Backend menentukan student_id dari auth user.
8. Frontend tidak mengirim role.
9. Frontend tidak mengirim major_id untuk hasil rekomendasi.
10. Frontend tidak mengirim score rekomendasi.
11. Frontend tidak mengirim session_id saat proses rekomendasi baru.
12. Frontend tidak mengirim nilai kosong sebagai 0.
13. Frontend tidak mengirim question yang tidak aktif.
14. Frontend mengikuti status readiness dari backend.

---

## 25. Endpoint yang Direkomendasikan untuk Frontend User

Auth:

GET /register
POST /register
GET /login
POST /login
POST /logout

User:

GET /user/dashboard

Profile:

GET /user/profile
PUT /user/profile

Questionnaire:

GET /user/questionnaire
POST /user/questionnaire

Academic Score:

GET /user/academic-scores
POST /user/academic-scores

Recommendation:

GET /user/recommendations
POST /user/recommendations/process
GET /user/recommendations/{session}
GET /user/recommendations/history
GET /user/recommendations/{session}/download

Rules:

1. Semua endpoint user wajib auth.
2. Semua endpoint user wajib role siswa.
3. Route admin dan user harus dipisah.
4. Frontend hanya memakai endpoint milik user.
5. Siswa tidak boleh mengakses endpoint admin.

---

## 26. Checklist Frontend User Sebelum Selesai

Checklist register:

- Form hanya nama, username, password, password_confirmation.
- Tidak ada input role.
- Tidak ada input email wajib.
- Error backend tampil di form.

Checklist dashboard:

- Menampilkan status profil.
- Menampilkan status kuesioner.
- Menampilkan status nilai.
- Menampilkan status rekomendasi.
- CTA sesuai status.

Checklist profil:

- Nama bisa diedit.
- NISN, kelas, asal sekolah bisa diisi.
- Tidak mengubah username kecuali ada fitur khusus.

Checklist kuesioner:

- Pertanyaan dari backend.
- Opsi dari backend.
- Semua pertanyaan aktif wajib dijawab.
- Progress tampil.
- Tidak ada input text bebas untuk Likert.

Checklist nilai akademik:

- Subject dari backend.
- Subject wajib tidak boleh kosong.
- Subject opsional boleh kosong.
- Nilai harus 0 sampai 100.
- Kosong tidak dikirim sebagai 0.

Checklist rekomendasi:

- Tombol proses disabled jika belum siap.
- Loading saat proses.
- Hasil Top-3 dari backend.
- Score ditampilkan persen.
- PDF bisa diunduh.
- Riwayat hanya milik user login.

---

## 27. Kesimpulan Rules Wajib User

Untuk siswa, data yang benar-benar wajib agar rekomendasi bisa diproses adalah:

1. Akun siswa aktif.
2. Nama siswa.
3. Semua jawaban kuesioner aktif.
4. Semua nilai akademik wajib.
5. Backend memiliki model ML aktif.

Data yang tidak wajib untuk perhitungan ML:

1. Email.
2. NISN.
3. Kelas.
4. Asal sekolah.
5. Nilai subject opsional.

Namun NISN, kelas, dan asal sekolah tetap disarankan diisi karena berguna untuk kebutuhan admin, filter, laporan, dan identitas siswa.