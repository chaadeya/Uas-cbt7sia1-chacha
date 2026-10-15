<?php

namespace App\Filament\Test\Resources\ExamHistories;

use App\Filament\Resources\ExamResults\Schemas\ExamResultInfolist;
use App\Filament\Test\Resources\ExamHistories\Pages\ListExamHistories;
use App\Filament\Test\Resources\ExamHistories\Pages\ViewExamHistory;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamHistoryResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $modelLabel = 'Riwayat Ujian';

    protected static ?string $pluralModelLabel = 'Riwayat Ujian Siswa';

    protected static ?string $navigationLabel = 'Riwayat Ujian Saya';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return ExamResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $studentId = auth()->user()?->student?->id;
                $query->where('student_id', $studentId);
            })
            ->columns([
                TextColumn::make('#')
                    ->rowIndex()
                    ->width(40),
                TextColumn::make('exam.title')
                    ->label('Sesi Ujian')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Skor Total')
                    ->badge()
                    ->color(fn ($record) => $record->is_passed ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1))
                    ->sortable(),
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
                    ->label('Detail Hasil & Pembahasan'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamHistories::route('/'),
            'view' => ViewExamHistory::route('/{record}'),
        ];
    }
}
