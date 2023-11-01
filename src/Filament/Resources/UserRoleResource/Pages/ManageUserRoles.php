<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserRoleResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\UserRoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageUserRoles extends ManageRecords
{
    protected static string $resource = UserRoleResource::class;
//    public function getTableRecordKey(Model $record): string
//    {
//        return uniqid();
//    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                $data['model_type'] = config('flux-auth.models.user')::class;

                return $data;
            }),
        ];
    }
}
