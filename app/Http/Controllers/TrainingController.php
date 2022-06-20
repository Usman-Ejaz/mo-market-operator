<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('trainings', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.trainings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! hasPermission('trainings', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $training = new Training;
        return view('admin.trainings.create', compact('training'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        abort_if(! hasPermission('trainings', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));
        $training = new Training;
        
        $data = $this->validateRequest($training);
        $data['slug'] = str_slug($data['title']);
        
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $filenames = "";
            foreach ($attachments as $file) {
                $name = storeFile(Training::STORAGE_DIRECTORY, $file);
                $filenames .= $name . ',';
            }
            $data['attachment'] = trim($filenames, ",");
        }

        unset($data['attachments']);

        Training::create($data);

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Training']));

        return redirect()->route('admin.trainings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Training $training)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Training $training)
    {
        abort_if(! hasPermission('trainings', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.trainings.edit', compact('training'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Training $training)
    {
        abort_if(! hasPermission('trainings', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        $data = $this->validateRequest($training);

        $data['attachment'] = $this->handleFileUpload($training, $request);
        unset($data['attachments']);

        $training->update($data);
        
        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Training']));

        return redirect()->route('admin.trainings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function destroy(Training $training)
    {
        abort_if(! hasPermission('trainings', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $training->removeAttachments();
        $training->delete();

        return redirect()->route('admin.trainings.index')->with('success', __('messages.record_deleted', ['module' => 'Training']));
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('trainings', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        if ($request->ajax()) 
        {
            $training = Training::latest()->get();

            return DataTables::of($training)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return (isset($row->title)) ? truncateWords($row->title, 30) : '';
                })
                ->addColumn('location', function ($row) {
                    return (isset($row->location)) ? truncateWords($row->location, 20) : '';
                })
                ->addColumn('status', function ($row) {
                    return (isset($row->status)) ? $row->status() : '';
                })
                ->addColumn('topics', function ($row) {
                    return (isset($row->topics)) ? truncateWords($row->topics, 15) : '';
                })
                ->addColumn('target_audience', function ($row) {
                    return (isset($row->target_audience)) ? truncateWords($row->target_audience, 15) : '';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('trainings', 'edit')) {
                        $options .= ' <a href="'. route('admin.trainings.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('trainings', 'delete')) {
                        $options .= ' <form action="'. route('admin.trainings.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($training)
    {
        $rules = [
            'title' => 'required|string|min:3|unique:trainings,title,' . $training->id,
            'short_description' => 'nullable',
            'description' => 'nullable',
            'location' => 'required|string',
            'topics' => 'required|string',
            'target_audience' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
            'attachments.*' => 'sometimes|file|mimes:doc,docx,pdf|max:' . config('settings.maxDocumentSize')
        ];

        return request()->validate($rules, [
            'attachments.*.max' => __('messages.max_file', ['limit' => '5 MB']),
        ]);
    }

    private function handleFileUpload($training, $request)
    {
        $filenames = implode(",", $training->attachment);
        
        if ($request->hasFile('attachments'))
        {
            $uploadedFiles = $request->file('attachments');

            if (count($uploadedFiles) > 0) {
                $filenames = $filenames . ',';
                foreach ($uploadedFiles as $file) {
                    $filename = storeFile(Training::STORAGE_DIRECTORY, $file);
                    $filenames .= $filename . ",";
                }

                $filenames = trim($filenames, ",");
            }
        }

        if ($request->get('removeFile') !== null)
        {
            $removedFiles = explode(",", $request->get('removeFile'));
            foreach ($removedFiles as $file) {
                removeFile(Training::STORAGE_DIRECTORY, $file);
                $filenames = str_replace($file, "", $filenames);
                $filenames = str_replace(",,", ",", $filenames);
                $filenames = trim($filenames, ",");
            }
        }

        return $filenames;
    }
}
