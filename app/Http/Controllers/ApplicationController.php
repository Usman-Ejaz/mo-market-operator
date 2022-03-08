<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        abort_if(!hasPermission('applications', 'view'), 401, __('messages.unauthorized_action'));

        $application = Application::find($application->id);
        return view('admin.applications.show', compact('application'));
    }

    public function destroy(Application $application)
    {
        abort_if(!hasPermission('applications', 'delete'), 401, __('messages.unauthorized_action'));

        $application = Application::find($application->id);
        $application->delete();

        return redirect()->route('admin.job.applications',$application->job_id)->with('success', 'Application Deleted Successfully !');
    }
}
