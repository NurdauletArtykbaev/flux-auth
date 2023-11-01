<?php

namespace Nurdaulet\FluxAuth\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Nurdaulet\FluxAuth\Helpers\UserHelper;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $usersCount = config('flux-auth.models.user')::query()->count();
        $verifiedUsers = config('flux-auth.models.user')::where('is_verified', 3)->count();
        $notVerifiedUsers = config('flux-auth.models.user')::where('is_verified', 1)->count();
        $onProcessVerificationUsers = config('flux-auth.models.user')::where('is_verified', UserHelper::ON_PROCESS)->count();

        return [
            Card::make('Всего пользователей', $usersCount),
            Card::make('Верифицированные пользователи', $verifiedUsers),
            Card::make('В процессе верификации', $onProcessVerificationUsers),
            Card::make('Не верифицированные', $notVerifiedUsers),
        ];
    }
}
