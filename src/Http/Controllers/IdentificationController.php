<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Http\Requests\IdentificationRequest;
use Nurdaulet\FluxAuth\Http\Requests\IdentifyNumberRequest;
use Nurdaulet\FluxAuth\Http\Requests\IdentifyNumberSaveRequest;
use Nurdaulet\FluxAuth\Services\IdentificationService;

class IdentificationController 
{

    public function __construct(private IdentificationService $identificationService)
    {
    }


    public function identifyUser(IdentificationRequest $request)
    {

        $identifyNumber = $this->identificationService->identify($request->user(), $request->file('face'),
            $request->file('identification'), $request->file('identification_back'));
        return response()->json([
            'data' => [
                'identify_number' => $identifyNumber
            ]
        ]);
    }


    public function getIdentifyNumber(IdentifyNumberRequest $request)
    {
        return response()->json([
            'data' => [
                'identify_number' => $this->identificationService->getIdentifyNumberByUser(
                    $request->user(),
                    $request->file('document')
                )
            ]
        ]);
    }

    public function saveIdentifyNumber(IdentifyNumberSaveRequest $request)
    {
        $user = auth()->user();
        $user->iin = $request->identify_number;
        $user->is_identified = true;
        $user->save();
        return response()->noContent();
    }
}
