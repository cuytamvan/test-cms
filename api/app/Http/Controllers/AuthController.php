<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        try {
            $payload = $request->only('email', 'password');
            $validator = Validator::make($payload, [
                'email' => 'required',
                'password' => 'required',
            ]);
            if ($validator->fails()) throw new ValidationException($validator->errors()->first());

            $data = $this->userRepository->findByEmail($request->email);
            if (!$data) throw new ValidationException('User not found');
            if (!Hash::check($request->password, $data->password)) throw new ValidationException('Password is not match');

            $data->load('role')->load('role.permissions');
            $data->api_token = $data->createToken($request->userAgent())->plainTextToken;

            if (!Cache::has('role:' . $data->role_id))
                Cache::put('role:' . $data->role_id, $data->role->permissions ?? []);

            return response()->json([
                'message' => 'success',
                'data' => new UserResource($data),
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                'data' => null,
                'message' => 'success logout'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function user(Request $request)
    {
        $user = $request->user();
        if ($user) $user->load('role')->load('role.permissions');

        return response()->json([
            'data' => $user ? new UserResource($user) : null,
            'message' => 'success'
        ]);
    }
}
