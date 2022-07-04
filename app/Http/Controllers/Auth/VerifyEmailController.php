<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {

        $user = \App\Models\User::find($id);
        abort_if(!$user, 403);
        abort_if(!hash_equals(
            $hash,
            sha1($user->getEmailForVerification())
        ), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return view("verified-account");
    }
}
