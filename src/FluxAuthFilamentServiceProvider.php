<?php

namespace Nurdaulet\FluxAuth;


use Filament\PluginServiceProvider;
use Nurdaulet\FluxAuth\Filament\Resources\ComplaintUserResource;
use Nurdaulet\FluxAuth\Filament\Resources\PermissionResource;
use Nurdaulet\FluxAuth\Filament\Resources\RoleResource;
use Nurdaulet\FluxAuth\Filament\Resources\UserAddressResource;
use Nurdaulet\FluxAuth\Filament\Resources\UserResource;
use Nurdaulet\FluxAuth\Filament\Resources\UserRoleResource;
use Spatie\LaravelPackageTools\Package;

class FluxAuthFilamentServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        UserResource::class,
        UserRoleResource::class,
        UserAddressResource::class,
        RoleResource::class,
        PermissionResource::class,
        ComplaintUserResource::class
    ];

    public function configurePackage(Package $package): void
    {
        $this->packageConfiguring($package);
        $package->name('auth-package');
    }
}
