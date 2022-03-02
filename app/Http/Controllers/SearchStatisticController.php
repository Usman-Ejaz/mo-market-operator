<?php

namespace App\Http\Controllers;

use App\Models\SearchStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SearchStatisticController extends Controller
{

    function __construct()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->role->hasPermission('search-statistics', 'list')) {
            return abort(403);
        }

        return view('admin.search-statistics.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SearchStatistic  $searchStatistic
     * @return \Illuminate\Http\Response
     */
    public function show(SearchStatistic $searchStatistic)
    {
        if (!Auth::user()->role->hasPermission('search-statistics', 'view')) {
            return abort(403);
        }

        return view('admin.search-statistics.show', compact('searchStatistic'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SearchStatistic  $searchStatistic
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchStatistic $searchStatistic)
    {
        if (!Auth::user()->role->hasPermission('search-statistics', 'delete')) {
            return abort(403);
        }

        $searchStatistic->delete();
        return redirect()->route('admin.search-statistics.index')->with('success', 'Search Keyword Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if (!Auth::user()->role->hasPermission('search-statistics', 'list')) {
            return abort(403);
        }

        if ($request->ajax()) {
            $data = SearchStatistic::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->setTotalRecords($data->count())
                ->orderColumn('created_at', 'created_at $1')
                ->orderColumn('keyword', 'keyword $1')
                ->orderColumn('count', 'count $1')
                ->orderColumn('DT_RowIndex', 'id $1')
                ->addColumn('keyword', function ($row) {
                    return truncateWords($row->keyword, 20);
                })
                ->addColumn('count', function ($row) {
                    return $row->count;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (Auth::user()->role->hasPermission('search-statistics', 'view')) {
                        $options .= '<a href="'. route('admin.search-statistics.show',$row->id) .'" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if (Auth::user()->role->hasPermission('search-statistics', 'delete')) {
                        $options .= ' <form action="'. route('admin.search-statistics.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    public function exportkeywords() {

        if (!Auth::user()->role->hasPermission('search-statistics', 'export_keywords')) {
            return abort(403);
        }

        $searchStatistics = SearchStatistic::orderByCount()->get();
        
        $fileName = 'Search Keywords.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('ID' ,'Keyword', 'Count', 'Created Date');

        $callback = function() use ($searchStatistics, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($searchStatistics as $item) {
                $row['id'] = $item->id;
                $row['keyword'] = $item->keyword;
                $row['count'] = $item->count;
                $row['created_at'] = $item->created_at;

                fputcsv($file, array($row['id'], $row['keyword'], $row['count'], $row['created_at']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
