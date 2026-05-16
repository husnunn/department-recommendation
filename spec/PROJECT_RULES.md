# PROJECT STRUCTURE RULES
# Sistem Rekomendasi Jurusan Kuliah
# Laravel + Blade Admin + Inertia Vue User

## 1. Tujuan Rules

Rules ini digunakan untuk menjaga struktur folder project agar:

- Mudah dibaca.
- Mudah dikembangkan.
- Mudah dipahami oleh developer baru.
- Memisahkan fitur Admin dan User/Siswa dengan jelas.
- Menghindari duplikasi komponen Vue.
- Memastikan komponen yang sama digunakan ulang sebagai reusable component.
- Menjaga pola desain kode tetap konsisten.

Project ini menggunakan:

- Laravel sebagai backend utama.
- Blade untuk halaman Admin.
- Inertia.js + Vue 3 untuk halaman User/Siswa.
- Tailwind CSS untuk styling.
- MySQL sebagai database.
- FastAPI sebagai microservice Machine Learning.

---

## 2. Prinsip Utama Struktur Folder

Gunakan prinsip berikut:

1. Pisahkan fitur berdasarkan area:
   - Admin
   - User/Siswa
   - Auth
   - Shared/Common

2. Jangan mencampur file Admin dan User di folder yang sama.

3. Komponen Vue yang dipakai lebih dari satu halaman wajib dipindah ke folder reusable component.

4. Komponen yang hanya dipakai pada satu halaman boleh diletakkan di folder lokal halaman tersebut.

5. Controller Laravel dipisahkan berdasarkan area:
   - Admin controller
   - User controller
   - Auth controller

6. Logic bisnis tidak boleh terlalu banyak di Controller.
   Gunakan Service class untuk proses bisnis.

7. Query database kompleks sebaiknya tidak ditulis langsung di Controller.
   Gunakan Repository atau Query class jika dibutuhkan.

8. Nama folder dan file harus deskriptif dan konsisten.

---

## 3. Struktur Folder Laravel yang Direkomendasikan

Gunakan struktur berikut:

app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── MajorController.php
│   │   │   ├── CriteriaController.php
│   │   │   ├── QuestionController.php
│   │   │   ├── StudentController.php
│   │   │   ├── TrainingController.php
│   │   │   ├── TrainingLogController.php
│   │   │   └── AdminUserController.php
│   │   │
│   │   ├── User/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── QuestionnaireController.php
│   │   │   ├── AcademicScoreController.php
│   │   │   ├── RecommendationController.php
│   │   │   └── RecommendationHistoryController.php
│   │   │
│   │   └── Auth/
│   │       ├── LoginController.php
│   │       ├── RegisterController.php
│   │       └── LogoutController.php
│   │
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   └── EnsureStudentProfileCompleted.php
│   │
│   ├── Requests/
│   │   ├── Admin/
│   │   │   ├── StoreMajorRequest.php
│   │   │   ├── UpdateMajorRequest.php
│   │   │   ├── StoreQuestionRequest.php
│   │   │   └── UpdateQuestionRequest.php
│   │   │
│   │   ├── User/
│   │   │   ├── UpdateProfileRequest.php
│   │   │   ├── StoreQuestionnaireAnswerRequest.php
│   │   │   └── StoreAcademicScoreRequest.php
│   │   │
│   │   └── Auth/
│   │       ├── LoginRequest.php
│   │       └── RegisterRequest.php
│   │
│   └── Resources/
│       ├── StudentResource.php
│       ├── MajorResource.php
│       └── RecommendationResultResource.php
│
├── Models/
│   ├── User.php
│   ├── Student.php
│   ├── Major.php
│   ├── Criteria.php
│   ├── Question.php
│   ├── QuestionOption.php
│   ├── StudentAnswer.php
│   ├── Subject.php
│   ├── AcademicScore.php
│   ├── RecommendationSession.php
│   ├── RecommendationResult.php
│   ├── TrainingDataset.php
│   ├── MlModel.php
│   └── TrainingLog.php
│
├── Services/
│   ├── Auth/
│   │   ├── RegisterStudentService.php
│   │   └── LoginService.php
│   │
│   ├── Recommendation/
│   │   ├── RecommendationService.php
│   │   ├── RecommendationPayloadBuilder.php
│   │   └── RecommendationResultService.php
│   │
│   ├── MachineLearning/
│   │   ├── FastApiClient.php
│   │   ├── ModelTrainingService.php
│   │   └── ModelVersionService.php
│   │
│   └── Report/
│       ├── RecommendationPdfService.php
│       └── ExportReportService.php
│
├── Repositories/
│   ├── StudentRepository.php
│   ├── MajorRepository.php
│   ├── QuestionRepository.php
│   ├── RecommendationRepository.php
│   └── TrainingRepository.php
│
└── Enums/
    ├── UserRole.php
    ├── RecommendationStatus.php
    └── TrainingStatus.php

