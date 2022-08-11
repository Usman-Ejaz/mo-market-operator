<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{    
    /**
     * show
     *
     * @return void
     */
    public function show()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $user
     * @return void
     */
    public function update(Request $request, User $user)
    {

        if ($user->update($this->validateRequest($user))) {
            $this->storeImage($user);

            $request->session()->flash('success', __('messages.record_updated', ['module' => 'Profile']));
            return redirect()->route('admin.dashboard');
        }

        $request->session()->flash('error', 'User was not updated, please try again');
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * storeImage
     *
     * @param  mixed $user
     * @return void
     */
    private function storeImage($user) {

        if (request()->has('image')) {
            $user->update([
                'image' => storeFile(User::STORAGE_DIRECTORY, request()->file('image'), $user->image),
            ]);
        }
    }
    
    /**
     * validateRequest
     *
     * @param  mixed $user
     * @return void
     */
    private function validateRequest($user){

        return request()->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role_id' => 'required|min:1',
            'department' => 'nullable',
            'image' => 'sometimes|file|image|max:' . config('settings.maxImageSize'),
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ], [
            'image.max' => __('messages.max_file', ['limit' => '2 MB'])
        ]);
    }
    
    /**
     * deleteImage
     *
     * @param  mixed $request
     * @return void
     */
    public function deleteImage(Request $request){

        if ($request->ajax()) {

            if( isset($request->user_id) ){
                $user = User::find($request->user_id);
                if (removeFile(User::STORAGE_DIRECTORY, $user->image)) {
                    $user->update(['image' => null]);
                    return response()->json(['success' => 'true', 'message' => __('messages.record_deleted', ['module' => 'Image'])], 200);
                }
            }

        }

    }
    
    /**
     * updatePasswordView
     *
     * @return void
     */
    public function updatePasswordView()
    {
        return view("admin.auth.update-password");
    }
    
    /**
     * updatePassword
     *
     * @param  mixed $request
     * @return void
     */
    public function updatePassword(Request $request) {
        
        $request->validate([
            'old_password' => ['required'],
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

        $user = User::where(['email' => auth()->user()->email])->first();

        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->password, $user->password)) {
                    return redirect()->back()->withErrors("New Password should be different from Old Password.");
                }
                $user->password = bcrypt($request->get("password"));
                $user->save();
                
                $request->session()->flash("success", __('messages.record_updated', ['module' => 'Password']));
                return redirect()->route("admin.dashboard");
            } else {
                return redirect()->back()->withErrors("Old Password is wrong.");
            }
        } else {
            return redirect()->back()->withErrors("Email does not exists.");
        }
    }
}
