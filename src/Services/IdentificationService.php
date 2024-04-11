<?php

namespace Nurdaulet\FluxAuth\Services;


use Nurdaulet\FluxAuth\Events\User\IdentifySuccessEvent;
use Nurdaulet\FluxAuth\Repositories\UserRepository;
use Illuminate\Support\Facades\Http;

class IdentificationService
{
    protected mixed $url;
    protected mixed $token;
    const IDENTITY_NUMBER_PDF = 'parseIdCardFromPdf';
    const IDENTITY_NUMBER_PHOTO = 'parseIdCard';
    const AUTH_ENDPOINT = 'token';
    const REKOGNITION_PHOTO_ENDPOINT = 'compareFaces';
    const REKOGNITION_PDF_ENDPOINT = 'compareFacesFromPdf';

    public function __construct(private UserRepository $userRepository)
    {
        $this->url = config('flux-auth.identify.url');
        $this->token = $this->authorizeInID()['access_token'];
    }

    public function identify($user, $face, $identification, $identifyBack = null)
    {
        $identifyNumber = $this->getIdentifyNumber($identification);
        $isSuccess = $this->identifyUser($user, $face, $identification, $identifyBack);

        if (!$isSuccess) {
            throw new \ErrorException('Не похоже.Снимитесь повторно.', 400);
        } else {
            event(new IdentifySuccessEvent($user));
        }

        $user->iin = $identifyNumber;
        $user->save();
        return $identifyNumber;
    }

    public function getIdentifyNumber($document)
    {
        try {
            $iin = $document->getClientOriginalExtension() !== 'pdf' ? $this->getIdentifyNumberByPhoto($document) : $this->getIdentifyNumberByPdf($document);
        } catch (\Exception $exception) {
            abort(500, 'Сервис временно не доступен. Обратитесь администратору');
        }
        if (isset($iin['IIN']) && !empty($iin['IIN'])) {
            return $iin['IIN'];
        }
        throw new \ErrorException('Отправьте качественный документ.', 400);
    }


    private function getIdentifyNumberByPdf($document)
    {
        return Http::timeout(20)->withToken($this->token)
            ->attach(
                'document', $document->get(), 'document.png'
            )->asMultipart()->post($this->url . self::IDENTITY_NUMBER_PDF)->json();
    }

    private function getIdentifyNumberByPhoto($document)
    {
        return Http::timeout(20)->withToken($this->token)
            ->attach(
                'document', $document->get(), 'document.png'
            )->asMultipart()->post($this->url . self::IDENTITY_NUMBER_PHOTO)->json();
    }

    private function identifyUser($user, $faceImage, $idImage, $identifyBack = null): bool
    {
        $lastIdentificationImage = $user->identify_front;
        $lastIdentifyFaceImage = $user->identify_face;
        $lastIdentifyBackImage = $user->identify_back;


        $user->identify_front = $this->userRepository->uploadImageToCloud('identification', $user->id, $idImage);
        $user->identify_face = $this->userRepository->uploadImageToCloud('face', $user->id, $faceImage);
        $user->identify_back = $this->userRepository->uploadImageToCloud('identification', $user->id, $identifyBack);
        $user->save();

        $checkRecognizeUser = $idImage->getClientOriginalExtension() !== 'pdf' ? $this->checkRecognizeUserByPhoto($idImage, $faceImage) : $this->checkRecognizeUserByPdf($idImage, $faceImage);

        if ($lastIdentificationImage) {
            $this->userRepository->deleteImageFromCloud($lastIdentificationImage);
        }

        if ($lastIdentifyFaceImage) {
            $this->userRepository->deleteImageFromCloud($lastIdentifyFaceImage);
        }
        if ($lastIdentifyBackImage) {
            $this->userRepository->deleteImageFromCloud($lastIdentifyBackImage);
        }

        if (!$checkRecognizeUser) {
            $user->is_identified = false;
            $user->save();
            return false;
        }

        $user->is_identified = true;
        $user->save();

        return true;
    }


    public function handleContractPdf($file, $replacement = [])
    {
        try {
            return Http::withToken($this->token)
                ->attach(
                    'document', file_get_contents($file), 'rent.pdf'
                )
                ->asMultipart()
                ->post($this->url . 'autoEditPdf', ['replacements' => json_encode($replacement)])->body();

        } catch (\Exception $exception) {
            abort(500, 'Сервис временно не доступен. Обратитесь администратору');
        }
    }

    public function handleContractDoc($file, $replacement = [])
    {
        try {
            return Http::withToken($this->token)
                ->attach(
                    'document', file_get_contents($file), 'rent.docx'
                )
                ->asMultipart()
                ->post($this->url . 'autoEditDoc', ['replacements' => json_encode($replacement)])->body();

        } catch (\Exception $exception) {
            abort(500, 'Сервис временно не доступен. Обратитесь администратору');
        }
    }

    private function checkRecognizeUserByPdf($id, $face)
    {
        try {
            $recognize = Http::withToken($this->token)->timeout(40)
                ->attach(
                    'photo', $face->get(), 'face.jpg'
                )->attach(
                    'document', $id->get(), 'id.pdf'
                )->acceptJson()->post($this->url . self::REKOGNITION_PDF_ENDPOINT)->body();
        } catch (\Exception $exception) {
            abort(500, 'Сервис временно не доступен. Обратитесь администратору');
        }
        return $recognize == "OK" || $recognize == '"OK"';
    }

    private function checkRecognizeUserByPhoto($id, $face)
    {
        try {
            $recognize = Http::withToken($this->token)->timeout(40)
                ->attach(
                    'photo', $face->get(), 'face.jpg'
                )->attach(
                    'document', $id->get(), 'id.jpg'
                )->acceptJson()->post($this->url . self::REKOGNITION_PHOTO_ENDPOINT)->body();
        } catch (\Exception $exception) {
            abort(500, 'Сервис временно не доступен. Обратитесь администратору');
        }
        return $recognize == "OK" || $recognize == '"OK"';
    }

    protected function authorizeInID()
    {
        return Http::post($this->url . self::AUTH_ENDPOINT, [
            'login' => config('flux-auth.identify.login'),
            'password' => config('flux-auth.identify.password')
        ])->json();
    }
}
