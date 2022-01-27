<?php

namespace App\Http\Controllers;

use App\Models\Job;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->role->hasPermission('jobs', 'list') ){
            return abort(403);
        }

        return view('admin.jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('jobs', 'create') ){
            return abort(403);
        }

        $job = new Job();
        return view('admin.jobs.create', compact('job'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('jobs', 'create') ){
            return abort(403);
        }

        $job = new Job();
        $job = $this->validateRequest($job);
        $job['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $job = Job::create($job);
        $this->storeImage($job);

        $request->session()->flash('success', 'Job was successfully added!');
        return redirect()->route('admin.jobs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        if( !Auth::user()->role->hasPermission('jobs', 'view') ){
            return abort(403);
        }

        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        if( !Auth::user()->role->hasPermission('jobs', 'edit') ){
            return abort(403);
        }

        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {      
        if( !Auth::user()->role->hasPermission('jobs', 'edit') ){
            return abort(403);
        }

        if (request()->has('image')) {
            $file_path = config('filepaths.jobImagePath.public_path').$job->image; 
            unlink($file_path);
        }

        $data = $this->validateRequest($job);
        $data['enable'] = ($request->get('enable') == null) ? '0' : request('enable');

        $job->update($data);
        $this->storeImage($job);

        $request->session()->flash('success', 'job was successfully updated!');
        return redirect()->route('admin.jobs.edit', $job->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if( !Auth::user()->role->hasPermission('jobs', 'delete') ){
            return abort(403);
        }

        $file_path = config('filepaths.jobImagePath.public_path').$job->image;
        unlink($file_path);

        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job was successfully deleted!');
    }

    /**
     * Get all jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('jobs', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Job::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return ($row->title) ? ( (strlen($row->title) > 30) ? substr($row->title,0,30).'...' : $row->title ) : '';
                })
                ->addColumn('location', function ($row) {
                    return ($row->location) ? ( (strlen($row->location) > 25) ? substr($row->location,0,25).'...' : $row->location ) : '';
                })
                ->addColumn('applications', function ($row) {
                    return $row->applications->count();
                })
                ->addColumn('experience', function ($row) {
                    return ($row->experience) ? ( (strlen($row->experience) > 15) ? substr($row->experience,0,15).'...' : $row->experience ) : '';
                })
                ->addColumn('total_positions', function ($row) {
                    return ($row->total_positions) ? $row->total_positions : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('jobs', 'view_applications') ) {
                        $options .= '<a href="' . route('admin.job.applications', $row->id) . '" class="btn btn-primary" title="applications">
                            <i class="fas fa-print"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('jobs', 'edit') ) {
                        $options .= '<a href="' . route('admin.jobs.edit', $row->id) . '" class="btn btn-primary" title="edit" style="margin-left: 3px;">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('jobs', 'delete') ) {
                        $options .= ' <form action="'. route('admin.jobs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
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

    public function getJobApplications (Job $job) {

        if( !Auth::user()->role->hasPermission('jobs', 'view_applications') ){
            return abort(403);
        }

        return view('admin.applications.index',compact('job'));
    }

    public function getApplicationsList(Request $request,Job $job) {

        if( !Auth::user()->role->hasPermission('jobs', 'view_applications') ){
            return abort(403);
        }

        $job = Job::find($job->id);
        $data = $job->applications;
        if ($request->ajax()) {

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ($row->name) ? ( (strlen($row->name) > 30) ? substr($row->name,0,30).'...' : $row->name ) : '';
                })
                ->addColumn('email', function ($row) {
                    return ($row->email) ? ( (strlen($row->email) > 30) ? substr($row->email,0,30).'...' : $row->email ) : '';
                })
                ->addColumn('gender', function ($row) {
                    return ($row->gender) ? ( (strlen($row->gender) > 10) ? substr($row->gender,0,10).'...' : $row->gender ) : '';
                })
                ->addColumn('phone', function ($row) {
                    return ($row->phone) ? ( (strlen($row->phone) > 20) ? substr($row->phone,0,20).'...' : $row->phone ) : '';
                })
                ->addColumn('city', function ($row) {
                    return ($row->city) ? ( (strlen($row->city) > 25) ? substr($row->city,0,25).'...' : $row->city ) : '';
                })
                ->addColumn('experience', function ($row) {
                    return ($row->experience) ? ( (strlen($row->experience) > 10) ? substr($row->experience,0,10).'...' : $row->experience ) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                        $options = '';
                        if( Auth::user()->role->hasPermission('applications', 'view') ) {
                            $options .= '<a href="' . route('admin.job.application.detail', $row->id) . '" class="btn btn-primary" title="edit">
                                <i class="fas fa-eye"></i>
                            </a>';
                        }
                        if( Auth::user()->role->hasPermission('applications', 'delete') ) {
                            $options .= ' <form action="'. route('admin.job.application.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                '.method_field("DELETE").'
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

    public function exportApplicationsList(Request $request,Job $job) {

        if( !Auth::user()->role->hasPermission('jobs', 'export_applications') ){
            return abort(403);
        }

        $job = Job::find($job->id);
        $data = $job->applications;
        
        $fileName = $job->title.'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Application ID' ,'Name', 'Email', 'Gender', 'Phone', 'Address', 'City', 'Experience', 'Degree Level', 'Degree Title','Created Date');

        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $application) {
                $row['id']  = $application->id;
                $row['name']  = $application->name;
                $row['email']    = $application->email;
                $row['gender']    = $application->gender;
                $row['phone']  = $application->phone;
                $row['address']  = $application->address;
                $row['city']  = $application->city;
                $row['experience']    = $application->experience;
                $row['degree_level']    = $application->degree_level;
                $row['degree_title']  = $application->degree_title;
                $row['created_at']  = $application->created_at;

                fputcsv($file, array($row['id'], $row['name'], $row['email'], $row['gender'], $row['phone'], $row['address'], $row['city'], $row['experience'], $row['degree_level'], $row['degree_title'], $row['created_at']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function validateRequest($job){
        
        return tap( request()->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:10',
            'location' => 'required',
            'qualification' => 'required',
            'experience' => 'required',
            'total_positions' => 'required',
            'image' => 'nullable',
            'start_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
            'end_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
            'active' => 'nullable',
            'enable' => 'nullable|boolean',
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


    private function storeImage($job){

        if(request()->has('image')){
            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.jobImagePath.internal_path'), $file_name);

            $job->update([
                'image' => $file_name,
            ]);
        }
    }

    public function deleteImage(Request $request){
        if ($request->ajax()) {
            if( isset($request->job_id) ){
                $job = Job::find($request->job_id);
                $image_path = config('filepaths.jobImagePath.public_path').$job->image;
                if( unlink($image_path) ){
                    $job->image = null;
                    $job->update();

                    return response()->json(['success' => 'true', 'message' => 'image deleted successfully'], 200);
                }
            }

        }

    }

}
