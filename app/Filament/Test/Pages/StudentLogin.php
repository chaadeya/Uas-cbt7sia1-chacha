<?php

namespace App\Filament\Test\Pages;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Override;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class StudentLogin extends Login
{
    public function form(Schema $schema) : Schema{
        return  $schema
        ->components([
            TextInput::make('username')
                ->label('NIS')
                ->placeholder('Nomor Induk Siswa')
                ->required(),
            $this->getPasswordFormComponent()
        ]);
    }

    #[Override]
    public function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password']
        ];
    }

    #[Override]
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => 'Siswa dengam data tersebut tidak ditemukan'
        ]);
    }
}
