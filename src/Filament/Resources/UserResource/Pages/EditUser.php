<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserResource\Pages;

use Nurdaulet\FluxAuth\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;



    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['logo'] && $data['logo'] != $this->record->logo) {
            $url = env('AWS_URL') . '/' . $data['logo'];
            $file = file_get_contents($url);
            $imageWebp = Image::make($file)->encode('webp', 80);
            $webpName = 'logo/'. $this->record->id.'/'.  time() . Str::uuid().'.webp';

            $data['logo_webp']  = $webpName;
            Storage::disk('s3')->put($webpName, $imageWebp);
            if ($this->record->logo_webp) {
                Storage::disk('s3')->delete($this->record->logo_webp);
            }
        }
        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
