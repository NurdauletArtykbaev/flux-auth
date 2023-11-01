<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;

use Nurdaulet\FluxAuth\Filament\Resources\PermissionResource\Pages;
use Nurdaulet\FluxAuth\Models\Permission;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $modelLabel = 'Привелигии';
    protected static ?string $pluralModelLabel = 'Привелигии';
    protected static ?string $navigationIcon = 'heroicon-o-users';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('admin.key'))
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label(trans('admin.description'))
                    ->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->alignCenter()
                    ->label(trans('admin.id')),
                Tables\Columns\TextColumn::make('name')
                    ->alignCenter()
                    ->label(trans('admin.key')),
                Tables\Columns\TextColumn::make('description')
                    ->alignCenter()
                    ->label(trans('admin.description')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePermissions::route('/'),
        ];
    }
}
