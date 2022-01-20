<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $data = $request->all();
        Permission::where('role_id',$data['role_id'])->delete();

        $records = array();
        foreach( $data['permissions'] as $permission => $capabilities ) {
            foreach( $capabilities as $capability => $status){
                array_push($records, ['role_id' => $data['role_id'], 'name' => $permission, 'capability' => $capability, 'created_at' => Carbon::now()]);
            }
        }

        Permission::insert( $records );
        $request->session()->flash('success', 'Permissions were successful updated!');
        return redirect()->route('admin.permissions.index', ['role_id'=>$data['role_id']]);
    }


    /**
     * Get all permissions of the role
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermissions(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->role_id)) {
                $role = Role::find($request->role_id);
                return response()->json(['success' => 'true', 'data' => $role->permissions], 200);
            }
        }
        return false;
    }

    private function validateRequest($role){

        return request()->validate([
            'name' => 'required|unique:roles,name,'.$role->id
        ]);
    }
}
