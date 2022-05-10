<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('our_teams', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.managers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! hasPermission('our_teams', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $manager = new Manager;
        return view('admin.managers.create', compact('manager'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(! hasPermission('our_teams', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));
        $data = $this->validateRequest();

        $data['image'] = '';

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(Manager::STORAGE_DIRECTORY, $request->file('image'));            
        }
        
        Manager::create($data);

        $request->session()->flash('success', 'Manager created successfully');

        return redirect()->route('admin.managers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        abort_if(! hasPermission('our_teams', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.managers.edit', compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $manager)
    {
        abort_if(! hasPermission('our_teams', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $data = $this->validateRequest($manager);

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(Manager::STORAGE_DIRECTORY, $request->file('image'), $manager->image);
        }

        $manager->update($data);
        
        $request->session()->flash('success', 'Manager updated successfully');

        return redirect()->route('admin.managers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
        abort_if(! hasPermission('our_teams', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $manager->removeImage();
        $manager->delete();
        return redirect()->route('admin.managers.index')->with('success', 'Manager deleted successfully!');
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('our_teams', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        if ($request->ajax()) 
        {
            $managers = Manager::latest()->get();

            return DataTables::of($managers)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return (isset($row->name)) ? $row->name : '';
                })
                ->addColumn('designation', function ($row) {
                    return (isset($row->designation)) ? $row->designation : '';
                })
                ->addColumn('description', function ($row) {
                    return (isset($row->description)) ? truncateWords($row->description, 20) : '';
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
                    if (hasPermission('our_teams', 'edit')) {
                        $options .= ' <a href="'. route('admin.managers.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('our_teams', 'delete')) {
                        $options .= ' <form action="'. route('admin.managers.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($manager = null)
    {
        $rules = [
            'name' => 'required|string|min:3',
            'designation' => 'required|string',
            'description' => 'required|string',
            'order' => 'required|string',
            'image' => 'sometimes|nullable|file|max:2000',
        ];

        if ($manager && $manager->image !== "" && $manager->image !== null) {
            unset($rules['image']);
        }

        return request()->validate($rules, [
            'image.max' => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }
}
