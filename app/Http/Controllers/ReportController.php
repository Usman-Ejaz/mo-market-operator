<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAttachmentToReportRequest;
use App\Http\Requests\AddReportRequest;
use App\Http\Requests\RemoveAttachmentFromReport;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("reports", "list"), 401, __('messages.unauthorized_action'));
        return view('admin.reports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("reports", "create"), 401, __('messages.unauthorized_action'));
        $categories = ReportCategory::with(['subCategories'])->get();
        return view('admin.reports.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddReportRequest $request)
    {
        abort_if(!hasPermission("reports", "create"), 401, __('messages.unauthorized_action'));
        /** @var Report $report */
        $report = Report::create([
            'name' => $request->name,
            'publish_date' => $request->publish_date,
            'report_sub_category_id' => $request->sub_category_id,
        ]);

        $attributes = collect($request->report_attributes)->mapWithKeys(function ($value, $attID) {
            return [$attID => ['value' => $value]];
        })->toArray();
        // dd($attributes);
        $report->filledAttributes()->attach($attributes);

        $this->storeFileAttachments($report, $request->file_attachments);
        // if ($request->attachment_files) {
        //     $this->storeFiles($report, $request->attachment_files);
        // }
        $request->session()->flash('success', "Successfully created a new report.");
        return redirect()->route('admin.reports.index');
    }

    /** @param UploadedFile[] $files */
    private function storeFileAttachments(Report $report, iterable $files)
    {
        $filesToStore = collect($files)->mapWithKeys(function (UploadedFile $file, $attID) {
            $fileStoredName = storeFile(ReportAttachment::STORAGE_DIRECTORY, $file);
            return [
                $attID => [
                    'value' => config('app.url') . '/storage/uploads/' . ReportAttachment::STORAGE_DIRECTORY . $fileStoredName,
                ]
            ];
        });

        $report->filledAttributes()->syncWithoutDetaching($filesToStore);
    }

    // private function storeFiles(Report $report, iterable $files)
    // {
    //     // dd($files->all()['files']);
    //     $filesToStore = collect($files)->map(function (iterable $file) {
    //         $fileStoredName = storeFile(ReportAttachment::STORAGE_DIRECTORY, $file['file']);
    //         return [
    //             'name' => $file['name'],
    //             'file_path' => config('app.url') . '/storage/uploads/' . ReportAttachment::STORAGE_DIRECTORY . $fileStoredName,
    //         ];
    //     });

    //     $report->attachments()->createMany($filesToStore);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!hasPermission("reports", "edit"), 401, __('messages.unauthorized_action'));
        $report = Report::with(['subCategory' => function ($q) {
            return $q->with('category');
        }, 'attachments', 'filledAttributes' => function ($q) {
            return $q->with('type');
        }])->findOrFail($id);

        return view('admin.reports.edit', ['report' => $report]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReportRequest $request, $id)
    {
        abort_if(!hasPermission("reports", "edit"), 401, __('messages.unauthorized_action'));
        /** @var Report $report */
        $report = Report::findOrFail($id);
        $report->update($request->only(['name', 'publish_date']));

        $attributes = collect($request->report_attributes)->mapWithKeys(function ($value, $attID) {
            return [$attID => ['value' => $value]];
        })->toArray();

        $report->filledAttributes()->syncWithoutDetaching($attributes);

        if ($request->has('file_attachments')) {
            $this->storeFileAttachments($report, $request->file_attachments);
        }

        $request->session()->flash('success', "Successfully updated post data.");

        return redirect()->route('admin.reports.edit', [$report->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!hasPermission("reports", "delete"), 401, __('messages.unauthorized_action'));
        Report::findOrFail($id)->delete();
        return redirect()->route('admin.reports.index');
    }

    public function list()
    {
        abort_if(!hasPermission("reports", "list"), 401, __('messages.unauthorized_action'));
        if (request()->ajax()) {
            $reports = Report::with(['subCategory.category'])->get();
            return DataTables::of($reports)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 27);
                })
                ->addColumn('category/sub_category', function ($row) {
                    return $row->subCategory->category->name . '/' . $row->subCategory->name;
                })
                ->addColumn('publish_date', function ($row) {
                    return Carbon::parse($row->publish_date)->format('d-m-Y');
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $options = "<a href='" . route('admin.reports.edit', ['report' => $row->id]) . "' title='Edit' class='btn btn-primary'>
                    <i class='fas fa-pencil-alt'></i>
                </a>
                " .
                        '<form method="POST" action="' . route('admin.reports.destroy', ['report' => $row->id]) . '" style="display:inline">' .
                        method_field('DELETE') .
                        csrf_field() .
                        '<button type="submit" class="btn btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);;
        }
    }

    public function getSubCategories($categoryID)
    {
        abort_if(!hasPermission("reports", "create"), 401, __('messages.unauthorized_action'));
        return ReportCategory::with('subCategories')->findOrFail($categoryID)->subCategories;
    }

    public function getAttributes($subCategoryID)
    {
        abort_if(!hasPermission("reports", "create"), 401, __('messages.unauthorized_action'));
        return ReportSubCategory::with(['attributes' => function ($q) {
            return $q->orderBy('report_attributes.id', 'desc');
        }, 'attributes.type'])->findOrFail($subCategoryID)->attributes;
    }

    public function addAttachment(AddAttachmentToReportRequest $request, $id)
    {
        abort_if(!hasPermission("reports", "edit"), 401, __('messages.unauthorized_action'));
        /** @var Report $report */
        $report = Report::findOrFail($id);

        $uploadedFile = $request->file('file');
        $this->storeFiles($report, [['name' => $request->name, 'file' => $uploadedFile]]);
        $request->session()->flash('success', "Successfully stored attachment.");
        return redirect()->route('admin.reports.edit', $id);
    }

    public function removeAttachment($reportID, $attachmentID, RemoveAttachmentFromReport $request)
    {
        abort_if(!hasPermission("reports", "edit"), 401, __('messages.unauthorized_action'));
        $report = Report::with(['attachments' => function ($q) use (&$attachmentID) {
            return $q->where('id', $attachmentID);
        }])->findOrFail($reportID);

        if ($report->attachments->count() > 0) {
            /** @var ReportAttachment $attachment */
            $attachment = $report->attachments[0];
            $fileName = Str::of($attachment->file_path)->explode('/')->last();
            removeFile('reports/', $fileName);
            $attachment->delete();
        }

        request()->session()->flash('success', 'Successfully removed attachment');
        return redirect()->route('admin.reports.edit', $reportID);
    }
}