---

## 4. Struktur Routes

Pisahkan routes berdasarkan area.

routes/
├── web.php
├── auth.php
├── admin.php
└── user.php

Aturan:

- routes/web.php hanya untuk halaman umum.
- routes/auth.php untuk login, register, logout.
- routes/admin.php untuk halaman admin.
- routes/user.php untuk halaman siswa/user.

Contoh struktur route:

routes/web.php
- Landing page
- Redirect berdasarkan role

routes/auth.php
- Login
- Register siswa
- Logout

routes/admin.php
- Dashboard admin
- CRUD jurusan
- CRUD kriteria
- CRUD pertanyaan
- Manajemen siswa
- Training model
- Log training
- Laporan

routes/user.php
- Dashboard siswa
- Profil siswa
- Kuesioner
- Input nilai akademik
- Hasil rekomendasi
- Riwayat rekomendasi
- Download PDF

---

## 5. Struktur Blade untuk Admin

Admin menggunakan Blade.

resources/
├── views/
│   ├── layouts/
│   │   └── admin.blade.php
│   │
│   ├── admin/
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   │
│   │   ├── majors/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   │
│   │   ├── criteria/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   │
│   │   ├── questions/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   │
│   │   ├── students/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   │
│   │   ├── trainings/
│   │   │   ├── index.blade.php
│   │   │   └── logs.blade.php
│   │   │
│   │   └── reports/
│   │       └── index.blade.php
│   │
│   └── components/
│       └── admin/
│           ├── sidebar.blade.php
│           ├── navbar.blade.php
│           ├── footer.blade.php
│           ├── card.blade.php
│           ├── table.blade.php
│           ├── alert.blade.php
│           ├── badge.blade.php
│           ├── modal.blade.php
│           └── form-error.blade.php

Aturan Blade Admin:

1. Semua halaman admin wajib menggunakan layout:
   resources/views/layouts/admin.blade.php

2. Sidebar admin wajib dibuat reusable:
   resources/views/components/admin/sidebar.blade.php

3. Navbar admin wajib dibuat reusable:
   resources/views/components/admin/navbar.blade.php

4. Table admin yang sering dipakai wajib dibuat reusable:
   resources/views/components/admin/table.blade.php

5. Card statistik wajib dibuat reusable:
   resources/views/components/admin/card.blade.php

6. Hindari menulis ulang struktur HTML table, card, alert, badge, modal di banyak halaman.

---

## 6. Struktur Vue untuk User/Siswa

User/Siswa menggunakan Inertia.js + Vue 3.

