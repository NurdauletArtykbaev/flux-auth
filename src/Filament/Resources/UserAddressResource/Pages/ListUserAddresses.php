<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserAddresses extends ListRecords
{
    protected static string $resource = UserAddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
