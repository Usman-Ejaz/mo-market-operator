<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserInfoForComplaintDepartments;
use App\Http\Requests\StoreComplaintDepartmentRequest;
use App\Http\Requests\UpdateComplaintDepartmentRequest;
use App\Models\ComplaintDepartment;
use App\Models\User;
use Yajra\DataTables\DataTables;


class ComplaintDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.complaint-departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.complaint-departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComplaintDepartmentRequest $request)
    {
        ComplaintDepartment::create($request->validated());
        $request->session()->flash('success', "Successfully created a new department.");
        return redirect()->route('admin.complaint-departments.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = ComplaintDepartment::with('pm:id,name,email,designation', 'apm:id,name,email,designation')->findOrFail($id);
        return view('admin.complaint-departments.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComplaintDepartmentRequest $request, $id)
    {
        ComplaintDepartment::where('id', $id)->update($request->validated());
        $request->session()->flash('success', "Succesfully update department.");
        return redirect()->route('admin.complaint-departments.edit', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // abort_if(!hasPermission("reports", "delete"), 401, __('messages.unauthorized_action'));
        $department = ComplaintDepartment::findOrFail($id);
        request()->session()->flash("success", "Successfully deleted " . $department->name . " department");
        $department->delete();
        return redirect()->route('admin.complaint-departments.index');
    }

    /**
     * Datatable endpoint for complaint departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        if (request()->ajax()) {
            $departments = ComplaintDepartment::all();
            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 27);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $options = "<a href='" . route('admin.complaint-departments.edit', [$row->id]) . "' title='Edit' class='btn btn-primary'>
                <i class='fas fa-pencil-alt'></i>
            </a>
            " .
                        '<form method="POST" action="' . route('admin.complaint-departments.destroy', [$row->id]) . '" style="display:inline">' .
                        method_field('DELETE') .
                        csrf_field() .
                        '<button type="submit" class="btn btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                        </button>
                    </form>';
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getUserInfo(GetUserInfoForComplaintDepartments $request)
    {
        $userQuery = User::query();
        if ($request->has('search')) {
            $userQuery->search($request->search);
        }
        $users = $userQuery->take(6)->select(['id', 'name', 'email', 'designation'])->get();
        return $users;
    }
}