resources/
├── js/
│   ├── app.js
│   ├── bootstrap.js
│   │
│   ├── Pages/
│   │   ├── Auth/
│   │   │   ├── Login.vue
│   │   │   └── Register.vue
│   │   │
│   │   ├── User/
│   │   │   ├── Dashboard/
│   │   │   │   └── Index.vue
│   │   │   │
│   │   │   ├── Profile/
│   │   │   │   ├── Edit.vue
│   │   │   │   └── Complete.vue
│   │   │   │
│   │   │   ├── Questionnaire/
│   │   │   │   ├── Index.vue
│   │   │   │   └── Result.vue
│   │   │   │
│   │   │   ├── AcademicScore/
│   │   │   │   └── Index.vue
│   │   │   │
│   │   │   ├── Recommendation/
│   │   │   │   ├── Show.vue
│   │   │   │   └── History.vue
│   │   │   │
│   │   │   └── Report/
│   │   │       └── Download.vue
│   │   │
│   │   └── Public/
│   │       └── Welcome.vue
│   │
│   ├── Layouts/
│   │   ├── UserLayout.vue
│   │   ├── GuestLayout.vue
│   │   └── AuthLayout.vue
│   │
│   ├── Components/
│   │   ├── base/
│   │   │   ├── BaseButton.vue
│   │   │   ├── BaseInput.vue
│   │   │   ├── BaseTextarea.vue
│   │   │   ├── BaseSelect.vue
│   │   │   ├── BaseCard.vue
│   │   │   ├── BaseTable.vue
│   │   │   ├── BaseBadge.vue
│   │   │   ├── BaseModal.vue
│   │   │   ├── BaseAlert.vue
│   │   │   ├── BaseEmptyState.vue
│   │   │   ├── BaseLoading.vue
│   │   │   └── BasePagination.vue
│   │   │
│   │   ├── navigation/
│   │   │   ├── UserNavbar.vue
│   │   │   ├── UserSidebar.vue
│   │   │   ├── UserFooter.vue
│   │   │   └── MobileBottomNavigation.vue
│   │   │
│   │   ├── forms/
│   │   │   ├── FormGroup.vue
│   │   │   ├── FormLabel.vue
│   │   │   ├── FormError.vue
│   │   │   └── PasswordInput.vue
│   │   │
│   │   ├── cards/
│   │   │   ├── StatisticCard.vue
│   │   │   ├── RecommendationCard.vue
│   │   │   ├── MajorCard.vue
│   │   │   └── ProfileCard.vue
│   │   │
│   │   └── feedback/
│   │       ├── ToastMessage.vue
│   │       ├── ConfirmDialog.vue
│   │       └── PageLoading.vue
│   │
│   ├── Composables/
│   │   ├── useAuth.js
│   │   ├── useFlash.js
│   │   ├── useFormError.js
│   │   ├── useRecommendation.js
│   │   └── useConfirmDialog.js
│   │
│   ├── constants/
│   │   ├── roles.js
│   │   ├── routes.js
│   │   └── questionnaire.js
│   │
│   └── utils/
│       ├── formatDate.js
│       ├── formatNumber.js
│       ├── formatPercent.js
│       └── routeHelper.js

---

## 7. Rules Reusable Component Vue

Komponen Vue wajib dijadikan reusable jika:

1. Dipakai lebih dari satu halaman.
2. Struktur HTML-nya sama.
3. Styling-nya sama atau hampir sama.
4. Hanya beda data melalui props.
5. Hanya beda event melalui emit.
6. Merupakan elemen umum seperti:
   - button
   - input
   - select
   - card
   - table
   - modal
   - alert
   - badge
   - navbar
   - footer
   - pagination
   - loading
   - empty state

Contoh:

Jangan membuat card rekomendasi berulang seperti ini:

Pages/User/Dashboard/Index.vue
Pages/User/Recommendation/History.vue
Pages/User/Recommendation/Show.vue

Jika tampilan card sama, buat:

resources/js/Components/cards/RecommendationCard.vue

Lalu gunakan ulang di semua halaman.

---

## 8. Aturan Penamaan Component Vue

Gunakan PascalCase untuk file component.

Benar:
- BaseButton.vue
- BaseInput.vue
- UserNavbar.vue
- RecommendationCard.vue
- FormError.vue

Salah:
- button.vue
- inputComponent.vue
- navbar_user.vue
- recommendation-card.vue

Aturan prefix:

1. Base*
   Untuk komponen paling dasar.
   Contoh:
   - BaseButton.vue
   - BaseInput.vue
   - BaseCard.vue
   - BaseTable.vue

2. User*
   Untuk komponen khusus layout user.
   Contoh:
   - UserNavbar.vue
   - UserSidebar.vue
   - UserFooter.vue

3. Form*
   Untuk komponen form.
   Contoh:
   - FormGroup.vue
   - FormLabel.vue
   - FormError.vue

4. Recommendation*
   Untuk komponen yang berkaitan dengan rekomendasi.
   Contoh:
   - RecommendationCard.vue
   - RecommendationScore.vue
   - RecommendationSummary.vue

---

## 9. Aturan Layout Vue

Gunakan layout agar halaman tidak mengulang navbar, footer, dan struktur halaman.

