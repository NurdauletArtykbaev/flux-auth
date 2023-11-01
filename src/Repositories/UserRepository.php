<?php

namespace Nurdaulet\FluxAuth\Repositories;

use Illuminate\Support\Facades\Storage;

class UserRepository
{

    public function find($id, $relations = [], $withCounts = [])
    {
        return config('flux-auth.models.user')::withTrashed()
            ->when(count($relations), fn($query) => $query->with($relations))
            ->when(count($withCounts), fn($query) => $query->withCount($withCounts))
            ->findOrFail($id);
    }


    public function uploadVerify($user, $file)
    {
        $path = $this->uploadImageToCloud('verify_image', $user->id, $file);
        $user->verify_image = $path;
        $user->save();

        return $user->verifyImageUrl;
    }

    public function uploadImageToCloud($attribute, $id, $file): ?string
    {
        if (empty($file)) {
            return null;
        }
        $filename = md5($file->getContent() . time()) . '.jpg';
        $path = "$attribute/$id/$filename";

        Storage::disk(config('flux-auth.options.storage_disk'))->put($path, $file->getContent(), 'public');
        return $path;
    }

    public function deleteImageFromCloud(  $file)
    {
        Storage::disk(config('flux-auth.options.storage_disk'))->delete($file);
    }

    public function update($user, array $data)
    {
        if (array_key_exists('is_enabled_notification',$data)) {
            $data['is_enabled_notification'] = $data['is_enabled_notification'] == 'true';
        }
        $user->fill($data);
        $user->saveOrFail();

        return $user;
    }
}
