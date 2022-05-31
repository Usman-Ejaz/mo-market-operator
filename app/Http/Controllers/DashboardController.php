<?php

namespace App\Http\Controllers;

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

    public function downloadAttachment($module, $file)
    {
        return downloadFile($module, $file);
    }
}
