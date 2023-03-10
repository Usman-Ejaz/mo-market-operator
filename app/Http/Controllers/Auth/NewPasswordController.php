<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('admin.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'bail',
                'required', 
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
        ], [
            'password.confirmed' => 'Password must be same.',
            'password.regex' => 'Password should must contain Uppercase, lowercase, number and special characters.'
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('admin.login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function createPassword(Request $request, $user) {

        $user = User::where(['email' => $user])->first();

        if ($user && $user->password_link !== null) {
            $signature = $request->signature;
            return view("admin.auth.create-password", compact('user', 'signature'));
        }

        abort(401, __('Password link has been expired.'));
    }

    public function createNewPassword(Request $request) {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'bail',
                'required', 
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
        ], [
            'password.confirmed' => 'Password must be same.',
            'password.regex' => 'Password should must contain Uppercase, lowercase, number and special characters.'
        ]);

        $user = User::where(['email' => $request->get("email")])->first();

        if ($user) {

            if ($user->password_link === null) {
                abort(401, __('Password link has been expired.'));
            }

            if ($user->active == "Active") {

                $user->password = bcrypt($request->get("password"));
                $user->password_link = null;
                $user->save();

                auth()->check() && auth()->logout();

                return redirect()->route("admin.login")->with('success', __('messages.record_created', ['module' => 'Password']));
            }

            return redirect()->back()->withErrors("Your email has been blocked or temporarily disable.");
        } else {
            return redirect()->back()->withErrors("Email does not exists.");
        }
    }
}
