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
        // if (!hasPermission('profile', 'edit')) {
        //     return abort(403);
        // }

        $previousImage = $user->image;

        if ($user->update($this->validateRequest($user))) {
            $this->storeImage($user, $previousImage);

            $request->session()->flash('success', 'Pofile Updated Successfully!');
            return redirect()->route('admin.dashboard');
        }

        $request->session()->flash('error', 'User was not updated, please try again');
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * storeImage
     *
     * @param  mixed $user
     * @param  mixed $previousImage
     * @return void
     */
    private function storeImage($user, $previousImage = null) {

        if (request()->has('image')) {

            if ($previousImage !== null) {
                $image_path = public_path(config('filepaths.userProfileImagePath.public_path')) . basename($previousImage);
                unlink($image_path);
            }

            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.userProfileImagePath.internal_path'), $file_name);

            $user->update([
                'image' => $file_name,
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
            'image' => 'sometimes|file|image|max:2000',
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

                $image_path = public_path(config('filepaths.userProfileImagePath.public_path')) . basename($user->image);

                if (unlink($image_path)) {
                    $user->update(['image' => null]);
                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.confirmed' => 'Password must be same.'
        ]);

        $user = User::where(['email' => auth()->user()->email])->first();

        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->password, $user->password)) {
                    return redirect()->back()->withErrors("New Password should be different from Old Password.");
                }
                $user->password = bcrypt($request->get("password"));
                $user->save();
                $request->session()->flash("success", "Password Updated Successfully");
                return redirect()->route("admin.dashboard");
            } else {
                return redirect()->back()->withErrors("Old Password is wrong.");
            }
        } else {
            return redirect()->back()->withErrors("Email does not exists.");
        }
    }
}
