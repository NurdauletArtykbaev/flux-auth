
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

```


``` php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Nurdaulet\FluxAuth\Models\User::class,
        ],
    ] 
```



