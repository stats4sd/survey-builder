<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function store(User $user, Request $request)
    {
        $token = $user->createToken($request->token_name);

        return redirect()->back()->with('token', $token->plainTextToken)->with('token_name', $request->token_name);
    }
}
