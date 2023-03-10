<?php

namespace App\Http\Controllers;

use App\Models\SliderImage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SliderImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('slider_images', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.slider-images.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! hasPermission('slider_images', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $sliderImage = new SliderImage;
        return view('admin.slider-images.create', compact('sliderImage'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(! hasPermission('slider_images', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $sliderImage = new SliderImage;
        $data = $this->validateRequest($sliderImage);
        $data['image'] = storeFile(SliderImage::STORAGE_DIRECTORY, $request->file('image'), null);
        SliderImage::create($data);

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Slider image']));
        return redirect()->route('admin.slider-images.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SliderImage  $sliderImage
     * @return \Illuminate\Http\Response
     */
    public function show(SliderImage $sliderImage)
    {
        abort_if(! hasPermission('slider_images', 'view'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.slider-images.show', compact('sliderImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SliderImage  $sliderImage
     * @return \Illuminate\Http\Response
     */
    public function edit(SliderImage $sliderImage)
    {
        abort_if(! hasPermission('slider_images', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.slider-images.edit', compact('sliderImage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SliderImage  $sliderImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SliderImage $sliderImage)
    {
        abort_if(! hasPermission('slider_images', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $data = $this->validateRequest($sliderImage);
        if ($request->hasFile('image')) {
            removeFile(SliderImage::STORAGE_DIRECTORY, $sliderImage->image);
            $data['image'] = storeFile(SliderImage::STORAGE_DIRECTORY, $request->file('image'), null);
        }
        $sliderImage->update($data);

        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Slider image']));
        return redirect()->route('admin.slider-images.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SliderImage  $sliderImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SliderImage $sliderImage)
    {
        abort_if(! hasPermission('slider_images', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        removeFile(SliderImage::STORAGE_DIRECTORY, $sliderImage->image);
        $sliderImage->delete();

        return redirect()->route('admin.slider-images.index')->with('success', __('messages.record_deleted', ['module' => 'Slider image']));
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('slider_images', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = SliderImage::orderByImageOrder()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('slot_one', function ($row) {
                    return truncateWords($row->slot_one, 20);
                })
                ->addColumn('slot_two', function ($row) {
                    return truncateWords($row->slot_two, 30);
                })
                ->addColumn('order', function ($row) {
                    return ($row->order) ? $row->order : '';
                })
                ->addColumn('image', function ($row) {
                    return !empty($row->image) ? '<img src="'. $row->image .'" height="100" width="100" />' : 'No image is selected';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('slider_images', 'edit')) {
                        $options .= '<a href="'. route('admin.slider-images.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('slider_images', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="'. route('admin.slider-images.destroy', $row->id ) .'" title="Delete">
                            <i class="fas fa-trash" data-action="'. route('admin.slider-images.destroy', $row->id ) .'"></i>
                        </button>';
                    }

                    return $options;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    private function validateRequest($sliderImage)
    {
        $rules = [
            'slot_one' => 'required|string|min:3',
            'slot_two' => 'required',
            'url' => 'required',
            'order' => 'required',
            'image' => 'sometimes|required|file|max:' . (config('settings.maxImageSize') + 6000),
        ];

        return request()->validate($rules, [
            'image.max' => __('messages.max_file', ['limit' => '2.5 MB'])
        ]);
    }
}
