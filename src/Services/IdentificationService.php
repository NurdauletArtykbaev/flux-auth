<?php

namespace Nurdaulet\FluxAuth\Services;


use Nurdaulet\FluxAuth\Repositories\UserRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class IdentificationService
{
    protected mixed $url ;
    const IDENTITY_NUMBER_ENDPOINT = 'getIdentityNumber';
    const AUTH_ENDPOINT = 'token';
    const REKOGNITION_PHOTO_ENDPOINT = 'compareFaces';
    const REKOGNITION_PDF_ENDPOINT = 'compareFacesFromPdf';

    public function __construct(private UserRepository $userRepository)
    {
        $this->url = config('flux-auth.identify.url');
    }

    /**
     * @throws ValidationException
     */
    public function  identify($user, $face, $identification, $identifyBack = null)
    {
        $isSuccess = $this->identifyUser(
            $user,
            $face,
            $identification, $identifyBack);

        if (!$isSuccess) {
            throw new \ErrorException( 'Не похоже.Снимитесь повторно.',400);
        }

        $identifyNumber = $this->getIdentifyNumberByUser(
            $user,
            $identification
        );

        $user->iin = $identifyNumber;
        $user->save();
        return $identifyNumber;
    }


    public function getIdentifyNumberByUser($user, $document)
    {
        $token = $this->authorizeInID()['access_token'];
        try {
            $iin = Http::timeout(20)->withToken($token)
                ->attach(
                    'document',$document->get(), 'document.png'
                )->asMultipart()->post($this->url . self::IDENTITY_NUMBER_ENDPOINT)->json();
        } catch (\Exception $exception) {
            return null;
        }
        if (isset($iin[0]) && $iin[0]['IIN']) {
            return $iin[0]['IIN'];
        }
        return null;
//        throw ValidationException::withMessages(['identification' => 'Отправьте качественный документ ']);
    }

    private function identifyUser($user, $faceImage, $idImage, $identifyBack = null): bool
    {
//        $user->identify_face = UserHelper::IDENTIFY_STATUS_SUCCESS;
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
            return  false;
        }

        $user->is_identified = true;
        $user->save();

        return true;
    }


    public function handleContractPdf($file, $replacement = [])
    {
        $token = $this->authorizeInID()['access_token'];

        try {
            return Http::withToken($token)
                ->attach(
                    'document', file_get_contents($file), 'rent.pdf'
                )
                ->asMultipart()
                ->post($this->url . 'autoEditPdf', ['replacements' => json_encode($replacement)])->body();

        } catch (\Exception $exception) {
            abort(500,'Сервис временно не доступен. Обратитесь администратору');
        }
    }

    public function handleContractDoc($file, $replacement = [])
    {
        $token = $this->authorizeInID()['access_token'];

        try {
            return Http::withToken($token)
                ->attach(
                    'document', file_get_contents($file), 'rent.docx'
                )
                ->asMultipart()
                ->post($this->url . 'autoEditDoc', ['replacements' => json_encode($replacement)])->body();

        } catch (\Exception $exception) {
            abort(500,'Сервис временно не доступен. Обратитесь администратору');
        }
    }

    private function checkRecognizeUserByPdf($id, $face)
    {
        $token = $this->authorizeInID()['access_token'];

        try {
            $recognize =  Http::withToken($token)->timeout(40)
                ->attach(
                    'photo', $face->get(), 'face.jpg'
                )->attach(
                    'document', $id->get(), 'id.pdf'
                )->acceptJson()->post($this->url . self::REKOGNITION_PDF_ENDPOINT)->body();
        } catch (\Exception $exception) {
            abort(500,'Сервис временно не доступен. Обратитесь администратору');
        }
        return $recognize == "OK" || $recognize == '"OK"';
    }

    private function checkRecognizeUserByPhoto($id, $face)
    {
        $token = $this->authorizeInID()['access_token'];

        try {
            $recognize =  Http::withToken($token)->timeout(40)
                ->attach(
                    'photo', $face->get(), 'face.jpg'
                )->attach(
                    'document', $id->get(), 'id.jpg'
                )->acceptJson()->post($this->url . self::REKOGNITION_PHOTO_ENDPOINT)->body();
        } catch (\Exception $exception) {
            abort(500,'Сервис временно не доступен. Обратитесь администратору');
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
