<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Carbon\Carbon;
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

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Manager']));

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
        
        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Manager']));

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
        return redirect()->route('admin.managers.index')->with('success', __('messages.record_deleted', ['module' => 'Manager']));
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
                    return (isset($row->name)) ? truncateWords($row->name, 30) : '';
                })
                ->addColumn('designation', function ($row) {
                    return (isset($row->designation)) ? truncateWords($row->designation, 15) : '';
                })
                ->addColumn('order', function ($row) {
                    return (isset($row->order)) ? $row->order : '';
                })
                ->addColumn('image', function ($row) {
                    return (isset($row->image)) ? '<img src="'. $row->image .'" height="100" width="100" />' : 'No image is selected';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
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
                                onclick="return confirm(\''. __('messages.record_delete') .'\')" title="Delete">
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
            'image' => 'sometimes|file|mimes:'. str_replace("|", ",", config('settings.image_file_extensions')) .'|max:' . config('settings.maxImageSize'),
        ];

        if ($manager && $manager->image !== "" && $manager->image !== null) {
            unset($rules['image']);
        }

        return request()->validate($rules, [
            'image.max' => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    public function deleteImage(Request $request){

        if ($request->ajax()) {

            if (isset($request->id)) {
                $manager = Manager::find($request->id);

                if (removeFile(Manager::STORAGE_DIRECTORY, $manager->image)) {
                    $manager->update(['image' => '']);
                }

                return response()->json(['success' => 'true', 'message' => __('messages.image_deleted', ['module' => 'Manager'])], 200);
            }
        }
    }
}
