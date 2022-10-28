<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFileToMODataRequest;
use App\Http\Requests\RemoveFileFromMODataRequest;
use App\Http\Requests\UpdateMODataRequest;
use App\Models\MOData;
use App\Models\MODataFiles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;
use Ramsey\Uuid\Uuid;

class MODataController extends Controller
{
    public const ACCEPTED_FILES = ['xls', 'xlsx'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.mo-data.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($mODatum)
    {
        $data = MOData::with('files')->findOrFail($mODatum);
        return view('admin.mo-data.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($mODatum)
    {
        $relationsToInclude = ['files', 'extraAttributes'];
        $data = MOData::with($relationsToInclude)->withCount($relationsToInclude)->findOrFail($mODatum);
        return view('admin.mo-data.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMODataRequest $request, $id)
    {
        $moData = MOData::findOrFail($id);
        $moData->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->extra_attributes) {
            $this->updateExtraAttributes($moData, $request->extra_attributes);
        }

        return redirect()->route('admin.mo-data.index');
    }

    public function list()
    {
        if (request()->ajax()) {
            $moData = MOData::withCount('files')->get();
            return DataTables::of($moData)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return truncateWords($row->title, 27);
                })
                ->addColumn('files_count', function ($row) {
                    return $row->files_count;
                })
                ->addColumn('action', function ($row) {
                    $options = "<a href='" . route('admin.mo-data.show', ['mo_datum' => $row->id]) . "' title='Preview' target='_blank' class='btn btn-primary'>
                        <i class='fas fa-eye'></i>
                    </a>
                    <a href='" . route('admin.mo-data.edit', ['mo_datum' => $row->id]) . "' title='Preview' class='btn btn-primary'>
                        <i class='fas fa-pencil-alt'></i>
                    </a>
                    ";
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function addFile(AddFileToMODataRequest $request, $id)
    {
        /** @var MOData $data */
        $data = MOData::findOrFail($id);
        if ($request->file) {
            $filePath = storeFile(MODataFiles::STORAGE_DIRECTORY, $request->file('file'));
            $data->files()->create([
                'name' => $request->name,
                'file_path' => $filePath,
            ]);
        } else if ($request->link) {
            $ext = pathinfo($request->link, PATHINFO_EXTENSION);
            $acceptedExtensions = collect(static::ACCEPTED_FILES);

            if (!$acceptedExtensions->contains($ext)) {
                throw ValidationException::withMessages([
                    'link' => 'The file provided is not xls or xlsx.',
                ]);
            }

            $fileContents = file_get_contents($request->link);

            $fileName = Uuid::uuid4()->toString() . ".$ext";
            Storage::put("public/uploads/mo-data/$fileName", $fileContents);
            $data->files()->create([
                'name' => $request->name,
                'file_path' => $fileName,
            ]);
        }

        $request->session()->flash('success', "Successfully stored file.");
        return redirect()->route('admin.mo-data.edit', ['mo_datum' => $id]);
    }

    public function removeFile(RemoveFileFromMODataRequest $request, $moDatumID, $fileID)
    {
        /** @var MOData $moDatum */
        $moDatum = MOData::findOrFail($moDatumID);
        $file = $moDatum->files()->where('id', $fileID)->first();
        if ($file) {
            removeFile('mo-data', $file->file_path);
            $file->delete();
        }
        request()->session()->flash('success', 'Successfully removed file');
        return redirect()->route('admin.mo-data.edit', ['mo_datum' => $moDatumID]);
    }

    private function updateExtraAttributes(MOData $moDatum, array $attributeArray)
    {
        // $attributeCollection = collect($attributeArray);

        foreach ($attributeArray as $key => $attribute) {
            if ($attribute != null) {
                $moDatum->extraAttributes()->where('id', $key)->update([
                    'value' => $attribute,
                ]);
            }
        }
    }
}
