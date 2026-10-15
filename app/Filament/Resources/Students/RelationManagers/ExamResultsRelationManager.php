<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\ExamResults\ExamResultResource;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class ExamResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'examResults';

    protected static ?string $title = 'Rekap & Riwayat Nilai Ujian Siswa';

    #[Override]
    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex()
                    ->width(40),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('exam.title')
                    ->label('Sesi Ujian')
                    ->searchable(),
                TextColumn::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Skor')
                    ->badge()
                    ->color(fn ($record) => $record->is_passed ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1)),
                TextColumn::make('correct_answers')
                    ->label('Benar')
                    ->badge()
                    ->color('success'),
                TextColumn::make('wrong_answers')
                    ->label('Salah')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('unanswered')
                    ->label('Kosong')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('is_passed')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'LULUS' : 'REMEDIAL'),
            ])
            ->defaultSort('exam_date', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => ExamResultResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