resources/js/Layouts/UserLayout.vue

Isi layout user:

- UserNavbar
- UserSidebar atau mobile navigation
- Main content slot
- UserFooter

Semua halaman siswa wajib menggunakan:

defineOptions({
    layout: UserLayout
})

Atau gunakan pattern layout Inertia sesuai konfigurasi project.

Halaman yang wajib memakai UserLayout:

- Dashboard siswa
- Lengkapi profil
- Kuesioner
- Input nilai akademik
- Hasil rekomendasi
- Riwayat rekomendasi

Halaman yang memakai GuestLayout:

- Welcome
- Login
- Register

---

## 10. Rules Page Vue

Setiap halaman Vue di folder Pages hanya boleh berisi:

1. Layout halaman.
2. Pemanggilan reusable component.
3. Props dari Inertia.
4. Event handler ringan.
5. State lokal sederhana.

Halaman Vue tidak boleh berisi:

1. Komponen besar yang bisa dipisah.
2. Logic formatting berulang.
3. Logic validasi kompleks.
4. Struktur table panjang yang dipakai ulang.
5. Struktur card panjang yang dipakai ulang.

Jika logic mulai panjang, pindahkan ke:

- Composables
- Utils
- Service frontend bila dibutuhkan

Contoh:

resources/js/Pages/User/Recommendation/Show.vue

Boleh:
- Menampilkan RecommendationCard.
- Menampilkan score.
- Menampilkan tombol download PDF.

Tidak boleh:
- Menulis ulang HTML card rekomendasi besar.
- Menghitung format persen manual berulang.
- Menulis ulang table riwayat rekomendasi.

---

## 11. Rules Composables Vue

Gunakan Composables untuk logic yang digunakan ulang.

Contoh:

resources/js/Composables/useFlash.js
Untuk membaca flash message dari Inertia.

resources/js/Composables/useConfirmDialog.js
Untuk dialog konfirmasi.

resources/js/Composables/useRecommendation.js
Untuk logic tampilan hasil rekomendasi.

resources/js/Composables/useFormError.js
Untuk helper error form.

Rules:

1. File composable harus diawali dengan use.
2. Jangan simpan template HTML di composable.
3. Composable hanya untuk logic.
4. Composable boleh return state, computed, dan function.

---

## 12. Rules Utils Vue

Gunakan utils untuk helper kecil yang tidak butuh reactive state.

Contoh:

resources/js/utils/formatDate.js
resources/js/utils/formatNumber.js
resources/js/utils/formatPercent.js

Rules:

1. Utils tidak boleh bergantung pada komponen Vue.
2. Utils harus pure function jika memungkinkan.
3. Jangan duplikasi formatting di banyak halaman.
4. Format persen rekomendasi harus lewat formatPercent.js.
5. Format tanggal harus lewat formatDate.js.

---

## 13. Rules Styling Tailwind

Gunakan Tailwind CSS dengan pola konsisten.

Aturan:

1. Jangan terlalu banyak inline class panjang di setiap halaman.
2. Untuk struktur yang sering dipakai, pindahkan ke component.
3. Gunakan spacing konsisten:
   - p-4 untuk card kecil
   - p-6 untuk card utama
   - gap-4 untuk grid umum
   - gap-6 untuk section besar

4. Gunakan warna konsisten:
   - primary untuk aksi utama
   - gray/slate untuk teks dan border
   - green untuk success
   - red untuk error
   - yellow/orange untuk warning

5. Jangan membuat gaya button berulang.
   Gunakan BaseButton.

6. Jangan membuat gaya input berulang.
   Gunakan BaseInput, BaseSelect, BaseTextarea.

---

## 14. Design Pattern Backend Laravel

Gunakan pattern:

Controller -> Request -> Service -> Repository -> Model

Penjelasan:

1. Controller
   Bertugas menerima request dan mengembalikan response/view.

2. Form Request
   Bertugas validasi input.

3. Service
   Bertugas menjalankan logic bisnis.

4. Repository
   Bertugas query database yang mulai kompleks.

5. Model
   Bertugas merepresentasikan table dan relasi.

Contoh alur registrasi siswa:

RegisterController
-> RegisterRequest
-> RegisterStudentService
-> User model + Student model

