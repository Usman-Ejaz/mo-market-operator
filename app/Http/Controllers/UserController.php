<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        if( !Auth::user()->role->hasPermission('users', 'list') ){
            return abort(403);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('users', 'create') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('users', 'create') ){
            return abort(403);
        }

        $user = new User();
        $user = User::create( $this->validateRequest($user) );

        if ($user->exists) {
            $this->storeImage($user);

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
        if( !Auth::user()->role->hasPermission('users', 'view') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('users', 'edit') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('users', 'edit') ){
            return abort(403);
        }

        if ( $user->update($this->validateRequest($user)) ) {
            $this->storeImage($user);

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
        if( !Auth::user()->role->hasPermission('users', 'delete') ){
            return abort(403);
        }

        if( $user->delete() ) {
            return redirect()->route('admin.users.index')->with('success', 'User Deleted Successfully!');
        }

        return redirect()->route('admin.users.index')->with('error', 'User was not deleted!');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('users', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = User::with(['Role'])->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
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
                    if( Auth::user()->role->hasPermission('users', 'edit') ) {
                        $options .= '<a href="' . route('admin.users.edit', $row->id) . '" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('users', 'delete') ) {
                        $options .= ' <form action="' . route('admin.users.destroy', $row->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
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

        return tap( request()->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role_id' => 'required|min:1',
            'department' => 'nullable',
            'image' => 'nullable',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ]), function(){
            if( request()->hasFile('image') ){
                request()->validate([
                    'image' => 'file|image|max:2000'
                ]);
            }
        });
    }

    private function storeImage($user){

        if (request()->has('image')) {
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

            if( isset($request->user_id) ){
                $user = User::find($request->user_id);

                $image_path = config('filepaths.userProfileImagePath.public_path').$user->image;

                if( unlink($image_path) ){
                    $user->image = null;
                    $user->update();

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }

        }

    }
}
