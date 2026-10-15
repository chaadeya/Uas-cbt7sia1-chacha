@php
    $record = $getRecord();
    $details = $record->details()->with(['question.answers', 'selectedAnswer', 'correctAnswer'])->get();
@endphp

<style>
    .cbt-details-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .cbt-details-kpi {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(148, 163, 184, 0.15);
        background: rgba(255, 255, 255, 0.02);
    }
    .cbt-q-card {
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: rgba(255, 255, 255, 0.02);
        margin-bottom: 0.85rem;
    }
    .cbt-q-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.6rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
    }
    .cbt-compare-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
        font-size: 0.8rem;
        margin-top: 0.85rem;
    }
    .cbt-box {
        padding: 0.65rem 0.85rem;
        border-radius: 0.35rem;
        border: 1px solid rgba(148, 163, 184, 0.15);
        background: rgba(255, 255, 255, 0.02);
    }
</style>

<div>
    {{-- Summary Stats Bar --}}
    <div class="cbt-details-summary">
        <div class="cbt-details-kpi">
            <span style="font-size: 0.72rem; opacity: 0.7; display: block; margin-bottom: 0.2rem;">Total Soal</span>
            <span style="font-size: 1.35rem; font-weight: 700;">{{ $record->total_questions }}</span>
        </div>
        <div class="cbt-details-kpi">
            <span style="font-size: 0.72rem; color: #10b981; display: block; margin-bottom: 0.2rem;">Jawaban Benar</span>
            <span style="font-size: 1.35rem; font-weight: 700; color: #10b981;">{{ $record->correct_answers }}</span>
        </div>
        <div class="cbt-details-kpi">
            <span style="font-size: 0.72rem; color: #ef4444; display: block; margin-bottom: 0.2rem;">Jawaban Salah</span>
            <span style="font-size: 1.35rem; font-weight: 700; color: #ef4444;">{{ $record->wrong_answers }}</span>
        </div>
        <div class="cbt-details-kpi">
            <span style="font-size: 0.72rem; opacity: 0.7; display: block; margin-bottom: 0.2rem;">Tidak Dijawab</span>
            <span style="font-size: 1.35rem; font-weight: 700;">{{ $record->unanswered }}</span>
        </div>
    </div>

    {{-- Question List with Answer Details --}}
    <div>
        @forelse ($details as $index => $detail)
            @php
                $isCorrect = $detail->is_correct;
                $isUnanswered = is_null($detail->selected_answer_id);
            @endphp
            <div class="cbt-q-card">
                {{-- Header Question --}}
                <div class="cbt-q-header">
                    <span style="font-weight: 600; font-size: 0.8rem; opacity: 0.8;">
                        Nomor {{ $index + 1 }}
                    </span>

                    <div>
                        @if ($isCorrect)
                            <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 0.25rem; background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 600;">
                                Benar ({{ number_format((float)$detail->score, 1) }} poin)
                            </span>
                        @elseif ($isUnanswered)
                            <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 0.25rem; background: rgba(148, 163, 184, 0.15); opacity: 0.7; font-weight: 600;">
                                Tidak Dijawab (0 poin)
                            </span>
                        @else
                            <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 0.25rem; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-weight: 600;">
                                Salah (0 poin)
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Question Payload --}}
                <div style="font-size: 0.88rem; line-height: 1.5; margin-bottom: 0.6rem;">
                    {!! $detail->question?->payload ?? '<em>Pertanyaan telah dihapus</em>' !!}
                </div>

                {{-- Answers Comparison --}}
                <div class="cbt-compare-grid">
                    {{-- Jawaban Siswa --}}
                    <div class="cbt-box">
                        <span style="display: block; font-size: 0.7rem; opacity: 0.6; margin-bottom: 0.2rem;">Jawaban Siswa:</span>
                        <div style="font-weight: 500; color: {{ $isCorrect ? '#10b981' : ($isUnanswered ? 'inherit' : '#ef4444') }};">
                            @if ($isUnanswered)
                                <span style="opacity: 0.5;">-</span>
                            @else
                                {{ $detail->student_answer_text ?? $detail->selectedAnswer?->text ?? '-' }}
                            @endif
                        </div>
                    </div>

                    {{-- Kunci Jawaban --}}
                    <div class="cbt-box">
                        <span style="display: block; font-size: 0.7rem; color: #10b981; margin-bottom: 0.2rem;">Kunci Jawaban:</span>
                        <div style="font-weight: 500; color: #10b981;">
                            {{ $detail->correct_answer_text ?? $detail->correctAnswer?->text ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- Pembahasan jika ada --}}
                @if ($detail->question?->description)
                    <div style="margin-top: 0.75rem; padding: 0.6rem 0.85rem; border-radius: 0.35rem; border: 1px solid rgba(148, 163, 184, 0.15); font-size: 0.78rem;">
                        <span style="font-weight: 600; opacity: 0.7; display: block; margin-bottom: 0.15rem;">Pembahasan:</span>
                        <span style="opacity: 0.85;">{{ $detail->question->description }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 1.5rem; opacity: 0.5; font-size: 0.82rem;">
                Tidak ada rincian soal untuk rekapan ini.
            </div>
        @endforelse
    </div>
</div>
