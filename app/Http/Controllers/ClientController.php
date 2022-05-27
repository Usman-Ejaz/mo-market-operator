<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission("clients", "list"), 401, __('messages.unauthorized_action'));

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
        abort_if(! hasPermission("clients", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(! hasPermission("clients", "delete"), 401, __('messages.unauthorized_action'));

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
        abort_if(! hasPermission("clients", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Client::latest()->get();

            return DataTables::of($data)
            ->addColumn('name', function ($row) {
                return ( isset($row->name)) ? $row->name : '';
            })
            ->addColumn('email', function ($row) {
                $primaryDetails = $row->primaryDetails();
                return ( isset($primaryDetails->email)) ? $primaryDetails->email : '';
            })
            ->addColumn('type', function ($row) {
                return ( isset($row->type)) ? __("client.registration_types.{$row->type}") : '';
            })
            ->addColumn('status', function ($row) {
                return $row->status();
            })
            ->addColumn('created_at', function ($row) {
                return ($row->created_at) ? $row->created_at : '';
            })
            ->addColumn('action', function ($row) {
                $options = '';

                if (hasPermission('clients', 'view')) {
                    $options .= '<a href="' . route('admin.clients.show', $row->id) . '" class="btn btn-primary" title="View">
                        <i class="fas fa-eye"></i>
                    </a>';
                }

                if (hasPermission('clients', 'delete')) {
                    $options .= ' <form action="'. route('admin.clients.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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
}
