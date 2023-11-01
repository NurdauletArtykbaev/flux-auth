<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAddress extends EditRecord
{
    protected static string $resource = UserAddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
