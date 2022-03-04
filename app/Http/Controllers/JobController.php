<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !hasPermission('jobs', 'list') ){
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
        if( !hasPermission('jobs', 'create') ){
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
        if( !hasPermission('jobs', 'create') ){
            return abort(403);
        }

        $job = new Job();
        $job = $this->validateRequest($job);
        $job['slug'] = Str::slug($job['title']);
        $job['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $job = Job::create($job);
        $this->storeImage($job);

        if ($request->action === "Published") {
            $job->published_at = now();
            $job->save();
        }

        $request->session()->flash('success', "Job {$request->action} Successfully!");
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
        if( !hasPermission('jobs', 'view') ){
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
        if( !hasPermission('jobs', 'edit') ){
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
        if( !hasPermission('jobs', 'edit') ){
            return abort(403);
        }
        $previousImage = $job->image;
        $data = $this->validateRequest($job);
        $data['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $data['slug'] = Str::slug($data['title']);
        $job->update($data);
        $this->storeImage($job, $previousImage);

        if ($request->action === "Unpublished") {
            $job->published_at = null;
            $job->save();
        } else if ($request->action === "Published") {
            $job->published_at = now();
            $job->save();
        }

        $request->session()->flash('success', "Job {$request->action} Successfully!");
        return redirect()->route('admin.jobs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if( !hasPermission('jobs', 'delete') ){
            return abort(403);
        }
        if ($job->image !== null) {
            $file_path = public_path(config('filepaths.jobImagePath.public_path')) . basename($job->image);
            unlink($file_path);
        }

        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job Deleted Successfully!');
    }

    /**
     * Get all jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        if( !hasPermission('jobs', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Job::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return truncateWords($row->title, 30);
                })
                ->addColumn('location', function ($row) {
                    return truncateWords($row->location, 25);
                })
                ->addColumn('applications', function ($row) {
                    return $row->applications->count();
                })
                ->addColumn('experience', function ($row) {
                    return truncateWords($row->experience, 15);
                })
                ->addColumn('total_positions', function ($row) {
                    return ($row->total_positions) ? $row->total_positions : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission('jobs', 'view_applications') ) {
                        $options .= '<a href="' . route('admin.job.applications', $row->id) . '" class="btn btn-primary" title="Applications">
                            <i class="fas fa-print"></i>
                        </a>';
                    }
                    if( hasPermission('jobs', 'edit') ) {
                        $options .= '<a href="' . route('admin.jobs.edit', $row->id) . '" class="btn btn-primary" title="Edit" style="margin-left: 3px;">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('jobs', 'delete') ) {
                        $options .= ' <form action="'. route('admin.jobs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    public function getJobApplications (Job $job) {

        if (!hasPermission('jobs', 'view_applications')) {
            return abort(403);
        }

        return view('admin.applications.index',compact('job'));
    }

    public function getApplicationsList(Request $request,Job $job) {

        if (!hasPermission('jobs', 'view_applications')) {
            return abort(403);
        }

        $job = Job::find($job->id);
        $data = $job->applications;
        if ($request->ajax()) {

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 30);
                })
                ->addColumn('email', function ($row) {
                    return truncateWords($row->email, 30);
                })
                ->addColumn('gender', function ($row) {
                    return truncateWords($row->gender, 10);
                })
                ->addColumn('phone', function ($row) {
                    return truncateWords($row->phone, 20);
                })
                ->addColumn('city', function ($row) {
                    return truncateWords($row->city, 25);
                })
                ->addColumn('experience', function ($row) {
                    return truncateWords($row->experience, 10);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('jobs', 'view_job_application')) {
                        $options .= '<a href="' . route('admin.job.application.detail', $row->id) . '" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }
                    if (hasPermission('jobs', 'delete_job_application')) {
                        $options .= ' <form action="'. route('admin.job.application.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    public function exportApplicationsList(Request $request,Job $job) {

        if (!hasPermission('jobs', 'export_applications')) {
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
                $row['id'] = $application->id;
                $row['name'] = $application->name;
                $row['email'] = $application->email;
                $row['gender'] = $application->gender;
                $row['phone'] = $application->phone;
                $row['address'] = $application->address;
                $row['city'] = $application->city;
                $row['experience'] = $application->experience;
                $row['degree_level'] = $application->degree_level;
                $row['degree_title'] = $application->degree_title;
                $row['created_at'] = $application->created_at;

                fputcsv($file, array($row['id'], $row['name'], $row['email'], $row['gender'], $row['phone'], $row['address'], $row['city'], $row['experience'], $row['degree_level'], $row['degree_title'], $row['created_at']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function validateRequest($job){
        
        return request()->validate([
            'title' => 'required|min:3',
            'description' => 'required',
            'location' => 'required',
            'qualification' => 'required',
            'experience' => 'required',
            'total_positions' => 'required',
            'image' => 'sometimes|file|image|max:2000',
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'active' => 'nullable',
            'enable' => 'nullable|boolean',
            'created_by' => '',
            'modified_by' => ''
        ], [
            "image.max" => __('messages.max_image', ['limit' => '2 MB']),
        ]);
    }


    private function storeImage($job, $previousImage = null){

        if(request()->has('image')){

            // remove previous file
            if ($previousImage !== null) {
                $file_path = public_path(config('filepaths.jobImagePath.public_path')) . basename($previousImage);
                unlink($file_path);
            }

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
                $image_path = public_path(config('filepaths.jobImagePath.public_path')) . basename($job->image);
                if( unlink($image_path) ){
                    $job->image = null;
                    $job->update();

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }

        }

    }

}
