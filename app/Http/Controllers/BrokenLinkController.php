<?php

namespace App\Http\Controllers;

use App\Models\BrokenLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrokenLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('broken_links', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.broken-links.index');
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
     * @param  \App\Models\BrokenLink  $brokenLink
     * @return \Illuminate\Http\Response
     */
    public function show(BrokenLink $brokenLink)
    {
        abort_if(! hasPermission('broken_links', 'view'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.broken-links.show', compact('brokenLink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BrokenLink  $brokenLink
     * @return \Illuminate\Http\Response
     */
    public function edit(BrokenLink $brokenLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BrokenLink  $brokenLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BrokenLink $brokenLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BrokenLink  $brokenLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(BrokenLink $brokenLink)
    {
        abort_if(! hasPermission('broken_links', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $brokenLink->delete();

        return redirect()->route('admin.broken-links.index')->with('success', __('messages.record_deleted', ['module' => 'Broken link']));
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('broken_links', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax())
        {
            $brokenLinks = BrokenLink::latest()->get();

            return DataTables::of($brokenLinks)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return (isset($row->title)) ? truncateWords($row->title, 30) : '';
                })
                ->addColumn('link', function ($row) {
                    return (isset($row->link)) ? truncateWords($row->link, 25) : '';
                })
                ->addColumn('menu_name', function ($row) {
                    return (isset($row->menu_name)) ? truncateWords($row->menu_name, 15) : '';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('broken_links', 'edit')) {
                        $options .= ' <a href="'. $row->edit_link .'" target="_blank" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('broken_links', 'view')) {
                        $options .= ' <a href="'. route('admin.broken-links.show', $row->id) .'" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if (hasPermission('broken_links', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="'. route('admin.broken-links.destroy', $row->id ) .'" title="Delete">
                            <i class="fas fa-trash" data-action="'. route('admin.broken-links.destroy', $row->id ) .'"></i>
                        </button>';
                    }

                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
