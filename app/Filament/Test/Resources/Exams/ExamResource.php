<?php

namespace App\Filament\Test\Resources\Exams;

use App\Filament\Test\Resources\Exams\Pages\ManageExams;
use App\Filament\Test\Resources\Exams\Pages\StartingExam;
use App\Models\Exam;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Sesi Ujian';

    protected static ?string $modelLabel = 'Ujian';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $now = now();
                $query
                    ->where('is_available', true)
                    ->where(function (Builder $query) use ($now) {
                        $query
                            // exact_time = true: ujian yang sudah mulai (started_at <= now)
                            // dan belum habis durasinya (started_at + duration >= now)
                            ->where(fn(Builder $q) => $q
                                ->where('exact_time', true)
                                ->where('started_at', '<=', $now)
                                ->whereRaw(
                                    'DATE_ADD(started_at, INTERVAL duration MINUTE) >= ?',
                                    [$now]
                                ))
                            // exact_time = false dengan expired_at: belum melewati expired_at
                            ->orWhere(fn(Builder $q) => $q
                                ->where('exact_time', false)
                                ->whereNotNull('expired_at')
                                ->where('expired_at', '>=', $now))
                            // exact_time = false tanpa expired_at: selalu tampil
                            ->orWhere(fn(Builder $q) => $q
                                ->where('exact_time', false)
                                ->whereNull('expired_at'));
                    });
            })
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->label('Ujian'),
                TextColumn::make('duration')
                    ->numeric()
                    ->label('durasi')
                    ->sortable(),
                TextColumn::make('threshold')
                    ->numeric()
                    ->label('Min. Score')
                    ->sortable(),
                TextColumn::make('started_at')
                    ->dateTime('d F Y, H:i:s')
                    ->label('Mulai')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('Start')
                    ->label('Mulai')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('primary')
                    ->button()
                    ->disabled(
                        fn($record) => $record->started_at >= now()
                    )
                    ->url(fn($record) => route(StartingExam::getRouteName(), ['exam' => $record],))
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExams::route('/'),
            'mulai' => StartingExam::route('/{exam}/mulai')
        ];
    }
}
