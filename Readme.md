Пакет flux-auth - Авторизация.

Установите пакет с помощью Composer:

``` bash
 composer require Nurdaulet/flux-auth
```

## Конфигурация

После установки пакета, вам нужно опубликовать конфигурационный файл. Вы можете сделать это с помощью следующей команды:

``` bash
php artisan vendor:publish --provider="Nurdaulet\FluxAuth\FluxAuthServiceProvider"

php artisan vendor:publish --provider="Nurdaulet\FluxWallet\FluxWalletServiceProvider"
php artisan vendor:publish --tag flux-wallet-config
```

Замените конфигурационный файл config/auth.php

``` php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Nurdaulet\FluxAuth\Models\User::class,
        ],
    ] 
```

Вы можете самостоятельно добавить поставщика услуг административной панели Filament в файл config/app.php.

``` php
'providers' => [
    // ...
    Nurdaulet\FluxAuth\FluxAuthFilamentServiceProvider::class,
];
```

По умолчанию все разделы будут добавлены, вы также можете самостоятельно добавить разделы в админ-панели Filament в
файле AppServiceProvider.php.

```
Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
    return $builder
        ->groups([
            NavigationGroup::make('Главная')
                ->items([
                //...
                    ...UserResource::getNavigationItems(),
                    ...RoleResource::getNavigationItems(),
        ]),
    ]);
});
```

Список всех ресурсов
``` php
[
       UserResource::class,
       UserRoleResource::class,
       UserAddressResource::class,
       RoleResource::class,
       PermissionResource::class,
       ComplaintUserResource::class
]
```