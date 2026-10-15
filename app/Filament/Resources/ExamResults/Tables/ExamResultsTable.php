<?php

namespace App\Filament\Resources\ExamResults\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExamResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex()
                    ->width(40),
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->student?->nis ? 'NIS: ' . $record->student->nis : null),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
                TextColumn::make('exam.title')
                    ->label('Sesi Ujian')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Skor Total')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->is_passed ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1)),
                TextColumn::make('correct_answers')
                    ->label('Benar')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('wrong_answers')
                    ->label('Salah')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
                TextColumn::make('unanswered')
                    ->label('Kosong')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('total_questions')
                    ->label('Total Soal')
                    ->sortable(),
                TextColumn::make('is_passed')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'LULUS' : 'REMEDIAL'),
            ])
            ->defaultSort('exam_date', 'desc')
            ->filters([
                SelectFilter::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name'),
                SelectFilter::make('exam_id')
                    ->label('Sesi Ujian')
                    ->relationship('exam', 'title'),
                TernaryFilter::make('is_passed')
                    ->label('Kelulusan')
                    ->trueLabel('Lulus')
                    ->falseLabel('Remedial'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Detail Histori'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
