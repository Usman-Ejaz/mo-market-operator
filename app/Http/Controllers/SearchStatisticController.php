<?php

namespace App\Http\Controllers;

use App\Models\SearchStatistic;
use Google_Client;
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
        abort_if(!hasPermission("search_statistics", "list"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("search_statistics", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("search_statistics", "delete"), 401, __('messages.unauthorized_action'));

        $searchStatistic->delete();
        return redirect()->route('admin.search-statistics.index')->with('success', __('messages.record_deleted', ['module' => 'Search keyword']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("search_statistics", "list"), 401, __('messages.unauthorized_action'));

        $startFrom = $request->get('start_date');
        $endsAt = $request->get('end_date');

        if ($request->ajax()) {
            $data = SearchStatistic::groupByKeyword($startFrom, $endsAt)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('keyword', function ($row) {
                    return truncateWords($row->keyword, 70);
                })
                ->make(true);
        }
    }

    public function getAnalyticsData()
    {
        // Reference link from where code has copied.
        // https://reeteshghimire.com.np/2021/09/11/google-analytics-realtime-traffic-viewer-using-analytics-api/
        $client = new Google_Client();
        $client->setApplicationName("Realtime Analytics");
        $client->setAuthConfig(config('settings.google_credentials'));
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_Analytics($client);

        $activePages = $this->getActivePages($analytics);
        $activeUsers = $this->getActiveUsers($analytics);

        return response([
            'activePages' => $activePages,
            'activeUsers' => $activeUsers
        ], 200);
    }

    public function exportkeywords() {

        abort_if(!hasPermission("search_statistics", "export_keywords"), 401, __('messages.unauthorized_action'));

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

    private function getActivePages($analytics)
    {
        $optParams = [
            'dimensions' => 'rt:pageTitle,rt:pagePath',
            'sort' => '-rt:activeVisitors',
            'max-results' => '16'
        ];
        $result = $analytics->data_realtime->get('ga:'.config('settings.ga_view_id'), 'rt:activeVisitors', $optParams);
        $table = '';
        if ($result) {
            $rows = $result->getRows();
            if ($rows) {
                foreach ($rows as $row) {
                    $table .= '<tr class="open-link" data-link="'.$row[1].'">';
                    $table .= '<td>'.htmlspecialchars($row[0],ENT_NOQUOTES).'</td>';
                    $table .= '<td>'.htmlspecialchars($row[2],ENT_NOQUOTES).'</td>';
                    $table .= '</tr>';
                }
            } else {
                $table .= '<tr><td colspan="2"><small>There is no data to view</small></td></tr>';
            }

            return $table;
        } else {
            return '<tr><td colspan="2"><small>There is no data to view</small></td></tr>';
        }
    }

    private function getActiveUsers($analytics)
    {
        $active_users = $analytics->data_realtime->get('ga:'.config('settings.ga_view_id'), 'rt:activeVisitors');
        $active_users = (isset($active_users->rows[0][0])) ? $active_users->rows[0][0] : 0;
        return $active_users;
    }
}
