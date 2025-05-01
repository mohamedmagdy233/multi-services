<?php

namespace App\Services\Api;

use App\Http\Resources\Api\UserResource;
use App\Models\DeviceToken;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use App\Models\User as ObjModel;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService
{

    public function __construct(ObjModel $model, protected DeviceToken $deviceToken)
    {
        parent::__construct($model);

    }

    public function register($data)
    {
        unset($data['password_confirmation']);
        $data['user_type'] = 0;
        $data['password'] = Hash::make($data['password']);
        $model = $this->createData($data);
        if ($model) {
            $token = Auth::guard('api')->login($model);
            $model->token = 'Bearer ' . $token;
            return $this->responseMsg('User created successfully', new UserResource($model), 200);
        } else {
            return $this->responseMsg('User not created', null, 400);
        }


    }

    public function login($data)
    {
        $obj = $this->model->where('phone', $data['phone'])->first();
        if ($obj->status == 0) {
            return $this->responseMsg('Your account is blocked contact admin', null, 401);
        }
        $credentials = ['phone' => $data['phone'], 'password' => $data['password']];
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->responseMsg('Phone or password is wrong', null, 401);
        }
        $user = Auth::guard('api')->user();
        $user->token = 'Bearer ' . $token;
        return $this->responseMsg('User login successfully', new UserResource($user), 200);


    }

    public function loginWithSocial($request)
    {
        $data = $request->all();


        $user = $this->model->where($request->social_type . '_email', $request->email)->first();
        if ($user) {
            if ($user->status == 0) {
                return $this->responseMsg('Your account is blocked contact admin', null, 401);
            }
            $token = Auth::guard('api')->login($user);
            $user->token = 'Bearer ' . $token;
            return $this->responseMsg('User login successfully', new UserResource($user), 200);
        } else {
            if ($request->social_type == 'google') {
                $data['google_email'] = $request->email;
            } elseif ($request->social_type == 'facebook') {
                $data['facebook_email'] = $request->email;
            } elseif ($request->social_type == 'apple') {
                $data['apple_email'] = $request->email;
            }
            unset($data['email']);
            $data['password'] = Hash::make(uniqid());
            $data['user_type'] = 0;
            $model = $this->createData($data);
            if ($model) {
                $token = Auth::guard('api')->login($model);
                $model->token = 'Bearer ' . $token;
                return $this->responseMsg('User created successfully', new UserResource($model), 200);
            } else {
                return $this->responseMsg('User not created', null, 400);
            }
        }

    }

    public function resetPassword($data)
    {
        unset($data['password_confirmation']);
        $user = $this->model->where('phone', $data['phone'])->first();
        if ($user->status == 0) {
            return $this->responseMsg('Your account is blocked contact admin', null, 401);
        }
        if ($user) {
            $user['password'] = Hash::make($data['password']);
            $user->save();
            return $this->responseMsg('password update successfully', new UserResource($user), 200);
        } else {
            return $this->responseMsg('Phone number not found', null, 400);
        }

    }

    public function profile()
    {

        $user = Auth::guard('api')->user();
        return $this->responseMsg('User profile', new UserResource($user), 200);

    }

    public function updateProfile($data)
    {
        $user = Auth::guard('api')->user();
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'users');
        }
        $user->update($data);
        return $this->responseMsg('User profile updated successfully', new UserResource($user), 200);

    }

    public function storeFcm($data)
    {
        $data['user_id'] = Auth::guard('api')->user()->id;
        $model = $this->deviceToken->create($data);
        if ($model) {
            return $this->responseMsg('Fcm token stored successfully', null, 200);
        }
        return $this->responseMsg('Fcm token not stored', null, 400);

    }

    public function changePassword($data)
    {
        $user = Auth::guard('api')->user();
        if (Hash::check($data['old_password'], $user->password)) {
            $user->password = Hash::make($data['password']);
            $user->save();
            return $this->responseMsg('Password changed successfully', null, 200);
        } else {
            return $this->responseMsg('Old password is wrong', null, 400);
        }

    }

    public function deleteAccount()
    {
        $user = Auth::guard('api')->user();
        JWTAuth::invalidate(JWTAuth::getToken());
        $user->deviceTokens()->delete();
        $user->delete();
        return $this->responseMsg('User deleted successfully', null, 200);

    }


    public function logout($data)
    {
        $user = Auth::guard('api')->user();
        JWTAuth::invalidate(JWTAuth::getToken());
        $this->deviceToken->where('token', $data['token'])->delete();
        return $this->responseMsg('User logout successfully', null, 200);

    }

    public function updatePhone($data)
    {
        $user = Auth::guard('api')->user();
        if ($user->phone !== null) {
            return $this->responseMsg('Phone number already updated you can not update it', null, 400);
        }
        $user->update(['phone' => $data['phone']]);
        $token = Auth::guard('api')->login($user);
        $user->token = 'Bearer ' . $token;

        return $this->responseMsg('Phone number updated successfully', new UserResource($user), 200);

    }

}
