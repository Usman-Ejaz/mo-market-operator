<?php

namespace App\Http\Controllers;

use App\Mail\ClientApprovedMail;
use App\Models\Client;
use App\Models\ClientAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use ZipArchive;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("clients", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        abort_if(!hasPermission("clients", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        abort_if(!hasPermission("clients", "delete"), 401, __('messages.unauthorized_action'));

        $_client = $client;

        $client->removeDetails();
        $client->removeAttachments();
        // $this->removeSignatures($client);
        $client->delete();

        // event(new ClientHasDeletedEvent($_client));

        return redirect()->route('admin.clients.index')->with('success', __('messages.record_deleted', ['module' => 'Client']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("clients", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Client::latest()->get();

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return (isset($row->name)) ? truncateWords($row->name, 20) : '';
                })
                ->addColumn('email', function ($row) {
                    $primaryDetails = $row->primaryDetails();
                    return (isset($primaryDetails->email)) ? $primaryDetails->email : '';
                })
                ->addColumn('type', function ($row) {
                    return (isset($row->type)) ? __("client.registration_types.{$row->type}") : '';
                })
                ->addColumn('status', function ($row) {
                    return $row->status();
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';

                    if (hasPermission('clients', 'view')) {
                        $options .= '<a href="' . route('admin.clients.show', $row->id) . '" class="btn btn-primary" title="View">
                        <i class="fas fa-eye"></i>
                    </a>';
                    }

                    if (hasPermission('clients', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="' . route('admin.clients.destroy', $row->id) . '" title="Delete">
                            <i class="fas fa-trash" data-action="' . route('admin.clients.destroy', $row->id) . '"></i>
                    </button>';
                    }

                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function downloadBulkFiles(Client $client, $category)
    {
        $zip = new ZipArchive();
        if ($category == '0') {
            $outputFilename = 'General Attachments';
        } else {
            $outputFilename = __('client.categories.' . $client->type . '.' . Client::REGISTER_CATEGORIES[$category]) . ' Attachments';
        }

        $filename = config('settings.storage_disk_base_path') . ucfirst($category) . '.zip';

        if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $category = $category == '0' ? null : $category;

            $files = ClientAttachment::where(['client_id' => $client->id, 'category_id' => $category])->select('file')->get();

            foreach ($files as $attachment) {
                $filePath = config('settings.storage_disk_base_path') . ClientAttachment::DIR . basename($attachment->file);
                $zip->addFile($filePath, pathinfo($filePath, PATHINFO_BASENAME));
            }

            $zip->close();

            return response()->download($filename, $outputFilename . '.zip');
        }
    }

    public function approve(Client $client)
    {
        $password = Str::random(16);
        $client->update(['approved' => 1, 'password' => bcrypt($password)]);



        Mail::to($client->email)->queue(new ClientApprovedMail($client->email, $password));

        request()->session()->flash('success', __('Client has been approved'));
        return redirect()->route('admin.clients.show', ['client' => $client->id]);
    }
}
