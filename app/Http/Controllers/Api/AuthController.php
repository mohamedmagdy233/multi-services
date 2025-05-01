<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\changePasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\LoginWithSocialRequest;
use App\Http\Requests\Api\logoutRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\resetPasswordRequest;
use App\Http\Requests\Api\StoreFcmRequest;
use App\Http\Requests\Api\UpdatePhoneRequest;
use App\Http\Requests\Api\updateProfileRequest;
use Illuminate\Http\Request;
use App\Services\Api\AuthService as ObjService;


class AuthController extends Controller
{
    public function __construct(protected ObjService $objService){

    }

    public function register(RegisterRequest $request)
    {
        try {
            return $this->objService->register($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->objService->login($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function loginWithSocial(loginWithSocialRequest $request)
    {
        try {
            return $this->objService->loginWithSocial($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function resetPassword(resetPasswordRequest $request)
    {
        try {
            return $this->objService->resetPassword($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function profile()
    {
        try {
            return $this->objService->profile();
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function storeFcm(storeFcmRequest $request)
    {
        try {
            return $this->objService->storeFcm($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function logout(logoutRequest $request)
    {
        try {
            return $this->objService->logout($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function updateProfile(updateProfileRequest $request)
    {
        try {
            return $this->objService->updateProfile($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function deleteAccount()
    {
        try {
            return $this->objService->deleteAccount();
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function changePassword(changePasswordRequest $request)
    {
        try {
            return $this->objService->changePassword($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function updatePhone(UpdatePhoneRequest $request)
    {
        try {
            return $this->objService->updatePhone($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }



}//end class
