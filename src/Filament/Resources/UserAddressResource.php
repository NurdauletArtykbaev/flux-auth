<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;

use Nurdaulet\FluxAuth\Facades\StringFormatter;
use Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource\Pages;
use Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource\RelationManagers;
use Nurdaulet\FluxAuth\Models\UserAddress;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserAddressResource extends Resource
{
    protected static ?string $model = UserAddress::class;
    protected static ?string $modelLabel = 'адрес';
    protected static ?string $pluralModelLabel = 'Адреса пользователей';

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.user'))
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search) => User::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
                        ->when(StringFormatter::onlyDigits($search), function ($query) use($search) {
                            $query->orWhere('phone', 'like', "%". StringFormatter::onlyDigits($search)."%");

                        })
                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
                        ->pluck('info', 'id'))
                    ->getOptionLabelUsing(function ($value) {
                        $user = config('flux-auth.models.user')::find($value);
                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
                    }),
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->label(trans('admin.city')),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)->label(trans('admin.name')),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)->label(trans('admin.address')),
                Forms\Components\TextInput::make('house')
                    ->maxLength(255)->label(trans('admin.house')),
                Forms\Components\TextInput::make('floor')
                    ->maxLength(255)->label(trans('admin.floor')),
                Forms\Components\TextInput::make('apartment')
                    ->maxLength(255)->label(trans('admin.apartment')),
                Forms\Components\TextInput::make('lat')
                    ->maxLength(255)->label(trans('admin.lat')),
                Forms\Components\TextInput::make('lng')
                    ->maxLength(255)->label(trans('admin.lng')),
                Forms\Components\Toggle::make('is_main')
                    ->required()->label(trans('admin.is_main')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label(trans('admin.user')),
                Tables\Columns\TextColumn::make('city.name')->label(trans('admin.city')),
                Tables\Columns\TextColumn::make('name')->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('address')->label(trans('admin.address')),
                Tables\Columns\TextColumn::make('house')->label(trans('admin.house')),
                Tables\Columns\TextColumn::make('floor')->label(trans('admin.floor')),
                Tables\Columns\TextColumn::make('apartment')->label(trans('admin.apartment')),
                Tables\Columns\TextColumn::make('lat')->label(trans('admin.lat')),
                Tables\Columns\TextColumn::make('lng')->label(trans('admin.lng')),
                Tables\Columns\BooleanColumn::make('is_main')->label(trans('admin.is_main')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(trans('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(trans('admin.updated_at')),
            ])
            ->filters([
                SelectFilter::make('city_id')
                    ->label(trans('admin.city'))
                    ->options(config('flux-auth.models.city')::orderBy('name')->get()->pluck('name', 'id')->toArray()),
                SelectFilter::make('user_id')
                    ->label(trans('admin.user'))
                    ->options(config('flux-auth.models.user')::orderBy('name')
                        ->whereNotNull('name')->get()
                        ->pluck('full_name_with_phone', 'id')
                        ->toArray()
                    ),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->has('user');
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
            'index' => Pages\ListUserAddresses::route('/'),
            'create' => Pages\CreateUserAddress::route('/create'),
            'edit' => Pages\EditUserAddress::route('/{record}/edit'),
        ];
    }
}
