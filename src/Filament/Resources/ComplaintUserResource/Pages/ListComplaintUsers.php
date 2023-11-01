<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplaintUsers extends ListRecords
{
    protected static string $resource = ComplaintUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
