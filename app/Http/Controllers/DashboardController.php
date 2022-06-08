<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Exception;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // $client = new Google_Client();
        // $client->setApplicationName("Hello Analytics Reporting");
        // $client->setAuthConfig(config('settings.google_credentials'));
        // $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        // $analytics = new Google_Service_AnalyticsReporting($client);

        // return $analytics;

        // $analytics = new Google_Service_AnalyticsReporting();
        return view('admin.dashboard.index');
    }

    public function getLatestAcitivityLogs(Request $request)
    {
        if (! $request->ajax()) {
            return response('Bad Request.', 400);
        }

        try {
            $logs = ActivityLog::latest()->limit(10)->select('id', 'type', 'message', 'done_by', 'created_at')->get();
            $table = "";
            if ($logs->count() > 0) {
                foreach($logs as $row){
                    $table .= '<tr>';
                    $table .= '<td>'. $row->id .'</td>';
                    $table .= '<td>'. $row->message .'</td>';
                    $table .= '<td>'. $row->type .'</td>';
                    // $table .= '<td>'. $row->module .'</td>';
                    $table .= '<td>'. $row->done_by .'</td>';
                    $table .= '<td>'. $row->created_at .'</td>';
                    $table .= '</tr>';
                }
            } else {
                $table .= '<tr><td colspan="5"><small>There is no data to view</small></td></tr>';
            }
            return response(['activityLogs' => $table], 200);
        } catch (Exception $ex) {
            return response($ex->getMessage(), 500);
        }
    }

    public function downloadAttachment($module, $file)
    {
        return downloadFile($module, $file);
    }
}
