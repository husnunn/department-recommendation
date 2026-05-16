@extends('admin.layouts.app')
@section('title', 'Edit pertanyaan')
@section('page-title', 'Edit pertanyaan')

@section('content')
    @php
        $optRows = old('options', $question->options->map(fn ($o) => ['opsi' => $o->opsi, 'score' => $o->score, 'sort_order' => $o->sort_order])->all());
    @endphp

    <x-admin.page-header
        title="Edit pertanyaan"
        description="{{ $canEditOptions ? 'Anda dapat mengubah opsi jawaban karena belum ada jawaban siswa.' : 'Opsi jawaban terkunci karena sudah ada jawaban siswa.' }}"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Pertanyaan', 'url' => route('admin.pertanyaan.index')],
            ['label' => 'Edit', 'url' => null],
        ]"
    />

    <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.pertanyaan.update', $question) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="criteria_id">Kriteria</label>
                    <select id="criteria_id" name="criteria_id" required class="w-full max-w-md rounded-lg border px-3 py-2">
                        @foreach ($criteriaList as $cr)
                            <option value="{{ $cr->id }}" @selected(old('criteria_id', $question->criteria_id) == $cr->id)>{{ $cr->nama_kriteria }}</option>
                        @endforeach
                    </select>
                    @error('criteria_id')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="pertanyaan">Pertanyaan</label>
                    <textarea id="pertanyaan" name="pertanyaan" rows="3" required class="w-full rounded-lg border px-3 py-2">{{ old('pertanyaan', $question->pertanyaan) }}</textarea>
                    @error('pertanyaan')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="sort_order">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $question->sort_order) }}" class="w-full rounded-lg border px-3 py-2" />
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="hidden" name="is_active" value="0" />
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4" @checked(old('is_active', $question->is_active)) />
                    <label for="is_active" class="text-sm">Aktif</label>
                </div>
            </div>

            @if ($canEditOptions)
                <div>
                    <h2 class="mb-3 text-sm font-semibold">Opsi jawaban</h2>
                    <div class="space-y-3">
                        @foreach ($optRows as $i => $row)
                            <div class="flex flex-wrap gap-2 rounded-lg border p-3">
                                <input type="text" name="options[{{ $i }}][opsi]" value="{{ $row['opsi'] ?? '' }}" required class="min-w-0 flex-1 rounded border px-2 py-1.5 text-sm" />
                                <input type="number" name="options[{{ $i }}][score]" value="{{ $row['score'] ?? 1 }}" min="1" max="5" required class="w-24 rounded border px-2 py-1.5 text-sm" />
                                <input type="number" name="options[{{ $i }}][sort_order]" value="{{ $row['sort_order'] ?? $i }}" min="0" class="w-24 rounded border px-2 py-1.5 text-sm" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="rounded-lg bg-surface-container-low p-4 text-sm text-on-surface-variant">Opsi tidak dapat diubah dari form ini karena sudah ada jawaban siswa.</p>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary">Perbarui</button>
                <a href="{{ route('admin.pertanyaan.index') }}" class="rounded-lg border px-5 py-2.5 text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection
