<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeamMemberController extends Controller
{       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('team_members', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.team-members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! hasPermission('team_members', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $teamMember = new TeamMember;
        $managers = Manager::select('id', 'name')->latest()->get();
        
        return view('admin.team-members.create', compact('teamMember', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(! hasPermission('team_members', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));
        $data = $this->validateRequest();

        $data['image'] = '';

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(TeamMember::STORAGE_DIRECTORY, $request->file('image'));
        }
        
        TeamMember::create($data);

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Team member']));

        return redirect()->route('admin.team-members.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function show(TeamMember $teamMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamMember $teamMember)
    {
        abort_if(! hasPermission('team_members', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        $managers = Manager::select('id', 'name')->latest()->get();

        return view('admin.team-members.edit', compact('teamMember', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        abort_if(! hasPermission('team_members', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $data = $this->validateRequest($teamMember);        

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(TeamMember::STORAGE_DIRECTORY, $request->file('image'), $teamMember->image);
        }

        $teamMember->update($data);
        
        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Team member']));

        return redirect()->route('admin.team-members.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamMember $teamMember)
    {
        abort_if(! hasPermission('team_members', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $teamMember->removeImage();
        $teamMember->delete();
        return redirect()->route('admin.team-members.index')->with('success', __('messages.record_deleted', ['module' => 'Team member']));
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('team_members', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        if ($request->ajax()) 
        {
            $teamMembers = TeamMember::with('manager')->latest()->get();

            return DataTables::of($teamMembers)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return (isset($row->name)) ? truncateWords($row->name, 30) : '';
                })
                ->addColumn('designation', function ($row) {
                    return (isset($row->designation)) ? truncateWords($row->designation, 15) : '';
                })
                ->addColumn('manager', function ($row) {
                    return (isset($row->manager)) ? $row->manager->name : '';
                })
                ->addColumn('order', function ($row) {
                    return (isset($row->order)) ? $row->order : '';
                })
                ->addColumn('image', function ($row) {
                    return (isset($row->image)) ? '<img src="'. $row->image .'" height="100" width="100" />' : 'No image is selected';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('team_members', 'edit')) {
                        $options .= ' <a href="'. route('admin.team-members.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('team_members', 'delete')) {
                        $options .= ' <form action="'. route('admin.team-members.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    private function validateRequest($teamMember = null)
    {
        $rules = [
            'name' => 'required|string|min:3',
            'designation' => 'required|string',
            'description' => 'required|string',
            'order' => 'required|string',
            'image' => 'sometimes|file|mimes:'. str_replace("|", ",", config('settings.image_file_extensions')) .'|max:' . config('settings.maxImageSize'),
            'manager_id' => 'required|string'
        ];

        $request = request();

        if (! $request->has('image')) {
            unset($rules['image']);
        }

        return request()->validate($rules, [
            'image.max' => __('messages.max_file', ['limit' => '2 MB']),
        ], [
            'manager_id' => 'manager'
        ]);
    }

    public function deleteImage(Request $request){

        if ($request->ajax()) {

            if (isset($request->id)) {
                $teamMember = TeamMember::find($request->id);

                if (removeFile(TeamMember::STORAGE_DIRECTORY, $teamMember->image)) {
                    $teamMember->update(['image' => '']);
                }

                return response()->json(['success' => 'true', 'message' => __('messages.image_deleted')], 200);
            }
        }
    }
}
