<?php

namespace App\Http\Controllers;

use App\Models\ContactPageQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContactPageQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission("contact-page-queries", "list")) {
            return abort(403);
        }

        return view("admin.contact-page-queries.index");
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
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPageQuery $contactPageQuery)
    {
        if (!hasPermission('contact-page-queries', 'view')) {
            return abort(403);
        }

        return view("admin.contact-page-queries.show", compact('contactPageQuery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPageQuery $contactPageQuery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactPageQuery $contactPageQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPageQuery $contactPageQuery)
    {
        if (!hasPermission('contact-page-queries', 'delete')) {
            return abort(403);
        }

        $contactPageQuery->delete();
        return redirect()->route('admin.contact-page-queries.index')->with('success', 'Query Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if (!hasPermission('contact-page-queries', 'list')) {
            return abort(403);
        }

        if ($request->ajax()) {
            $data = ContactPageQuery::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 20);
                })  
                ->addColumn('email', function ($row) {
                    return ($row->email) ? $row->email : '';
                })
                ->addColumn('subject', function ($row) {
                    return truncateWords($row->subject, 25);
                })
                ->addColumn('message', function ($row) {
                    return truncateWords($row->message, 25);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if (hasPermission('contact-page-queries', 'edit')) {
                        $options .= '<a href="' . route('admin.contact-page-queries.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('contact-page-queries', 'view')) {
                        $options .= '<a href="' . route('admin.contact-page-queries.show', $row->id) . '" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }
                    if (hasPermission('contact-page-queries', 'delete')) {
                        $options .= ' <form action="'. route('admin.contact-page-queries.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($request) {

        return $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email',
            'subject' => 'required|min:5|max:100',
            'message' => 'required|min:5|max:255',
        ]);
    }
}
