<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->role->hasPermission('roles', 'list') ){
            return abort(403);
        }

        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('roles', 'create') ){
            return abort(403);
        }

        $role = new Role();
        return view('admin.roles.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('roles', 'create') ){
            return abort(403);
        }

        $role = new Role();
        $role = Role::create( $this->validateRequest($role) );

        $request->session()->flash('success', 'Role Added Successfully!');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        if( !Auth::user()->role->hasPermission('roles', 'view') ){
            return abort(403);
        }

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if( !Auth::user()->role->hasPermission('roles', 'edit') ){
            return abort(403);
        }

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if( !Auth::user()->role->hasPermission('roles', 'edit') ){
            return abort(403);
        }

        $role->update($this->validateRequest($role));

        $request->session()->flash('success', 'Role Updated Successfully!');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if( !Auth::user()->role->hasPermission('roles', 'delete') ){
            return abort(403);
        }
        try {
            $role->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->route('admin.roles.index')->with('error', 'This role is assigned to user(s). Cannot delete parent record!');
        } catch (\Exception $ex) {
            return redirect()->route('admin.roles.index')->with('error', 'Could not delete role!');
        }
        return redirect()->route('admin.roles.index')->with('success', 'Role Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('roles', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Role::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ( isset($row->name)) ? $row->name : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('roles', 'edit') ) {
                        $options .= '<a href="'. route('admin.roles.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( Auth::user()->role->hasPermission('roles', 'delete') ) {
                        $options .= ' <form action="'. route('admin.roles.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
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

    private function validateRequest($role){

        return request()->validate([
            'name' => 'required|unique:roles,name,'.$role->id
        ]);
    }
}
