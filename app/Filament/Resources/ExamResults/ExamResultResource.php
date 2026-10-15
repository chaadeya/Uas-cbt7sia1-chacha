<?php

namespace App\Filament\Resources\ExamResults;

use App\Filament\Resources\ExamResults\Pages\ListExamResults;
use App\Filament\Resources\ExamResults\Pages\ViewExamResult;
use App\Filament\Resources\ExamResults\Schemas\ExamResultInfolist;
use App\Filament\Resources\ExamResults\Tables\ExamResultsTable;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Ujian';

    protected static ?string $modelLabel = 'Rekap Ujian';

    protected static ?string $pluralModelLabel = 'Rekap Hasil Ujian Siswa';

    protected static ?string $navigationLabel = 'Rekap Ujian Siswa';

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return ExamResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamResultsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamResults::route('/'),
            'view' => ViewExamResult::route('/{record}'),
        ];
    }
}
