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
        abort_if(!hasPermission("search-statistics", "list"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("search-statistics", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("search-statistics", "delete"), 401, __('messages.unauthorized_action'));

        $searchStatistic->delete();
        return redirect()->route('admin.search-statistics.index')->with('success', 'Search Keyword Deleted Successfully!');
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("search-statistics", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = SearchStatistic::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->setTotalRecords($data->count())
                ->orderColumn('keyword', 'keyword $1')
                ->orderColumn('count', 'count $1')
                ->orderColumn('DT_RowIndex', 'id $1')
                ->addColumn('keyword', function ($row) {
                    return truncateWords($row->keyword, 70);
                })
                ->addColumn('count', function ($row) {
                    return $row->count;
                })
                ->make(true);
        }
    }

    public function exportkeywords() {

        abort_if(!hasPermission("search-statistics", "export_keywords"), 401, __('messages.unauthorized_action'));

        $searchStatistics = SearchStatistic::orderByCount()->get();
        
        $fileName = 'Search Keywords.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('ID' ,'Keyword', 'Count');

        $callback = function() use ($searchStatistics, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $counter = 1;
            foreach ($searchStatistics as $item) {
                $row['id'] = $counter;
                $row['keyword'] = $item->keyword;
                $row['count'] = $item->count;

                fputcsv($file, array($row['id'], $row['keyword'], $row['count']));
                $counter++;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
