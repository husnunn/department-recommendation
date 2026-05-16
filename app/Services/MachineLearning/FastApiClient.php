<?php

namespace App\Services\MachineLearning;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FastApiClient
{
    /**
     * Mengirim payload prediksi ke FastAPI dan mem-parse response JSON.
     *
     * @param  array<string, mixed>  $body
     */
    public function predict(array $body): FastApiPredictionResponse
    {
        $base = rtrim((string) config('ml.base_url'), '/');
        $path = (string) config('ml.predict_path', '/predict');
        if ($base === '') {
            return FastApiPredictionResponse::fail('URL layanan ML tidak dikonfigurasi.');
        }

        $url = $base.$path;
        $timeout = (int) config('ml.timeout_seconds', 15);
        $token = (string) config('ml.token', '');

        try {
            $pending = Http::timeout($timeout)
                ->acceptJson()
                ->asJson();

            if ($token !== '') {
                $pending = $pending->withToken($token);
            }

            $response = $pending->post($url, $body);
            $response->throw();

            $json = $response->json();
            if (! is_array($json)) {
                return FastApiPredictionResponse::fail('Format response ML tidak valid.');
            }

            return FastApiPredictionResponse::fromHttpJson($json);
        } catch (RequestException $e) {
            Log::warning('FastAPI prediksi gagal (HTTP)', [
                'message' => $e->getMessage(),
            ]);

            return FastApiPredictionResponse::fail('Layanan rekomendasi tidak tersedia sementara. Coba lagi nanti.');
        } catch (Throwable $e) {
            Log::error('FastAPI prediksi gagal', [
                'exception' => $e,
            ]);

            return FastApiPredictionResponse::fail('Terjadi kesalahan saat menghubungi layanan ML.');
        }
    }

    /**
     * Memicu training di FastAPI (dataset + label).
     *
     * @param  array<string, mixed>  $body
     */
    public function train(array $body): FastApiTrainingResponse
    {
        $base = rtrim((string) config('ml.base_url'), '/');
        $path = (string) config('ml.train_path', '/train');
        if ($base === '') {
            return FastApiTrainingResponse::fail('URL layanan ML tidak dikonfigurasi.');
        }

        $url = $base.$path;
        $timeout = (int) config('ml.train_timeout_seconds', 120);
        $token = (string) config('ml.token', '');

        try {
            $pending = Http::timeout($timeout)
                ->acceptJson()
                ->asJson();

            if ($token !== '') {
                $pending = $pending->withToken($token);
            }

            $response = $pending->post($url, $body);
            $response->throw();

            $json = $response->json();
            if (! is_array($json)) {
                return FastApiTrainingResponse::fail('Format response training ML tidak valid.');
            }

            return FastApiTrainingResponse::fromHttpJson($json);
        } catch (RequestException $e) {
            Log::warning('FastAPI training gagal (HTTP)', [
                'message' => $e->getMessage(),
            ]);

            return FastApiTrainingResponse::fail('Layanan training tidak tersedia sementara.');
        } catch (Throwable $e) {
            Log::error('FastAPI training gagal', [
                'exception' => $e,
            ]);

            return FastApiTrainingResponse::fail('Terjadi kesalahan saat menghubungi layanan training ML.');
        }
    }
}
