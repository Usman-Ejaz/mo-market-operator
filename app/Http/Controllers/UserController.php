<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $user = new User();
        $user = User::create( $this->validateRequest($user) );

        $this->storeImage($user);

        $request->session()->flash('success', 'User was successful added!');
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
        $user->update($this->validateRequest($user));

        $this->storeImage($user);

        $request->session()->flash('success', 'User was successful updated!');
        return redirect()->route('admin.users.edit', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User was successful deleted!');
    }

    public function list(Request $request)
    {
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
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.users.edit',$row->id) .'" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.users.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
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

                    return response()->json(['success' => 'true', 'message' => 'image deleted successfully'], 200);
                }
            }

        }

    }
}
