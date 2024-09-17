<?php

namespace App\Http\Controllers\auth_user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Resetpassword;
use App\Models\User;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Mail;


class PassWordController extends Controller
{
    use HttpResponse;
    public function change_user_password(Request $request){
        // dd($request->all());
        $user = Auth::guard('user')->user()->id;
        if($user){
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);
            if(Hash::check($request->input('current_password'), $user->password)){
                $user->update([
                    'password' => Hash::make($request->input('new_password')),
                ]);
                return $this->response(true, 200, 'Password updated successfully');
            }else{
                return $this->response(false, 400, 'Current password is incorrect');
            }
        }else{
            return $this->response(false, 401, 'Unauthorized');
        }
    }


    public function user_forget_password(Request $request, UrlGenerator $url)
    {
        try {
            $request = $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $url = $url->temporarySignedRoute(
                'passwordd',
                now()->addMinutes(20),
                ['email' => $request['email']]
            );
                 // $newPassword = '';

            // while (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
            //     $newPassword = Str::random(8);
            //     // Ensure criteria are met by adding one character each of lowercase, uppercase, digit, and special character
            //     $newPassword .= chr(rand(97, 122)); // Lowercase letter
            //     $newPassword .= chr(rand(65, 90));  // Uppercase letter
            //     $newPassword .= chr(rand(48, 57));  // Digit
            //     $newPassword .= str_shuffle('@$!%*?&')[0]; // Special character
            //     // Shuffle the password to randomize character order
            //     $newPassword = Str::of($newPassword)->shuffle()->substr(0, 8);
            // }

            $newPassword = Str::random(10);

            // Update the user's temporary password in the database
            $user = User::where('email', $request['email'])->first();
            $user->temp_password = Hash::make($newPassword);

            $user->save();

            // Send email with the new password
            Mail::to($request['email'])->sendNow(new Resetpassword($newPassword));
            return $this->response(true, 200, 'Please check your email.');
        } catch (Exception $e) {
            // Log the error


            // Return an error response
            return $this->response(false, 500, 'An error occurred while processing your request.');
        }
    }

    public function Login_new_password(Request $request)
    {
        $request_get_password = $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('email', $request_get_password['email'])->first();

        // Check if the provided new_password matches the hashed temp_password
        if (Hash::check($request_get_password['new_password'], $user->temp_password)) {
            // Update the user's password
            $user->password = Hash::make($request_get_password['new_password']);
            // Clear the temp_password field
            $user->temp_password = null;
            $token = $user->createToken('login' .    $user->id)->plainTextToken;
            $user->save();

            return $this->response(true, 200, 'Password confimed successfully. ',$token);
        } else {
            return $this->response(false, 400, 'Invalid new password or email');
        }
    }
}
