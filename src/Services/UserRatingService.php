<?php

namespace Nurdaulet\FluxAuth\Services;

use Nurdaulet\FluxAuth\Helpers\UserHelper;
use Nurdaulet\FluxAuth\Helpers\UserVerifyHelper;
use Nurdaulet\FluxAuth\Repositories\PermissionRepository;
use Nurdaulet\FluxAuth\Repositories\UserRatingRepository;
use Nurdaulet\FluxAuth\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nurdaulet\FluxItems\Facades\ItemsFacade;
use Nurdaulet\FluxOrders\Facades\OrdersFacade;
use Nurdaulet\FluxWallet\Facades\WalletFacade;

class UserRatingService
{

    public function __construct(private UserRatingRepository $userRatingRepository
    )
    {
    }

    public function create($id, $data)
    {
        $data['receiver_id'] = $id;
        $data['user_id'] = request()->user();
        $this->userRatingRepository->create($data);
    }

}
