<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('application_id')
                    ->relationship('application', 'id')
                    ->required()
                    ->searchable()
                    ->label('Application ID'),

                Select::make('type')
                    ->options([
                        'passport_photo' => 'Passport Photo',
                        'national_id' => 'National ID',
                        'waec_certificate' => 'WAEC Certificate',
                    ])
                    ->required(),

                FileUpload::make('file_path')
                    ->required()
                    ->directory('documents')
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->label('Document File'),

                Toggle::make('verified')
                    ->label('Verified')
                    ->default(false),
            ]);
    }
}
