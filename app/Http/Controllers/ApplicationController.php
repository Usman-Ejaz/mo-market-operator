<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        $application = Application::find($application->id);
        return view('admin.applications.show', compact('application'));
    }

    public function destroy(Application $application)
    {
        $application = Application::find($application->id);
        $application->delete();

        return redirect()->route('admin.job.applications',$application->job_id);
    }
}
