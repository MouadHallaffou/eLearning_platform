<?php

namespace App\Http\Controllers\V2;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Classes\AuthResponseApi  as AuthResponseApi;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the AuthController"
 * )
 */
class AuthController extends AuthResponseApi
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "c_password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="c_password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="User registered successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation Error."),
     *             @OA\Property(property="data", type="object", example={"email": {"The email field is required."}})
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            $input = $request->validated();
            $user = $this->userRepository->createUser($input);
            $success['user'] = $user;

            return $this->sendResponse($success, 'User registered successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Registration failed.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Token"),
     *             @OA\Property(property="message", type="string", example="User logged in successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorised",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorised."),
     *             @OA\Property(property="data", type="object", example={"error": "Unauthorised"})
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!$token = auth('api')->attempt($credentials)) {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
            }

            $success = $this->respondWithToken($token);

            return $this->sendResponse($success, 'User logged in successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Login failed.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/profile",
     *     summary="Get the authenticated user's profile",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="User profile retrieved successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found."),
     *             @OA\Property(property="data", type="object", example=null)
     *         )
     *     )
     * )
     */
    public function profile()
    {
        try {
            $user = Auth::user();

            if ($user) {
                return $this->sendResponse($user, 'User profile retrieved successfully.');
            }

            return $this->sendError('User not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Profile retrieval failed.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", example=[]),
     *             @OA\Property(property="message", type="string", example="Successfully logged out.")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            Auth::logout();
            return $this->sendResponse([], 'Successfully logged out.');
        } catch (\Exception $e) {
            return $this->sendError('Logout failed.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Refresh the authenticated user's token",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Token"),
     *             @OA\Property(property="message", type="string", example="Token refreshed successfully.")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        try {
            $newToken = auth('api')->refresh(); // Utiliser le guard 'api'
            return $this->sendResponse($this->respondWithToken($newToken), 'Token refreshed successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Token refresh failed.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/update-profile",
     *     summary="Update the authenticated user's profile",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Profile update failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Profile update failed."),
     *             @OA\Property(property="data", type="object", example=null)
     *         )
     *     )
     * )
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $input = $request->validated();

            if (isset($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            }

            $user = Auth::user();
            $updatedUser = $this->userRepository->updateUser($user->id, $input);

            if ($updatedUser) {
                return $this->sendResponse($updatedUser, 'Profile updated successfully.');
            }

            return $this->sendError('Profile update failed.');
        } catch (\Exception $e) {
            return $this->sendError('Profile update failed.', $e->getMessage(), 500);
        }
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
