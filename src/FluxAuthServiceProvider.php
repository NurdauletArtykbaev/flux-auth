<?php
namespace Nurdaulet\FluxAuth;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Nurdaulet\FluxAuth\Helpers\StringFormatterHelper;

class FluxAuthServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/flux-auth.php' => config_path('flux-auth.php'),
            ], 'flux-auth-config');
            $this->publishMigrations();
        }
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->app->bind('stringFormatter', StringFormatterHelper::class);

    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function publishMigrations()
    {

        $this->publishes([
            __DIR__ . '/../database/migrations/check_cities_table.php.stub' => $this->getMigrationFileName(0,'check_flux_auth_cities_table.php'),
            __DIR__ . '/../database/migrations/check_complaint_reasons_table.php.stub' => $this->getMigrationFileName(1,'check_flux_auth_complaint_reasons_table.php'),
            __DIR__ . '/../database/migrations/check_type_organizations.php.stub' => $this->getMigrationFileName(2,'check_flux_auth_type_organizations.php'),
            __DIR__ . '/../database/migrations/check_users_table.php.stub' => $this->getMigrationFileName(3,'check_flux_auth_users_table.php'),
            __DIR__ . '/../database/migrations/check_complaint_users_table.php.stub' => $this->getMigrationFileName(4,'check_flux_auth_complaint_users_table.php'),
            __DIR__ . '/../database/migrations/check_user_addresses.php.stub' => $this->getMigrationFileName(5,'check_flux_auth_user_addresses.php'),
            __DIR__ . '/../database/migrations/check_user_ratings_table.php.stub' => $this->getMigrationFileName(6,'check_flux_auth_user_ratings_table.php'),
            __DIR__ . '/../database/migrations/check_stores_table.php.stub' => $this->getMigrationFileName(7,'check_flux_auth_stores_table.php'),
            __DIR__ . '/../database/migrations/check_permissions_table.php.stub' => $this->getMigrationFileName(8,'check_flux_auth_permissions_table.php'),
            __DIR__ . '/../database/migrations/check_user_organizations.php.stub' => $this->getMigrationFileName(9,'check_flux_auth_user_organizations_table.php'),
        ], 'flux-auth-migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getMigrationFileName($index,string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His','1620669600');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR])
            ->flatMap(fn($path) => $filesystem->glob($path . '*_' . $migrationFileName))
            ->push($this->app->databasePath() . "/migrations/{$timestamp}{$index}_{$migrationFileName}")
            ->first();
    }

}
