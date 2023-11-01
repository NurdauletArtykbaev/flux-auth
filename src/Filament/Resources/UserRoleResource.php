<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;

use Nurdaulet\FluxAuth\Facades\StringFormatter;
use Nurdaulet\FluxAuth\Filament\Resources\UserRoleResource\Pages;
use Nurdaulet\FluxAuth\Models\UserRole;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class UserRoleResource extends Resource
{
    protected static ?string $model = UserRole::class;
    protected static ?string $modelLabel = 'Роль пользователя';
    protected static ?string $pluralModelLabel = 'Роли пользователей';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('model_id')
                    ->label(trans('admin.user'))
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search) => config('flux-auth.models.user')::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
                        ->when(StringFormatter::onlyDigits($search), function ($query) use ($search) {
                            $query->orWhere('phone', 'like', "%" . StringFormatter::onlyDigits($search) . "%");

                        })
                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
                        ->pluck('info', 'id'))
                    ->getOptionLabelUsing(function ($value) {
                        $user = config('flux-auth.models.user')::find($value);
                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
                    }),
                Forms\Components\Select::make('role_id')
                    ->label(trans('admin.role'))
                    ->relationship('role', 'description')
                    ->preload()
                    ->searchable()
            ]);
    }
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereNull('deleted_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.phone')
                    ->alignCenter()
                    ->label(trans('admin.user')),
                Tables\Columns\TextColumn::make('role.description')
                    ->alignCenter()
                    ->label(trans('admin.role')),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label(trans('admin.role'))
                    ->options(config('flux-auth.models.role')::orderBy('description')->get()->pluck('description', 'id')->toArray()),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserRoles::route('/'),
        ];
    }
}
