<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;

use Nurdaulet\FluxAuth\Filament\Resources\RoleResource\Pages;
use Nurdaulet\FluxAuth\Filament\Resources\RoleResource\RelationManagers\RentProductPricesRelationManager;
use Nurdaulet\FluxAuth\Models\Role;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $modelLabel = 'Роли';
    protected static ?string $pluralModelLabel = 'Роли';
    protected static ?string $navigationIcon = 'heroicon-o-users';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('admin.name'))
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label(trans('admin.description'))
                    ->required(),
                Forms\Components\Textarea::make('info')
                    ->label(trans('admin.info'))
                ->columnSpanFull(),
                Forms\Components\CheckboxList::make('permissions')
                    ->label(trans('admin.permissions'))
                    ->relationship(
                       'permissions','description')
                ->columns(2)
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
                    ->label(trans('admin.name')),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