Contoh alur rekomendasi:

RecommendationController
-> RecommendationService
-> RecommendationPayloadBuilder
-> FastApiClient
-> RecommendationResultService
-> RecommendationSession model
-> RecommendationResult model

---

## 15. Rules Controller Laravel

Controller harus tipis.

Controller boleh:

- Menerima request.
- Memanggil service.
- Mengirim response.
- Render Inertia atau Blade.
- Redirect dengan flash message.

Controller tidak boleh:

- Menulis query kompleks panjang.
- Menulis proses machine learning langsung.
- Menulis validasi manual panjang.
- Menulis logic perhitungan rekomendasi terlalu banyak.

Contoh controller yang benar:

public function store(StoreAcademicScoreRequest $request, AcademicScoreService $service)
{
    $service->store(auth()->user(), $request->validated());

    return redirect()->back()->with('success', 'Nilai akademik berhasil disimpan.');
}

---

## 16. Rules Service Laravel

Service digunakan untuk logic bisnis.

Contoh service:

app/Services/Auth/RegisterStudentService.php
- Membuat user.
- Membuat student.
- Set role siswa.

app/Services/Recommendation/RecommendationService.php
- Membuat sesi rekomendasi.
- Mengambil jawaban siswa.
- Mengambil nilai akademik.
- Mengirim payload ke FastAPI.
- Menyimpan hasil rekomendasi.

app/Services/MachineLearning/FastApiClient.php
- Menghubungi FastAPI.
- Mengirim data.
- Menerima hasil rekomendasi.

Rules:

1. Service tidak boleh return Blade langsung.
2. Service tidak boleh membaca request langsung.
3. Service menerima data dari Controller.
4. Service boleh menggunakan DB transaction.
5. Service boleh memanggil repository.

---

## 17. Rules Repository Laravel

Repository digunakan jika query mulai kompleks.

Contoh:

app/Repositories/StudentRepository.php
- filter siswa berdasarkan nama
- filter siswa berdasarkan NISN
- filter siswa berdasarkan kelas

app/Repositories/RecommendationRepository.php
- mengambil riwayat rekomendasi siswa
- mengambil statistik rekomendasi admin

app/Repositories/TrainingRepository.php
- mengambil dataset training
- mengambil log training

Rules:

1. Query yang sederhana boleh langsung di model.
2. Query yang digunakan berulang sebaiknya masuk repository.
3. Repository tidak boleh mengurus validasi request.
4. Repository tidak boleh mengurus response.

---

## 18. Rules Model Relationship

Pastikan setiap model punya relasi yang jelas.

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
- belongsTo QuestionOption

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

MlModel:
- hasMany TrainingLog

TrainingLog:
- belongsTo MlModel

---

## 19. Rules Admin Feature

Admin menggunakan Blade.

Folder controller:
app/Http/Controllers/Admin

Folder views:
resources/views/admin

Folder reusable Blade component:
resources/views/components/admin

Fitur admin:

1. Dashboard
2. CRUD Jurusan
3. CRUD Kriteria
4. CRUD Pertanyaan
5. CRUD Opsi Jawaban
6. Manajemen Siswa
7. Training Model
8. Log Training
9. Laporan
10. Manajemen Admin

Rules:

1. Semua route admin wajib memakai middleware auth dan role admin/superadmin.
2. Superadmin boleh mengelola admin.
3. Admin biasa tidak boleh menambah superadmin.
4. Semua halaman admin wajib punya title halaman.
5. Semua table admin wajib punya fitur search jika datanya berpotensi banyak.
6. Gunakan pagination, jangan tampilkan semua data sekaligus.

---

## 20. Rules User Feature

User/Siswa menggunakan Inertia Vue.

Folder controller:
app/Http/Controllers/User

Folder page:
resources/js/Pages/User

Folder layout:
resources/js/Layouts/UserLayout.vue

Folder reusable component:
resources/js/Components

Fitur user:

1. Dashboard siswa
2. Lengkapi profil
3. Isi kuesioner
4. Input nilai akademik
5. Lihat hasil rekomendasi
6. Lihat riwayat rekomendasi
7. Download PDF rekomendasi

Rules:

