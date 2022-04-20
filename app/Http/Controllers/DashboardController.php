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
        // <!-- Global site tag (gtag.js) - Google Analytics -->
        // <script async src="https://www.googletagmanager.com/gtag/js?id=G-WS4D14CBGY"></script>
        // <script>
        //   window.dataLayer = window.dataLayer || [];
        //   function gtag(){dataLayer.push(arguments);}
        //   gtag('js', new Date());
        
        //   gtag('config', 'G-WS4D14CBGY');
        // </script>
        
        // $client = new Google_Client();
        // $client->setApplicationName("Hello Analytics Reporting");
        // $client->setAuthConfig(config('settings.google_credentials'));
        // $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        // $analytics = new Google_AnalyticsService($client);

        // return $analytics;
        return view('admin.dashboard.index');
    }

    public function downloadAttachment($module, $file)
    {
        return donwloadFile($module, $file);
    }
}
