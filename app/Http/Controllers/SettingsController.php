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
        return view('admin.settings.index', compact('theme'));
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

        request()->session()->flash('success', 'Settings updated successfully!');
        return redirect()->route('admin.settings.index');
    }

}
