<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("jobs", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("jobs", "create"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("jobs", "create"), 401, __('messages.unauthorized_action'));

        $job = new Job();
        $job = $this->validateRequest($job);
        $job['slug'] = Str::slug($job['title']);
        $job['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $job['start_datetime'] = $this->parseDate($request->start_datetime);
        $job['end_datetime'] = $this->parseDate($request->end_datetime);
        $job['image'] = null;
        
        $job = Job::create($job);
        
        if ($request->hasFile('image')) {
            $images = $request->file('image');
            $filenames = "";
            foreach ($images as $file) {
                $name = storeFile(Job::STORAGE_DIRECTORY, $file);
                $filenames .= $name . ',';
            }
            $job->update(['image' => trim($filenames, ",")]);
        }

        if ($request->action === "Published") {
            $job->update(['published_at' => now()]);
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
        // abort_if(!hasPermission("jobs", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("jobs", "edit"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("jobs", "edit"), 401, __('messages.unauthorized_action'));

        $previousImage = $job->image;
        $data = $this->validateRequest($job);
        $data['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $data['slug'] = Str::slug($data['title']);
        $data['start_datetime'] = $this->parseDate($request->start_datetime);
        $data['end_datetime'] = $this->parseDate($request->end_datetime);

        $data['image'] = $this->handleFileUpload($job, $request);

        $job->update($data);

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
        abort_if(!hasPermission("jobs", "delete"), 401, __('messages.unauthorized_action'));

        if ($job->image !== null) {
            foreach ($job->image as $image) {
                removeFile(Job::STORAGE_DIRECTORY, $image);
            }
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
        abort_if(!hasPermission("jobs", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Job::latest()->get();

            return DataTables::of($data)
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
                ->addColumn('status', function ($row) {
                    return $row->isPublished() ? 'Published' : 'Draft';
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

        abort_if(!hasPermission("jobs", "view_applications"), 401, __('messages.unauthorized_action'));

        return view('admin.applications.index',compact('job'));
    }

    public function getApplicationsList(Request $request, Job $job) {

        abort_if(!hasPermission("jobs", "view_applications"), 401, __('messages.unauthorized_action'));

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

    public function exportApplicationsList(Request $request, Job $job) {

        abort_if(!hasPermission("jobs", "export_applications"), 401, __('messages.unauthorized_action'));
        
        $data = $job->applications;
        
        $fileName = $job->title . '.csv';
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
            'specialization' => 'required|string',
            'salary' => 'nullable',
            'image.*' => 'sometimes',
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'active' => 'nullable',
            'enable' => 'nullable|boolean',
            'created_by' => '',
            'modified_by' => ''
        ], [
            "image.*.max" => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    private function handleFileUpload($job, $request)
    {
        $oldFiles = implode(",", $job->image);
        $filenames = "";

        if ($request->get('removeFile') !== null)
        {
            $removedFiles = explode(",", $request->get('removeFile'));
            foreach ($removedFiles as $file) {
                removeFile(Job::STORAGE_DIRECTORY, $file);
                $oldFiles = str_replace($file, "", $oldFiles);
                $oldFiles = str_replace(",,", ",", $oldFiles);
                $oldFiles = trim($oldFiles, ",");
            }
        }

        if ($request->hasFile('image'))
        {
            $oldFiles = explode(",", $oldFiles);
            foreach ($oldFiles as $file) {
                removeFile(Job::STORAGE_DIRECTORY, $file);
            }

            $uploadedFiles = $request->file('image');

            if (count($uploadedFiles) > 0) {
                foreach ($uploadedFiles as $file) {
                    $filename = storeFile(Job::STORAGE_DIRECTORY, $file);
                    $filenames .= $filename . ",";
                }

                $filenames = trim($filenames, ",");
            }
        } else {
            $filenames = trim($oldFiles, ",");
        }

        return $filenames;
    }

    public function deleteImage(Request $request) {
        if ($request->ajax()) {
            if (isset($request->job_id)) {
                $job = Job::find($request->job_id);
                if ($job && removeFile(Job::STORAGE_DIRECTORY, $job->image)) {
                    $job->update(['image' => null]);
                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }
        }
    }

    private function parseDate($date) {
        if ($date) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $date))));
        }
        return null;
    }
}
