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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Client  $client
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        abort_if(! hasPermission("clients", "delete"), 401, __('messages.unauthorized_action'));

        $_client = $client;
        // $this->removeSignatures($client);
        $client->delete();

        // event(new ClientHasDeletedEvent($_client));

        return redirect()->back()->with('success', 'Client deleted successfully!');
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission("clients", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Client::query();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return ( isset($row->name)) ? $row->name : '';
            })
            ->addColumn('pri_email', function ($row) {
                return ( isset($row->pri_email)) ? $row->pri_email : '';
            })
            ->addColumn('type', function ($row) {
                return ( isset($row->type)) ? ucwords(str_replace("_", " ", $row->type)) : '';
            })
            ->addColumn('status', function ($row) {
                return $row->status();
            })
            ->orderColumn('created_at', 'created_at $1')
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
