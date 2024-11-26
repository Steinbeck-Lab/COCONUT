<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Verify the user's email address.
     *
     * @param  int  $user_id  The ID of the user to be verified.
     * @param  Request  $request  The incoming request instance.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * This method checks for a valid signature in the request. If the request's user
     * does not match the provided user ID, the user is logged out and an authorization
     * exception is thrown. If the user's email is not verified, it marks the email as
     * verified and redirects to the welcome route with a success message.
     * If the URL is invalid or expired, a JSON response with an error message is returned.
     */
    public function verify($user_id, Request $request)
    {
        if (! $request->hasValidSignature()) {
            return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
        }

        if ($request->user() && $request->user()->getKey() != $user_id) {
            Auth::logout();
            throw new AuthorizationException;
        }

        $user = User::findOrFail($user_id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('welcome')->with('success', 'Email verification Successful');
    }

    /**
     * Resend the email verification link to the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * This method checks if the user is authenticated. If authenticated, it sends a
     * new email verification notification to the user's registered email address.
     * A JSON response is returned to confirm the action.
     *
     * If the user's email is already verified (commented-out logic), it would respond
     * with a message indicating the email is already verified.
     */
    public function resend()
    {
        if (auth()->user()) {
            // if (auth()->user()->hasVerifiedEmail()) {
            //     return response()->json(['msg' => 'Email already verified.'], 400);
            // }

            auth()->user()->sendEmailVerificationNotification();

            return response()->json(['msg' => 'Email verification link sent on your email id']);
        }
    }
}
