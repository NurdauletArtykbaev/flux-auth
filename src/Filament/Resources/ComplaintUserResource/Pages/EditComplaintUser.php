<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplaintUser extends EditRecord
{
    protected static string $resource = ComplaintUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
