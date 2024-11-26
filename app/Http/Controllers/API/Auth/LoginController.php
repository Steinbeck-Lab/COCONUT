<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @return JsonResponse
     *
     * Attempts to authenticate the user with the provided email and password.
     * If authentication fails, returns a JSON response with an error message.
     * If the user has not verified their email, returns a JSON response with
     * a verification required message. On successful authentication, generates
     * a new API token for the user and returns it in a JSON response.
     */
    public function login(Request $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            if (! $user->hasVerifiedEmail()) {
                return response()->json([
                    'message' => 'Account is not yet verified. Please verify your email address by clicking on the link we just emailed to you.',
                ], 403);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Handle a logout request to the application.
     *
     * @return JsonResponse
     *
     * Deletes the user's current access token, effectively logging the user out.
     * Returns a JSON response indicating successful logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'logout' => 'Successful',
        ]);
    }
}
