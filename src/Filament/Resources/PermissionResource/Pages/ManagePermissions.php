<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\PermissionResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Pages\Actions;

class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                return $data;
            }),
        ];
    }
}
