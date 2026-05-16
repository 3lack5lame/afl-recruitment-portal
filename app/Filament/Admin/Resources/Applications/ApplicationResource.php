<?php

namespace App\Filament\Admin\Resources\Applications;

use App\Filament\Admin\Resources\Applications\Pages\CreateApplication;
use App\Filament\Admin\Resources\Applications\Pages\EditApplication;
use App\Filament\Admin\Resources\Applications\Pages\ListApplications;
use App\Models\Application;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?string $pluralLabel = 'Recruitment Applications';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->label('Applicant'),

                DatePicker::make('date_of_birth')
                    ->required()
                    ->label('Date of Birth'),

                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->required(),

                Select::make('county_of_origin')
                    ->options([
                        'Bomi' => 'Bomi',
                        'Bong' => 'Bong',
                        'Gbarpolu' => 'Gbarpolu',
                        'Grand Bassa' => 'Grand Bassa',
                        'Grand Cape Mount' => 'Grand Cape Mount',
                        'Grand Gedeh' => 'Grand Gedeh',
                        'Grand Kru' => 'Grand Kru',
                        'Lofa' => 'Lofa',
                        'Margibi' => 'Margibi',
                        'Maryland' => 'Maryland',
                        'Montserrado' => 'Montserrado',
                        'Nimba' => 'Nimba',
                        'Rivercess' => 'Rivercess',
                        'River Gee' => 'River Gee',
                        'Sinoe' => 'Sinoe',
                    ])
                    ->required()
                    ->label('County of Origin'),

                Select::make('county_of_residence')
                    ->options([
                        'Bomi' => 'Bomi',
                        'Bong' => 'Bong',
                        'Gbarpolu' => 'Gbarpolu',
                        'Grand Bassa' => 'Grand Bassa',
                        'Grand Cape Mount' => 'Grand Cape Mount',
                        'Grand Gedeh' => 'Grand Gedeh',
                        'Grand Kru' => 'Grand Kru',
                        'Lofa' => 'Lofa',
                        'Margibi' => 'Margibi',
                        'Maryland' => 'Maryland',
                        'Montserrado' => 'Montserrado',
                        'Nimba' => 'Nimba',
                        'Rivercess' => 'Rivercess',
                        'River Gee' => 'River Gee',
                        'Sinoe' => 'Sinoe',
                    ])
                    ->required()
                    ->label('County of Residence'),

                TextInput::make('phone_number')
                    ->required()
                    ->tel()
                    ->label('Phone Number'),

                Select::make('education_level')
                    ->options([
                        'WASSCE' => 'WASSCE',
                        'High School Diploma' => 'High School Diploma',
                        'Bachelor Degree' => 'Bachelor Degree',
                        'Master Degree' => 'Master Degree',
                        'Professional Certificate' => 'Professional Certificate',
                    ])
                    ->required()
                    ->label('Education Level'),

                Select::make('preferred_testing_center')
                    ->options([
                        'Monrovia (Montserrado)' => 'Monrovia (Montserrado)',
                        'Gbarnga (Bong)' => 'Gbarnga (Bong)',
                        'Kakata (Margibi)' => 'Kakata (Margibi)',
                        'Buchanan (Grand Bassa)' => 'Buchanan (Grand Bassa)',
                        'Zwedru (Grand Gedeh)' => 'Zwedru (Grand Gedeh)',
                        'Harper (Maryland)' => 'Harper (Maryland)',
                        'Voinjama (Lofa)' => 'Voinjama (Lofa)',
                        'Sanniquellie (Nimba)' => 'Sanniquellie (Nimba)',
                    ])
                    ->required()
                    ->label('Preferred Testing Center'),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'invited_for_test' => 'Invited for Test',
                        'test_passed' => 'Test Passed',
                        'test_failed' => 'Test Failed',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('submitted')
                    ->label('Application Status'),

                Textarea::make('notes')
                    ->rows(3)
                    ->label('Notes (for admin use)')
                    ->placeholder('Add any internal notes about this applicant...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Applicant Name'),

                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->label('DOB'),

                TextColumn::make('gender')
                    ->label('Gender'),

                TextColumn::make('phone_number')
                    ->label('Phone'),

                TextColumn::make('county_of_origin')
                    ->label('Origin County'),

                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'submitted',
                        'info' => 'under_review',
                        'primary' => 'invited_for_test',
                        'success' => ['accepted', 'test_passed'],
                        'danger' => ['rejected', 'test_failed'],
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Applied On'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'invited_for_test' => 'Invited for Test',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                Tables\Filters\SelectFilter::make('county_of_origin')
                    ->label('County'),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListApplications::route('/'),
            'create' => CreateApplication::route('/create'),
            'edit' => EditApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'submitted')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
