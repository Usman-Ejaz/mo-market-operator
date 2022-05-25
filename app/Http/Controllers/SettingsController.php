<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("settings", "list"), 401, __('messages.unauthorized_action'));
        
        $theme = Settings::where('name', 'current_theme')->first();
        $notification_emails = Settings::where('name', 'notification_emails')->first();
        $from_emails = Settings::where('name', 'from_emails')->first();
        
        return view('admin.settings.index', compact('theme', 'notification_emails', 'from_emails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request = $request->except('_token', '_method');
        foreach($request as $name => $value) {
            $updated = Settings::update_option($name, $value);
        }

        request()->session()->flash('success', __('messages.record_updated', ['module' => 'Site configuration']));
        return redirect()->route('admin.site-configuration.index');
    }

}
