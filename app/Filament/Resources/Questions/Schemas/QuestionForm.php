<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('subject_id')
                            ->label('Pelajaran')
                            ->relationship('subject', 'name')
                            ->native(false)
                            ->preload()
                            ->required(),
                        TextInput::make('correct_answer')
                            ->label('Bobot nilai')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->default(null),
                        TextInput::make('score')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                        RichEditor::make('payload')
                            ->label('Pertanyaan')
                            ->fileAttachmentsDisk('Public')
                            ->fileAttachmentsDirectory('question-images')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Keterangan Soal')
                            ->default(null)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif?')
                            ->inline(false)
                            ->default(true)
                            ->required(),
                            ]),

                    Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('answer')
                        ->relationship()
                        ->label('Pilih Jawaban')
                        ->addActionLabel('Tambah pilihan Baru')
                        ->reorderable()
                        ->columns(2)
                        ->minItems(2)
                        ->schema([
                            textInput::make('text')
                                ->label('Deskripsi Pilihan')
                                ->required()
                                ->columnSpanFull(),
                            Toggle::make('is_active')
                                ->label('Tersedia')
                                ->default(true),
                            Toggle::make('is_correct')
                                ->label('Pilihan jawaban')
                        ]),
                    ]),
            ]);

    }
}
