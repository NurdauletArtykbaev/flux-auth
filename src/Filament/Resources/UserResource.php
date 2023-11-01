<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;


use Nurdaulet\FluxAuth\Filament\Resources\UserResource\Pages;
use Nurdaulet\FluxAuth\Filament\Resources\UserResource\RelationManagers;
use Nurdaulet\FluxAuth\Helpers\UserHelper;
use Nurdaulet\FluxAuth\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rules\Unique;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'пользователя';
    protected static ?string $pluralModelLabel = 'Пользователи';


    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
//        $hiddenColumns = $form->get();

        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->disk('s3')
                    ->directory('users')
                    ->label(trans('admin.avatar')),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->disk('s3')
                    ->directory('logo')
                    ->label(trans('admin.logo')),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(trans('admin.user_name')),
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->label(trans('admin.surname')),
                Forms\Components\TextInput::make('last_name')
                    ->label(trans('admin.last_name')),
                Forms\Components\DatePicker::make('born_date')
                    ->label(trans('admin.born_date')),
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->preload()
                    ->label(trans('admin.city')),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignorable: fn ($record) => $record, callback:  function (Unique $rule) {
                        return $rule->whereNull('deleted_at');
                    })
                    ->label(trans('admin.email')),
                Forms\Components\TextInput::make('password')
                    ->hint('[Forgotten your password?](forgotten-password)'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record, callback:  function (Unique $rule) {
                        return $rule->whereNull('deleted_at');
                    })
                    ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->pattern('00000000000'))
                    ->label(trans('admin.phone')),
                Forms\Components\TextInput::make('code')
                    ->minLength(4)
                    ->maxLength(4)
                    ->label(trans('admin.code')),

                Forms\Components\TextInput::make('company_name')
                    ->label(trans('admin.company_name')),

                Forms\Components\Select::make('type_organization_id')
                    ->relationship('typeOrganization', 'name')
                    ->preload()
                    ->label(trans('admin.type_organization_id')),
                Forms\Components\TextInput::make('bin_iin')
                    ->label(trans('admin.bin_iin')),
                Forms\Components\TextInput::make('bik')
                    ->label(trans('admin.bik')),
                Forms\Components\TextInput::make('iik')
                    ->label(trans('admin.iik')),

                Forms\Components\TextInput::make('iin')
                    ->label(trans('admin.iin')),
                Forms\Components\FileUpload::make('identify_front')
                    ->image()
                    ->disk('s3')
                    ->directory('users')
                    ->label(trans('admin.identification_image')),
                Forms\Components\FileUpload::make('identify_back')
                    ->image()
                    ->disk('s3')
                    ->directory('users')
                    ->label(trans('admin.identify_back')),
                Forms\Components\FileUpload::make('identify_face')
                    ->image()
                    ->disk('s3')
                    ->directory('users')
                    ->label(trans('admin.identify_face')),
                Forms\Components\FileUpload::make('verify_image')
                    ->image()
                    ->disk('s3')
                    ->directory('verify_image' )
                    ->label(trans('admin.verify_image')),

                Forms\Components\FileUpload::make('contract')
                    ->disk('s3')
                    ->label(trans('admin.contract'))
                    ->directory(UserHelper::CONTRACT_DIR),
                Forms\Components\Select::make('is_verified')
                    ->options(User::getVerifiedOptions())
                    ->preload()
                    ->default(User::NOT_VERIFIED )
                    ->label(trans('admin.is_verified')),
                Forms\Components\Toggle::make('is_identified')
                    ->default(0 )
                    ->label(trans('admin.is_identified')),
                Forms\Components\Toggle::make('is_banned')
                    ->default(0 )
                    ->label(trans('admin.is_banned')),
                Forms\Components\Tabs::make('Heading')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Время доставки')
                            ->schema(
                                [
                                    TagsInput::make('delivery_times')
                                        ->label(trans('admin.delivery_time'))
                                        ->separator(',')
                                        ->default((new User())->delivery_times)

                                ]
                            ),
                        Forms\Components\Tabs\Tab::make('Время работы')
                            ->schema(
                                static::getFormSchema()
                            ),


                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->width(150)
                    ->height(150)
                    ->disk('s3')
                    ->label(trans('admin.avatar')),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Имя'),
                Tables\Columns\TextColumn::make('surname')
                    ->sortable()
                    ->searchable()
                    ->label(trans('admin.surname')),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->url(fn(User $record): string => url("https://naprocat.kz/user/$record->id/" ) )
                    ->openUrlInNewTab()
                    ->label(trans('admin.phone')),

                Tables\Columns\TextColumn::make('is_verified')->enum(User::getVerifiedOptions())
                    ->label(trans('admin.is_verified')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(trans('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(trans('admin.updated_at')),
            ])
            ->filters([
                SelectFilter::make('is_verified')
                    ->label(trans('admin.is_verified'))
                    ->options(User::getVerifiedOptions()
                    ),
                Filter::make('is_banned')->label(trans('admin.is_banned'))
                    ->query(fn(Builder $query): Builder => $query->where('is_banned', true)),
                SelectFilter::make('city_id')
                    ->label(trans('admin.city'))
                    ->options(config('flux-auth.models.city')::orderBy('name')->whereNotNull('name')->get()->pluck('name', 'id')->toArray()),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('с даты'),
                        Forms\Components\DatePicker::make('created_until')->label('до даты'),
                    ])->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(isset($data['created_from']), function ($query) use ($data) {
                                return $query->whereDate('created_at', '>=', $data['created_from']);
                            })
                            ->when(isset($data['created_until']), function ($query) use ($data) {
                                return $query->whereDate('created_at', '<=', $data['created_until']);
                            });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Архивировать_товары')
                    ->action(function (User $record) {
                        $record->ads()->update(['status' => 0]);
                        Artisan::call('optimize:clear');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'DESC');
    }
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Repeater::make('Время работы')
                ->statePath('graphic_works')
                ->default([
                    ['day' => 1],
                    ['day' => 2],
                    ['day' => 3],
                    ['day' => 4],
                    ['day' => 5],
                    [
                        'day' => 6,
                        'is_closed' => true,
                    ],
                    [
                        'day' => 0,
                        'is_closed' => true,
                    ],
                ])
                ->schema([
                    Forms\Components\Select::make('day')
                        ->options([
                            1 => 'Пн',
                            2 => 'Вт',
                            3 => 'Ср',
                            4 => 'Чт',
                            5 => 'Пт',
                            6 => 'Сб',
                            0 => 'Вс',
                        ])
                        ->label(trans('admin.stores.day'))
                        ->default(1)
                        ->disabled()
                        ->disablePlaceholderSelection(),

                    Forms\Components\Toggle::make('is_closed')
                        ->label(trans('admin.stores.is_closed'))
                        ->columnSpan([
                            'md' => 1,
                        ]),
                    Forms\Components\TextInput::make('start_hour')
                        ->label(trans('admin.stores.start_hour'))
                        ->default('9:00')
                        ->columnSpan([
                            'md' => 1,
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('end_hour')
                        ->label(trans('admin.stores.end_hour'))
                        ->default('18:00')
                        ->required()
                        ->columnSpan([
                            'md' => 1,
                        ]),
                ])
                ->disableItemDeletion()
                ->disableItemCreation()
                ->columns(2)

        ];

    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressesRelationManager::class,
//            RelationManagers\SupportsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
