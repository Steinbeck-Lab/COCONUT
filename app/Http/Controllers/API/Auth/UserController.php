<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get the authenticated user.
     *
     *
     * @return \App\Models\User
     */
    public function info(Request $request)
    {
        return $request->user();
    }
}