1. Siswa yang belum melengkapi profil diarahkan ke halaman complete profile.
2. Siswa tidak boleh mengakses halaman admin.
3. Siswa hanya boleh melihat data miliknya sendiri.
4. Kuesioner harus dinamis dari database.
5. Nilai akademik harus dinamis dari table subjects.
6. Hasil rekomendasi harus menampilkan Top-3 jurusan.
7. Riwayat rekomendasi hanya menampilkan riwayat milik siswa yang login.

---

## 21. Rules Auth

Login menggunakan username, bukan email.

Form register siswa hanya berisi:

- Nama lengkap
- Username
- Password
- Konfirmasi password

Data yang disimpan saat register:

users:
- username
- password
- role = siswa

students:
- user_id
- nama

Email tidak wajib saat registrasi siswa.

Rules:

1. username wajib unique.
2. password wajib hashed.
3. confirm password tidak disimpan ke database.
4. role siswa otomatis saat register.
5. superadmin hanya dibuat melalui seeder.
6. admin hanya dibuat oleh superadmin.

---

## 22. Rules Naming

Gunakan nama yang konsisten.

Controller:
- MajorController
- QuestionController
- RecommendationController

Service:
- RegisterStudentService
- RecommendationService
- ModelTrainingService

Repository:
- StudentRepository
- RecommendationRepository

Request:
- StoreMajorRequest
- UpdateMajorRequest
- StoreAcademicScoreRequest

Vue Page:
- Index.vue
- Show.vue
- Edit.vue
- Create.vue
- History.vue

Vue Component:
- BaseButton.vue
- BaseCard.vue
- RecommendationCard.vue
- UserNavbar.vue

Blade View:
- index.blade.php
- create.blade.php
- edit.blade.php
- show.blade.php

---

## 23. Rules Import Vue

Gunakan import yang rapi.

Urutan import:

1. Vue/Inertia
2. Layout
3. Component
4. Composable
5. Utils/constants

Contoh:

import { Head, Link, useForm } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'
import BaseButton from '@/Components/base/BaseButton.vue'
import BaseCard from '@/Components/base/BaseCard.vue'
import RecommendationCard from '@/Components/cards/RecommendationCard.vue'
import { formatPercent } from '@/utils/formatPercent'

---

## 24. Rules Props Vue Component

Setiap reusable component harus menerima data melalui props.

Contoh:

RecommendationCard.vue

Props:
- majorName
- description
- score
- rank

Tidak boleh mengambil data langsung dari halaman lain.

Component reusable tidak boleh bergantung pada satu halaman tertentu.

---

## 25. Rules Emits Vue Component

Gunakan emit untuk event.

Contoh:

BaseModal.vue
Emits:
- close
- confirm

BaseButton.vue
Emits:
- click

ConfirmDialog.vue
Emits:
- confirm
- cancel

Jangan membuat component reusable yang langsung menjalankan route tertentu kecuali memang component khusus domain.

---

## 26. Rules Table Component

Buat BaseTable untuk struktur table umum.

resources/js/Components/base/BaseTable.vue

BaseTable digunakan untuk:

- Riwayat rekomendasi
- List nilai akademik
- List data sederhana user

Untuk table admin Blade, gunakan:

resources/views/components/admin/table.blade.php

Rules:

1. Table harus support empty state.
2. Table harus support loading state jika dibutuhkan.
3. Table tidak boleh hardcode data domain.
4. Table menerima columns dan rows.
5. Untuk action khusus, gunakan slot.

---

## 27. Rules Card Component

Gunakan BaseCard untuk container umum.

resources/js/Components/base/BaseCard.vue

Gunakan card domain untuk tampilan khusus:

resources/js/Components/cards/RecommendationCard.vue
resources/js/Components/cards/MajorCard.vue
resources/js/Components/cards/StatisticCard.vue
resources/js/Components/cards/ProfileCard.vue

Rules:

1. BaseCard hanya mengatur wrapper.
2. Card domain mengatur isi sesuai kebutuhan.
3. Jangan copy-paste class card di banyak halaman.

---

## 28. Rules Navigation

User navigation:

resources/js/Components/navigation/UserNavbar.vue
resources/js/Components/navigation/UserSidebar.vue
resources/js/Components/navigation/UserFooter.vue
resources/js/Components/navigation/MobileBottomNavigation.vue

