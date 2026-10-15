<x-filament-panels::page>
    <style>
        .cbt-grid-subjects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .cbt-subject-item {
            text-align: left;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: rgba(255, 255, 255, 0.02);
            cursor: pointer;
            width: 100%;
            transition: border-color 0.15s ease;
        }
        .cbt-subject-item:hover {
            border-color: rgba(148, 163, 184, 0.45);
        }
        .cbt-subject-item.is-selected {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }
        .cbt-grid-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .cbt-metric-box {
            padding: 0.85rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(148, 163, 184, 0.15);
            background: rgba(255, 255, 255, 0.02);
        }
        .cbt-metric-label {
            font-size: 0.75rem;
            opacity: 0.7;
            display: block;
            margin-bottom: 0.25rem;
        }
        .cbt-metric-value {
            font-size: 1.35rem;
            font-weight: 700;
        }
        .cbt-history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        .cbt-history-table th {
            padding: 0.65rem 0.85rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(156, 163, 175, 1);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .cbt-history-table td {
            padding: 0.75rem 0.85rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        {{-- Daftar Pelajaran --}}
        <x-filament::section>
            <x-slot name="heading">
                Mata Pelajaran
            </x-slot>

            <div class="cbt-grid-subjects">
                @foreach ($subjects as $mapel)
                    @php
                        $isSelected = $selectedSubjectId == $mapel->id;
                    @endphp
                    <button 
                        type="button"
                        wire:click="selectSubject({{ $mapel->id }})"
                        class="cbt-subject-item {{ $isSelected ? 'is-selected' : '' }}"
                    >
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <span style="font-weight: 600; font-size: 0.9rem;">
                                {{ $mapel->name }}
                            </span>
                            @if ($isSelected)
                                <span style="font-size: 0.7rem; color: #818cf8; font-weight: 600;">Terpilih</span>
                            @endif
                        </div>
                        <p style="font-size: 0.75rem; opacity: 0.65; margin-bottom: 0.6rem; line-height: 1.3;">
                            {{ $mapel->description ?: '-' }}
                        </p>
                        <div style="font-size: 0.72rem; opacity: 0.6;">
                            {{ $mapel->questions_count }} Soal &bull; {{ $mapel->exams_count }} Sesi Ujian
                        </div>
                    </button>
                @endforeach
            </div>
        </x-filament::section>

        {{-- Detail Hasil Nilai --}}
        @if ($selectedSubject)
            <x-filament::section>
                <x-slot name="heading">
                    Nilai: {{ $selectedSubject->name }}
                </x-slot>

                {{-- Metrik Nilai --}}
                <div class="cbt-grid-metrics">
                    <div class="cbt-metric-box">
                        <span class="cbt-metric-label">Jumlah Ujian</span>
                        <div class="cbt-metric-value">{{ $stats['totalExamsTaken'] }}</div>
                    </div>

                    <div class="cbt-metric-box">
                        <span class="cbt-metric-label">Nilai Rata-rata</span>
                        <div class="cbt-metric-value">{{ $stats['averageScore'] }}</div>
                    </div>

                    <div class="cbt-metric-box">
                        <span class="cbt-metric-label">Nilai Tertinggi</span>
                        <div class="cbt-metric-value">{{ $stats['highestScore'] }}</div>
                    </div>

                    <div class="cbt-metric-box">
                        <span class="cbt-metric-label">Ketuntasan</span>
                        <div class="cbt-metric-value">{{ $stats['passRate'] }}%</div>
                    </div>
                </div>

                {{-- Tabel Riwayat --}}
                <div style="overflow-x: auto; border: 1px solid rgba(148, 163, 184, 0.15); border-radius: 0.5rem;">
                    <table class="cbt-history-table">
                        <thead>
                            <tr>
                                <th>Ujian</th>
                                <th>Tanggal</th>
                                <th style="text-align: center;">Benar / Salah / Kosong</th>
                                <th style="text-align: center;">Nilai</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                                <tr>
                                    <td style="font-weight: 500;">
                                        {{ $result->exam?->title ?? '-' }}
                                    </td>
                                    <td style="opacity: 0.75;">
                                        {{ $result->exam_date ? $result->exam_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td style="text-align: center; opacity: 0.85;">
                                        {{ $result->correct_answers }} / {{ $result->wrong_answers }} / {{ $result->unanswered }}
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        {{ number_format((float)$result->score, 1) }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($result->is_passed)
                                            <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 0.25rem; background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 600;">Lulus</span>
                                        @else
                                            <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 0.25rem; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-weight: 600;">Remedial</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <x-filament::button
                                            tag="a"
                                            size="xs"
                                            color="gray"
                                            href="{{ route('filament.test.resources.exam-histories.view', ['record' => $result->id]) }}"
                                        >
                                            Lihat Detail
                                        </x-filament::button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 1.5rem; opacity: 0.5;">
                                        Belum ada riwayat ujian pada mata pelajaran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
