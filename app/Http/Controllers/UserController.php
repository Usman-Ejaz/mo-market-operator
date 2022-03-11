<?php

namespace App\Http\Controllers;

use App\Mail\NewUserCreatePasswordEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("users", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("users", "create"), 401, __('messages.unauthorized_action'));

        $user = new User();
        return view('admin.users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("users", "create"), 401, __('messages.unauthorized_action'));

        $user = new User();
        $user = User::create( $this->validateRequest($user) );

        if ($user->exists) {
            $this->storeImage($user);

            if ($request->get("sendEmail") == "1") {
                Mail::to($user->email)->send(new NewUserCreatePasswordEmail($user));
            }

            $request->session()->flash('success', 'User Added Successfully!');
            return redirect()->route('admin.users.index');
        }

        $request->session()->flash('error', 'User was not added, please try again');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort_if(!hasPermission("users", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if(!hasPermission("users", "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        abort_if(!hasPermission("users", "edit"), 401, __('messages.unauthorized_action'));
        
        $previousImage = $user->image;

        if ($user->update($this->validateRequest($user))) {
            $this->storeImage($user, $previousImage);

            if ($request->get("sendEmail") == "1") {
                Mail::to($user->email)->send(new NewUserCreatePasswordEmail($user));
            }

            $request->session()->flash('success', 'User Updated Successfully!');
            return redirect()->route('admin.users.index');
        }

        $request->session()->flash('error', 'User was not updated, please try again');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if(!hasPermission("users", "delete"), 401, __('messages.unauthorized_action'));

        if ($user->image !== null) {
            $file_path = public_path(config('filepaths.userProfileImagePath.public_path')) . basename($user->image);
            unlink($file_path);
        }

        if( $user->delete() ) {
            return redirect()->route('admin.users.index')->with('success', 'User Deleted Successfully!');
        }

        return redirect()->route('admin.users.index')->with('error', 'User was not deleted!');
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('users', 'list'), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = User::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 25);
                })
                ->addColumn('role', function ($row) {
                    return ( isset($row->role->name)) ? $row->role->name : '';
                })
                ->addColumn('department', function ($row) {
                    return ($row->department) ? $row->department : '';
                })
                ->addColumn('status', function ($row) {
                    return ($row->active) ? $row->active : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission('users', 'edit') ) {
                        $options .= '<a href="' . route('admin.users.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('users', 'delete') ) {
                        $options .= ' <form action="' . route('admin.users.destroy', $row->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="Delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

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
            "image.max" => __('messages.max_file', ['limit' => '2 MB'])
        ]);
    }

    private function storeImage($user, $previousImage = null) {
        if (request()->has('image')) {

            if ($previousImage !== null) {
                $file_path = public_path(config('filepaths.userProfileImagePath.public_path')) . basename($previousImage);
                unlink($file_path);
            }
            
            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.userProfileImagePath.internal_path'), $file_name);

            $user->update([
                'image' => $file_name,
            ]);
        }
    }

    public function deleteImage(Request $request){

        if ($request->ajax()) {

            if (isset($request->user_id)) {
                $user = User::find($request->user_id);

                $image_path = public_path(config('filepaths.userProfileImagePath.public_path')) . basename($user->image);

                if( unlink($image_path) ){
                    $user->image = null;
                    $user->update();

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }
        }
    }
}
