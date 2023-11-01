<?php
return [
    'param' => env('EXAMPLE_PARAM', 100),
    'identify' => [
        'enabled' => false,
        'url' => env('IDENTIFY_URL'),
        'login' => env('IDENTIFY_LOGIN'),
        'password' => env('IDENTIFY_PASSWORD'),
    ],
    'models' => [
        'user_address' => \Nurdaulet\FluxAuth\Models\UserAddress::class,
        'user_rating' => \Nurdaulet\FluxAuth\Models\UserRating::class,
        'complaint_user' => \Nurdaulet\FluxAuth\Models\ComplaintUser::class,
        'complaint_reason' => \Nurdaulet\FluxAuth\Models\ComplaintReason::class,
        'user' => \Nurdaulet\FluxAuth\Models\User::class,
        'permission' => \Nurdaulet\FluxAuth\Models\Permission::class,
        'role' => \Nurdaulet\FluxAuth\Models\Role::class,
        'city' => \Nurdaulet\FluxAuth\Models\City::class,
    ],
    'options' => [
        'storage_disk' => 's3'
    ]
];
