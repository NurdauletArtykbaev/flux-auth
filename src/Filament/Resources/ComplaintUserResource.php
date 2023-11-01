<?php

namespace Nurdaulet\FluxAuth\Filament\Resources;

use Nurdaulet\FluxAuth\Facades\StringFormatter;
use Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource\Pages;
use Nurdaulet\FluxAuth\Models\ComplaintUser;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class ComplaintUserResource extends Resource
{
    protected static ?string $model = ComplaintUser::class;
    protected static ?string $modelLabel = 'жалобу';
    protected static ?string $pluralModelLabel = 'Жалобы на пользователя';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 1)->count();
    }

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('complaint_reason_id')
                    ->relationship('compReason', 'name')
                    ->preload()
                    ->label(trans('admin.comp_reason')),

                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.user'))
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search) => config('flux-auth.models.user')::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
                        ->when(StringFormatter::onlyDigits($search), function ($query) use($search) {
                            $query->orWhere('phone', 'like', "%". StringFormatter::onlyDigits($search)."%");

                        })
                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
                        ->pluck('info', 'id'))
                    ->getOptionLabelUsing(function ($value) {
                        $user = config('flux-auth.models.user')::find($value);
                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
                    }),
                Forms\Components\Select::make('who_complaint_user_id')
                    ->label(trans('admin.complaint_user'))
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search) => config('flux-auth.models.user')::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
                        ->when(StringFormatter::onlyDigits($search), function ($query) use($search) {
                            $query->orWhere('phone', 'like', "%". StringFormatter::onlyDigits($search)."%");

                        })
                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
                        ->pluck('info', 'id'))
                    ->getOptionLabelUsing(function ($value) {
                        $user = config('flux-auth.models.user')::find($value);
                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
                    }),
                Forms\Components\Textarea::make('comment')
                    ->maxLength(65535)->label(trans('admin.comment')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('compReason.name')
                    ->label(trans('admin.comp_reason')),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label(trans('admin.user')),
                Tables\Columns\TextColumn::make('whoComplaintUser.name')
                    ->label(trans('admin.complaint_user')),
                Tables\Columns\TextColumn::make('comment')
                    ->label(trans('admin.comment')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(trans('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(trans('admin.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Отработан')
                    ->action(function (ComplaintUser $record): void {
                        $record->status = false;
                        $record->save();
                    })
            ])
            ->defaultSort('id','desc')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListComplaintUsers::route('/'),
            'create' => Pages\CreateComplaintUser::route('/create'),
            'edit' => Pages\EditComplaintUser::route('/{record}/edit'),
        ];
    }
}
