<?php

namespace App\Http\Controllers\auth_user;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogOutController extends Controller
{
    use HttpResponse;
    public function userlogout(Request $request)
    {

        $user = Auth::guard('user')->user();
        if ($user) {

            $request->user()->currentAccessToken()->delete();
            return $this->response(true, 200, 'user log out');
        } else {
            return $this->response(false, 400, 'error log out');
        }
    }
}
