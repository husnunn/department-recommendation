@extends('admin.layouts.app')
@section('title', 'Tambah pertanyaan')
@section('page-title', 'Tambah pertanyaan')

@section('content')
    @php
        $defaultOptions = [
            ['opsi' => 'Sangat Tidak Setuju', 'score' => 1, 'sort_order' => 0],
            ['opsi' => 'Tidak Setuju', 'score' => 2, 'sort_order' => 1],
            ['opsi' => 'Netral', 'score' => 3, 'sort_order' => 2],
            ['opsi' => 'Setuju', 'score' => 4, 'sort_order' => 3],
            ['opsi' => 'Sangat Setuju', 'score' => 5, 'sort_order' => 4],
        ];
        $optRows = old('options', $defaultOptions);
    @endphp

    <x-admin.page-header
        title="Tambah pertanyaan"
        description="Pilih kriteria, isi teks pertanyaan, dan sesuaikan opsi Likert bila perlu."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Pertanyaan', 'url' => route('admin.pertanyaan.index')],
            ['label' => 'Tambah', 'url' => null],
        ]"
    />

    <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.pertanyaan.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="criteria_id">Kriteria</label>
                    <select id="criteria_id" name="criteria_id" required class="w-full max-w-md rounded-lg border border-outline-variant px-3 py-2">
                        <option value="">— Pilih —</option>
                        @foreach ($criteriaList as $cr)
                            <option value="{{ $cr->id }}" @selected(old('criteria_id') == $cr->id)>{{ $cr->nama_kriteria }}</option>
                        @endforeach
                    </select>
                    @error('criteria_id')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="pertanyaan">Pertanyaan</label>
                    <textarea id="pertanyaan" name="pertanyaan" rows="3" required class="w-full rounded-lg border px-3 py-2">{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="sort_order">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}" class="w-full rounded-lg border px-3 py-2" />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="hidden" name="is_active" value="0" />
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4" @checked(old('is_active', true)) />
                    <label for="is_active" class="text-sm">Aktif</label>
                </div>
            </div>

            <div>
                <h2 class="mb-3 text-sm font-semibold text-on-surface">Opsi jawaban</h2>
                <div class="space-y-3">
                    @foreach ($optRows as $i => $row)
                        <div class="flex flex-wrap items-end gap-2 rounded-lg border border-outline-variant/40 p-3 md:flex-nowrap">
                            <div class="min-w-0 flex-1">
                                <label class="text-xs text-on-surface-variant">Teks opsi</label>
                                <input type="text" name="options[{{ $i }}][opsi]" value="{{ $row['opsi'] ?? '' }}" required class="mt-1 w-full rounded border px-2 py-1.5 text-sm" />
                            </div>
                            <div class="w-24">
                                <label class="text-xs text-on-surface-variant">Skor</label>
                                <input type="number" name="options[{{ $i }}][score]" value="{{ $row['score'] ?? 1 }}" min="1" max="5" required class="mt-1 w-full rounded border px-2 py-1.5 text-sm" />
                            </div>
                            <div class="w-24">
                                <label class="text-xs text-on-surface-variant">Urut</label>
                                <input type="number" name="options[{{ $i }}][sort_order]" value="{{ $row['sort_order'] ?? $i }}" min="0" class="mt-1 w-full rounded border px-2 py-1.5 text-sm" />
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('options')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary">Simpan</button>
                <a href="{{ route('admin.pertanyaan.index') }}" class="rounded-lg border px-5 py-2.5 text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection
