<?php
return [
    'param' => env('EXAMPLE_PARAM', 100),
    'identify' => [
        'enabled' => env('IDENTIFY_ENABLED',false),
        'url' => env('IDENTIFY_URL'),
        'login' => env('IDENTIFY_LOGIN'),
        'password' => env('IDENTIFY_PASSWORD'),
    ],
    'models' => [
        'user_address' => \Nurdaulet\FluxAuth\Models\UserAddress::class,
        'user_organization' => \Nurdaulet\FluxAuth\Models\UserOrganization::class,
        'user_rating' => \Nurdaulet\FluxAuth\Models\UserRating::class,
        'store' => \Nurdaulet\FluxAuth\Models\Store::class,
        'complaint_user' => \Nurdaulet\FluxAuth\Models\ComplaintUser::class,
        'complaint_reason' => \Nurdaulet\FluxAuth\Models\ComplaintReason::class,
        'user' => \Nurdaulet\FluxAuth\Models\User::class,
        'permission' => \Nurdaulet\FluxAuth\Models\Permission::class,
        'role' => \Nurdaulet\FluxAuth\Models\Role::class,
        'city' => \Nurdaulet\FluxAuth\Models\City::class,
    ],
    'options' => [
        'organization_default_status' => null,
        'storage_disk' => 's3',
        'is_enabled_balance' => false,
        'is_multiple_organizations' => false,
        'organization_check_hour' => 12,
        'filament_email_access_end' => ''
    ],
    'permission' => [
        'models' => [
            'permission' => Spatie\Permission\Models\Permission::class,

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * Eloquent model should be used to retrieve your roles. Of course, it
             * is often just the "Role" model but you may use whatever you like.
             *
             * The model you want to use as a Role model needs to implement the
             * `Spatie\Permission\Contracts\Role` contract.
             */

            'role' => Spatie\Permission\Models\Role::class,

        ],

        'table_names' => [

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'roles' => 'roles',

            /*
             * When using the "HasPermissions" trait from this package, we need to know which
             * table should be used to retrieve your permissions. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'permissions' => 'permissions',

            /*
             * When using the "HasPermissions" trait from this package, we need to know which
             * table should be used to retrieve your models permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_permissions' => 'model_has_permissions',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your models roles. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_roles' => 'model_has_roles',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'role_has_permissions' => 'role_has_permissions',
        ],

        'column_names' => [
            /*
             * Change this if you want to name the related pivots other than defaults
             */
            'role_pivot_key' => null, //default 'role_id',
            'permission_pivot_key' => null, //default 'permission_id',

            /*
             * Change this if you want to name the related model primary key other than
             * `model_id`.
             *
             * For example, this would be nice if your primary keys are all UUIDs. In
             * that case, name this `model_uuid`.
             */

            'model_morph_key' => 'model_id',

            /*
             * Change this if you want to use the teams feature and your related model's
             * foreign key is other than `team_id`.
             */

            'team_foreign_key' => 'team_id',
        ],
        'teams' => false,
        'cache' => [

            /*
             * By default all permissions are cached for 24 hours to speed up performance.
             * When permissions or roles are updated the cache is flushed automatically.
             */

            'expiration_time' => \DateInterval::createFromDateString('24 hours'),

            /*
             * The cache key used to store all permissions.
             */

            'key' => 'spatie.permission.cache',

            /*
             * You may optionally indicate a specific cache driver to use for permission and
             * role caching using any of the `store` drivers listed in the cache.php config
             * file. Using 'default' here means to use the `default` set in cache.php.
             */

            'store' => 'default',
        ],

    ],
];
