<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Microservice Machine Learning (FastAPI)
    |--------------------------------------------------------------------------
    |
    | Konfigurasi HTTP ke layanan prediksi. Jika base_url kosong atau
    | stub diaktifkan, Laravel memakai prediksi stub (pengembangan lokal)
    | selama masih ada model aktif di tabel ml_models.
    |
    */

    'base_url' => env('ML_SERVICE_URL', ''),

    'predict_path' => env('ML_SERVICE_PREDICT_PATH', '/predict'),

    'train_path' => env('ML_SERVICE_TRAIN_PATH', '/train'),

    'token' => env('ML_SERVICE_TOKEN', ''),

    'timeout_seconds' => (int) env('ML_SERVICE_TIMEOUT', 15),

    /**
     * Timeout khusus request training (biasanya lebih lama dari prediksi).
     */
    'train_timeout_seconds' => (int) env('ML_SERVICE_TRAIN_TIMEOUT', 120),

    /**
     * Saat true, tidak memanggil jaringan dan memakai hasil stub (Top-3 jurusan aktif).
     */
    'stub' => env('ML_SERVICE_STUB', false),
];