Admin navigation:

resources/views/components/admin/navbar.blade.php
resources/views/components/admin/sidebar.blade.php
resources/views/components/admin/footer.blade.php

Rules:

1. Jangan menulis navbar langsung di setiap halaman.
2. Menu aktif harus berdasarkan route aktif.
3. Menu admin dan user harus dipisah.
4. User tidak boleh melihat menu admin.
5. Admin tidak boleh memakai layout user.

---

## 29. Rules Error Handling

Backend:

1. Gunakan Form Request untuk validasi.
2. Redirect back dengan error untuk Blade.
3. Kirim error Inertia untuk Vue.
4. Jangan menampilkan error teknis ke user.

Frontend Vue:

1. Tampilkan error input menggunakan FormError.
2. Tampilkan flash success/error menggunakan ToastMessage atau Alert.
3. Jangan menampilkan stack trace ke user.

---

## 30. Rules Flash Message

Gunakan satu pattern flash message.

Backend:

return redirect()
    ->back()
    ->with('success', 'Data berhasil disimpan.');

Frontend:

Gunakan:
resources/js/Composables/useFlash.js

Component:
resources/js/Components/feedback/ToastMessage.vue

Rules:

1. Jangan membuat alert success manual di banyak halaman.
2. Gunakan satu ToastMessage reusable.
3. Flash message harus konsisten.

---

## 31. Rules Pagination

Gunakan pagination untuk data banyak.

Admin:
- Gunakan Laravel pagination di Blade.

User:
- Gunakan pagination dari Inertia jika data riwayat rekomendasi banyak.

Reusable component:
resources/js/Components/base/BasePagination.vue

Rules:

1. Jangan menampilkan semua data sekaligus.
2. Minimal gunakan paginate(10) atau paginate(15).
3. Search dan filter harus mempertahankan query string.

---

## 32. Rules Search dan Filter

Admin wajib support search untuk:

- Data siswa
- Data jurusan
- Data pertanyaan
- Data training log
- Data hasil rekomendasi

Search field yang direkomendasikan:

Siswa:
- nama
- nisn
- kelas

Jurusan:
- nama_jurusan

Pertanyaan:
- pertanyaan
- criteria

Training log:
- model_name
- status

Rules:

1. Jangan gunakan select *.
2. Pilih kolom yang dibutuhkan.
3. Tambahkan index pada kolom yang sering difilter.
4. Hindari LIKE pada table besar jika data sudah sangat banyak.
5. Gunakan pagination.

---

## 33. Rules Machine Learning Integration

Laravel tidak menjalankan proses ML langsung di Controller.

Gunakan:

app/Services/MachineLearning/FastApiClient.php

Tugas FastApiClient:

1. Mengirim payload siswa ke FastAPI.
2. Menerima Top-3 rekomendasi.
3. Menghandle timeout.
4. Menghandle error response.

Tugas RecommendationService:

1. Membuat recommendation session.
2. Mengumpulkan jawaban kuesioner.
3. Mengumpulkan nilai akademik.
4. Memanggil FastApiClient.
5. Menyimpan hasil rekomendasi ke database.

Rules:

1. Jangan panggil FastAPI langsung dari Controller.
2. Jangan simpan response mentah tanpa validasi.
3. Simpan hasil ke recommendation_results.
4. Simpan status session.
5. Jika FastAPI error, session status menjadi failed.

---

## 34. Rules Seeder

Seeder wajib dibuat untuk data awal:

database/seeders/
├── SuperAdminSeeder.php
├── SubjectSeeder.php
├── CriteriaSeeder.php
├── QuestionSeeder.php
├── QuestionOptionSeeder.php
├── MajorSeeder.php
└── DatabaseSeeder.php

Data wajib:

1. Superadmin awal.
2. Mata pelajaran wajib:
   - Matematika
   - Bahasa Indonesia
   - Bahasa Inggris
   - IPA
   - IPS

3. Criteria awal:
   - Logika
   - Sosial
   - Bahasa
   - Kreativitas
   - Analitis

4. Opsi Likert:
   - Sangat Tidak Setuju = 1
   - Tidak Setuju = 2
   - Netral = 3
   - Setuju = 4
   - Sangat Setuju = 5

