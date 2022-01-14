<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $job = new Job();
        $job = $this->validateRequest($job);
        $job['enable'] = ($request->get('enable') == null) ? '0' : request('enable');
        $job = Job::create($job);
        $this->storeImage($job);

        $request->session()->flash('alert-success', 'Job was successful added!');
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
        $data = $this->validateRequest($job);
        $data['enable'] = ($request->get('enable') == null) ? '0' : request('enable');

        $job->update($data);
        $this->storeImage($job);

        $request->session()->flash('alert-success', 'job was successful updated!');
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
        $job->delete();
        return redirect()->route('admin.jobs.index');
    }

    /**
     * Get all jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
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
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.job.applications',$row->id) .'" class="btn btn-secondary" title="applications">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="'. route('admin.jobs.edit',$row->id) .'" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.jobs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])                
                ->make(true);
        }
    }

    public function getJobApplications (Job $job) {
        return view('admin.applications.index',compact('job'));
    }

    public function getApplicationsList(Request $request,Job $job) {
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
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.job.application.detail',$row->id) .'" class="btn btn-primary" title="detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="'. route('admin.job.application.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])                
                ->make(true);
        }
    }

    public function exportApplicationsList(Request $request,Job $job) {
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
            'start_datetime' => 'nullable|date_format:d/m/Y h:i A',
            'end_datetime' => 'nullable|date_format:d/m/Y h:i A',
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
            $job->update([
                'image' => request()->image->store('uploads', 'public')
            ]);
        }
    }

    public function deleteImage(Request $request){
        if ($request->ajax()) {
            if( isset($request->product_id) ){
                $job = Job::find($request->product_id);

                if( Storage::disk('public')->delete($job->image) ){
                    $job->image = null;
                    $job->update();

                    return response()->json(['success' => 'true', 'message' => 'image deleted successfully'], 200);
                }
            }

        }

    }

}
