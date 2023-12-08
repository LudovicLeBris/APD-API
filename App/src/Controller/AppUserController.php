<?php

namespace App\Controller;

use App\Domain\AppUser\UseCase\Login\Login;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Presentation\AppUser\LoginJsonPresenter;
use App\Domain\AppUser\UseCase\Register\Register;

use App\Presentation\AppUser\RegisterJsonPresenter;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUser;
use App\Presentation\AppUser\GetAppUserJsonPresenter;
use App\Domain\AppUser\UseCase\Register\RegisterRequest;
use App\Presentation\AppUser\ConfirmRegisterJsonPresenter;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserRequest;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterRequest;
use App\Domain\AppUser\UseCase\Login\LoginRequest;
use App\Domain\AppUser\UseCase\LostPassword\LostPassword;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordRequest;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUser;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserRequest;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUser;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserRequest;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePassword;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordRequest;
use App\Presentation\AppUser\LostPasswordJsonPresenter;
use App\Presentation\AppUser\RemoveAppUserJsonPresenter;
use App\Presentation\AppUser\UpdateAppUserJsonPresenter;
use App\Presentation\AppUser\UpdatePasswordJsonPresenter;
use App\Presentation\JsonModel;

#[Route('/api/V2')]
class AppUserController extends AbstractController
{
    #[Route(
        '/appuser/{id}',
        name: 'app_appuser_getappAppUser',
        methods: ['GET'],
        requirements: ['id' => '\d+']
    )]
    public function getAppUser(
        int $id,
        GetAppUser $getAppUser,
        GetAppUserJsonPresenter $presenter
    )
    {
        $getAppUser->execute(new GetAppUserRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/register',
        name: 'app_appuser_register',
        methods: ['POST']
    )]
    public function register(
        Request $request,
        Register $register,
        RegisterRequest $nulllableRequest,
        RegisterJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $register->execute($nulllableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/register/{id}',
        name: 'app_appuser_confirmregister',
        methods: ['POST'],
        requirements: ['id' => '\d+']
    )]
    public function confirmRegister(
        int $id,
        ConfirmRegister $confirmRegister,
        ConfirmRegisterJsonPresenter $presenter
    )
    {
        $confirmRegister->execute(new ConfirmRegisterRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/login',
        name: 'app_appuser_login'
    )]
    public function login(
        Request $request,
        Login $login,
        LoginJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);
        
        $login->execute(new LoginRequest($content['email'], $content['password']), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/appuser/{id}',
        name: 'app_appuser_updateappuser',
        methods: ['PUT'],
        requirements: ['id' => '\d+']
    )]
    public function updateAppUser(
        int $id,
        Request $request,
        UpdateAppUser $updateAppUser,
        UpdateAppUserJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nulllableRequest = new UpdateAppUserRequest($id);

        $updateAppUser->execute($nulllableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/appuser/{id}',
        name: 'app_appuser_removeappuser',
        methods: ['DELETE'],
        requirements: ['id' => '\d+']
    )]
    public function removeAppUser(
        int $id,
        RemoveAppUser $removeAppUser,
        RemoveAppUserJsonPresenter $presenter
    )
    {
        $removeAppUser->execute(new RemoveAppUserRequest($id), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/lostpassword',
        name: 'app_appuser_lostpassword',
        methods: ['POST'],
    )]
    public function lostPassword(
        Request $request,
        LostPassword $lostPassword,
        LostPasswordJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        if (!array_key_exists('email', $content) || is_null($content)) {
            $jsonModel = new JsonModel(
                'error',
                ['Email is missing'],
                422
            );
            return $this->json(...$jsonModel->getJsonResponse());
        }

        $lostPassword->execute(new LostPasswordRequest($content['email']), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/recoverpassword/{guid}',
        name: 'app_appuser_recoverpassword',
        methods: ['PUT'],
        requirements: ['guid' => '(?:\d|[a-z]){16}']
    )]
    public function recoverPassword(
        string $guid,
        Request $request,
        UpdatePassword $updatePassword,
        UpdatePasswordJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = (new UpdatePasswordRequest())->setGuid($guid);

        $updatePassword->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }

    #[Route(
        '/updatepassword/{id}',
        name: 'app_appuser_updatepassword',
        methods: ['PUT'],
        requirements: ['id' => '\d+']
    )]
    public function updatePassword(
        int $id,
        Request $request,
        UpdatePassword $updatePassword,
        UpdatePasswordJsonPresenter $presenter
    )
    {
        $content = json_decode($request->getContent(), true);

        $nullableRequest = (new UpdatePasswordRequest())->setId($id);

        $updatePassword->execute($nullableRequest->setContent($content), $presenter);

        return $this->json(...$presenter->getJson());
    }
}