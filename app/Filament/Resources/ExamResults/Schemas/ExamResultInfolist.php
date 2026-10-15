<?php

namespace App\Filament\Resources\ExamResults\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekap Ujian')
                    ->description('Rangkuman data siswa, mata pelajaran, waktu pelaksanaan, dan nilai akhir ujian')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('student.name')
                                    ->label('Nama Siswa'),
                                TextEntry::make('student.nis')
                                    ->label('NIS'),
                                TextEntry::make('subject.name')
                                    ->label('Mata Pelajaran')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('-'),
                                TextEntry::make('exam.title')
                                    ->label('Sesi Ujian'),
                                TextEntry::make('exam_date')
                                    ->label('Tanggal Ujian')
                                    ->dateTime('d F Y, H:i'),
                                TextEntry::make('duration_spent_minutes')
                                    ->label('Durasi Pengerjaan')
                                    ->formatStateUsing(fn ($state) => $state . ' Menit'),
                                TextEntry::make('score')
                                    ->label('Skor Total')
                                    ->badge()
                                    ->color(fn ($record) => $record->is_passed ? 'success' : 'danger')
                                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1)),
                                TextEntry::make('is_passed')
                                    ->label('Status Kelulusan')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'success' : 'danger')
                                    ->formatStateUsing(fn ($state) => $state ? 'LULUS' : 'REMEDIAL'),
                                TextEntry::make('exam.threshold')
                                    ->label('Nilai Standar Minimum (KKM)')
                                    ->placeholder('50.00'),
                            ]),
                    ]),

                Section::make('Detail Histori Ujian (Rincian Jawaban Siswa & Kunci Jawaban)')
                    ->description('Daftar seluruh soal yang diujikan beserta jawaban yang dipilih siswa, kunci jawaban resmi, dan status penilaian.')
                    ->schema([
                        ViewEntry::make('details')
                            ->view('filament.resources.exam-results.details')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
