<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';
    protected static ?string $modelLabel = 'адрес';
    protected static ?string $pluralModelLabel = 'Адреса пользователей';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label(trans('admin.name')),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->label(trans('admin.address')),
                Forms\Components\TextInput::make('house')
                    ->maxLength(255)
                    ->label(trans('admin.house')),
                Forms\Components\TextInput::make('floor')
                    ->maxLength(255)
                    ->label(trans('admin.floor')),
                Forms\Components\TextInput::make('apartment')
                    ->maxLength(255)
                    ->label(trans('admin.apartment')),
                Forms\Components\TextInput::make('lat')
                    ->maxLength(255)
                    ->label(trans('admin.lat')),
                Forms\Components\TextInput::make('lng')
                    ->maxLength(255)
                    ->label(trans('admin.lng')),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label(trans('admin.is_active')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('address')->label(trans('admin.address')),
                Tables\Columns\TextColumn::make('house')->label(trans('admin.house')),
                Tables\Columns\TextColumn::make('floor')->label(trans('admin.floor')),
                Tables\Columns\TextColumn::make('apartment')->label(trans('admin.apartment')),
                Tables\Columns\TextColumn::make('lat')->label(trans('admin.lat')),
                Tables\Columns\TextColumn::make('lng')->label(trans('admin.lng')),
                Tables\Columns\BooleanColumn::make('is_active')->label(trans('admin.is_active')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(trans('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(trans('admin.updated_at')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
