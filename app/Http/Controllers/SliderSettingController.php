<?php

namespace App\Http\Controllers;

use App\Models\SliderSetting;
use Illuminate\Http\Request;

class SliderSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // abort_if(! hasPermission('slider_settings', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $sliderSetting = SliderSetting::first();
        return view('admin.slider-settings.edit', compact('sliderSetting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SliderSetting  $sliderSetting
     * @return \Illuminate\Http\Response
     */
    public function show(SliderSetting $sliderSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SliderSetting  $sliderSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(SliderSetting $sliderSetting)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SliderSetting  $sliderSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SliderSetting $sliderSetting)
    {
        // abort_if(! hasPermission('slider_settings', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $data = $request->validate([
            'transition' => 'required|string',
            'speed' => 'required|string'
        ]);

        $sliderSetting->update($data);

        $request->session()->flash('success', 'Slider settings updated successfully!');
        return redirect()->route('admin.slider-images.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SliderSetting  $sliderSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(SliderSetting $sliderSetting)
    {
        //
    }
}
