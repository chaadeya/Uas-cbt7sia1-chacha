<x-filament-panels::page>
    <style>
        .cbt-exam-header {
            position: sticky;
            top: 4rem;
            z-index: 20;
            padding: 0.85rem 1.25rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .cbt-exam-q-box {
            padding: 1.25rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: rgba(255, 255, 255, 0.02);
            margin-bottom: 1rem;
        }
        .cbt-exam-q-box.answered {
            border-color: rgba(99, 102, 241, 0.35);
        }
        .cbt-option-label {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.65rem 0.85rem;
            border-radius: 0.35rem;
            border: 1px solid rgba(148, 163, 184, 0.15);
            background: rgba(255, 255, 255, 0.01);
            cursor: pointer;
            margin-bottom: 0.4rem;
            font-size: 0.82rem;
            transition: border-color 0.15s ease;
        }
        .cbt-option-label:hover {
            border-color: rgba(148, 163, 184, 0.35);
        }
        .cbt-option-label.selected {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.08);
        }
    </style>

    <div>
        {{-- Sticky Header Bar --}}
        <div class="cbt-exam-header">
            <div>
                <h2 style="font-size: 1rem; font-weight: 700; margin: 0;">
                    {{ $exam->title }}
                </h2>
                <div style="display: flex; gap: 0.75rem; font-size: 0.72rem; opacity: 0.7; margin-top: 0.25rem; flex-wrap: wrap;">
                    <span>Durasi: {{ $exam->duration }} Menit</span>
                    <span>&bull;</span>
                    <span>KKM: {{ number_format((float)$exam->threshold, 0) }}</span>
                    <span>&bull;</span>
                    <span>Terjawab: <strong style="color: #818cf8;">{{ count($userAnswers) }}</strong> / {{ $totalQuestions }}</span>
                </div>
            </div>

            <div>
                <x-filament::button
                    type="button"
                    color="primary"
                    size="sm"
                    wire:click="submitExam"
                    wire:confirm="Apakah Anda yakin ingin mengumpulkan ujian ini?"
                >
                    Selesaikan Ujian
                </x-filament::button>
            </div>
        </div>

        {{-- Soal Collections by Subject --}}
        @php
            $globalNumber = 1;
        @endphp

        @foreach ($collections as $pelajaran)
            <div style="margin-bottom: 1.5rem;">
                <div style="padding: 0.6rem 0.85rem; border-radius: 0.35rem; background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.25); margin-bottom: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.82rem; font-weight: 600; color: #818cf8;">{{ $pelajaran['name'] }}</span>
                    <span style="font-size: 0.72rem; opacity: 0.7;">{{ count($pelajaran['soals']) }} Soal</span>
                </div>

                <div>
                    @foreach ($pelajaran['soals'] as $pertanyaan)
                        @php
                            $questionId = $pertanyaan['id'];
                            $isAnswered = isset($userAnswers[$questionId]);
                        @endphp
                        <div class="cbt-exam-q-box {{ $isAnswered ? 'answered' : '' }}">
                            {{-- Header Nomor Soal --}}
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; margin-bottom: 0.75rem; border-bottom: 1px solid rgba(148, 163, 184, 0.15);">
                                <span style="font-weight: 600; font-size: 0.8rem; opacity: 0.75;">
                                    Soal {{ $globalNumber }}
                                </span>

                                <div>
                                    @if ($isAnswered)
                                        <span style="font-size: 0.68rem; padding: 0.1rem 0.45rem; border-radius: 0.2rem; background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 600;">
                                            Sudah Dijawab
                                        </span>
                                    @else
                                        <span style="font-size: 0.68rem; padding: 0.1rem 0.45rem; border-radius: 0.2rem; background: rgba(148, 163, 184, 0.15); opacity: 0.6;">
                                            Belum Dijawab
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Isi Soal --}}
                            <div style="font-size: 0.88rem; line-height: 1.5; margin-bottom: 1rem;">
                                {!! $pertanyaan['payload'] !!}
                            </div>

                            {{-- Pilihan Jawaban --}}
                            <div>
                                @php
                                    $optionLetters = ['A', 'B', 'C', 'D', 'E'];
                                @endphp
                                @foreach ($pertanyaan['answers'] as $optIndex => $jawaban)
                                    @php
                                        $answerId = $jawaban['id'];
                                        $isSelected = isset($userAnswers[$questionId]) && $userAnswers[$questionId] == $answerId;
                                    @endphp
                                    <label class="cbt-option-label {{ $isSelected ? 'selected' : '' }}">
                                        <input 
                                            type="radio" 
                                            name="jawaban_{{ $questionId }}" 
                                            wire:model.live="userAnswers.{{ $questionId }}" 
                                            value="{{ $answerId }}"
                                            style="margin-top: 0.2rem;"
                                        />
                                        <div style="display: flex; gap: 0.4rem; flex: 1;">
                                            <span style="opacity: 0.6; min-width: 1.1rem; font-weight: 600;">
                                                {{ $optionLetters[$optIndex] ?? ($optIndex + 1) }}.
                                            </span>
                                            <span>
                                                {{ $jawaban['text'] }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @php
                            $globalNumber++;
                        @endphp
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Footer Submit Bar --}}
        <div style="text-align: center; padding: 1.5rem; border: 1px solid rgba(148, 163, 184, 0.15); border-radius: 0.5rem; background: rgba(255, 255, 255, 0.02); margin-top: 1rem;">
            <p style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 0.85rem;">
                Pastikan seluruh jawaban sudah diperiksa sebelum mengumpulkan ujian.
            </p>
            <x-filament::button
                type="button"
                color="primary"
                size="md"
                wire:click="submitExam"
                wire:confirm="Apakah Anda yakin ingin mengumpulkan ujian ini?"
            >
                Kumpulkan Jawaban
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
