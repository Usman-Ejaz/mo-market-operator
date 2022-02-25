<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->role->hasPermission('permissions', 'view') ){
            return abort(403);
        }

        $roles = Role::all();
        $permissions = config('permissions');
        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('permissions', 'edit') ){
            return abort(403);
        }

        $data = $request->all();
        Permission::where('role_id',$data['role_id'])->delete();

        $records = array();
        if( isset($data['permissions']) ) {
            foreach ($data['permissions'] as $permission => $capabilities) {
                foreach ($capabilities as $capability => $status) {
                    array_push($records, ['role_id' => $data['role_id'], 'name' => $permission, 'capability' => $capability, 'created_at' => Carbon::now()]);
                }
            }
        }

        Permission::insert( $records );

        $request->session()->flash('success', 'Permissions Updated Successfully!');
        $request->session()->flash('role_id', $data['role_id']);
        return redirect()->route('admin.permissions.index');
    }


    /**
     * Get all permissions of the role
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermissions(Request $request)
    {
        if( !Auth::user()->role->hasPermission('permissions', 'view') ){
            return abort(403);
        }

        if ($request->ajax()) {
            if (isset($request->role_id)) {
                $role = Role::find($request->role_id);
                return response()->json(['success' => 'true', 'data' => $role->permissions], 200);
            }
        }
        return false;
    }
}