Rules:

1. Superadmin hanya dibuat dari seeder.
2. Jangan hardcode superadmin di controller.
3. Password seeder harus di-hash.
4. Seeder harus bisa dijalankan berulang tanpa duplikasi.

---

## 35. Rules File dan PDF

PDF rekomendasi dibuat melalui service:

app/Services/Report/RecommendationPdfService.php

Rules:

1. Controller hanya memanggil service.
2. Template PDF dipisah di folder:
   resources/views/pdf/recommendation-result.blade.php

3. Nama file PDF harus jelas:
   rekomendasi-jurusan-{nama-siswa}-{tanggal}.pdf

4. Jangan membuat HTML PDF langsung di Controller.

---

## 36. Rules Security

1. Semua halaman admin wajib auth.
2. Semua halaman user wajib auth.
3. Role siswa tidak boleh mengakses admin.
4. Role admin tidak boleh mengakses data siswa secara sembarangan tanpa kebutuhan fitur.
5. Password wajib di-hash.
6. Username wajib unique.
7. Validasi input wajib menggunakan Form Request.
8. Jangan percaya data dari frontend.
9. Siswa hanya boleh melihat recommendation session miliknya.
10. Admin action penting seperti delete harus menggunakan konfirmasi.

---

## 37. Rules Git dan Commit

Gunakan commit message yang jelas.

Contoh:

feat: add student registration flow
feat: add admin major management
feat: add reusable vue base components
fix: prevent duplicate username registration
refactor: move recommendation logic to service
style: improve user dashboard layout

Rules:

1. Jangan commit file .env.
2. Jangan commit vendor.
3. Jangan commit node_modules.
4. Jangan commit file model ML besar tanpa kebutuhan jelas.
5. Gunakan .gitignore dengan benar.

---

## 38. Checklist Sebelum Membuat Halaman Baru

Sebelum membuat halaman baru, cek:

1. Halaman ini untuk Admin atau User?
2. Apakah sudah ada layout yang sesuai?
3. Apakah ada component yang bisa digunakan ulang?
4. Apakah butuh Form Request?
5. Apakah butuh Service?
6. Apakah query-nya cukup sederhana atau butuh Repository?
7. Apakah butuh route baru?
8. Apakah butuh middleware role?
9. Apakah butuh pagination?
10. Apakah butuh search/filter?

---

## 39. Checklist Sebelum Membuat Component Vue Baru

Sebelum membuat component baru, cek:

1. Apakah component serupa sudah ada?
2. Apakah component ini akan dipakai lebih dari satu halaman?
3. Apakah component ini harus masuk folder base, cards, forms, navigation, atau feedback?
4. Apakah data bisa dikirim melalui props?
5. Apakah event bisa dikirim melalui emit?
6. Apakah styling-nya terlalu spesifik untuk satu halaman?
7. Apakah component ini bisa tetap reusable?

---

## 40. Larangan Utama

Dilarang:

1. Menaruh semua controller dalam satu folder tanpa pemisahan Admin/User.
2. Menulis semua logic di Controller.
3. Mengulang navbar di banyak halaman.
4. Mengulang footer di banyak halaman.
5. Mengulang card yang sama di banyak halaman.
6. Mengulang table yang sama di banyak halaman.
7. Menulis format tanggal manual berulang.
8. Menulis format persen manual berulang.
9. Membuat Vue component yang terlalu besar.
10. Mencampur Blade Admin dan Vue User dalam satu struktur halaman.
11. Menulis query kompleks langsung di Blade atau Vue.
12. Membiarkan siswa mengakses data siswa lain.
13. Membiarkan role siswa masuk halaman admin.
14. Menampilkan semua data tanpa pagination.

---

## 41. Target Akhir Struktur Project

Target akhir project harus terlihat seperti ini:

- Admin mudah dikembangkan dengan Blade.
- User/Siswa rapi menggunakan Inertia Vue.
- Komponen Vue reusable dan tidak duplikatif.
- Controller Laravel tipis.
- Logic bisnis berada di Service.
- Query kompleks berada di Repository.
- Layout dipakai ulang.
- Navbar, footer, card, table, modal, alert, input, dan button reusable.
- Struktur folder mudah dibaca dan mudah dipahami.