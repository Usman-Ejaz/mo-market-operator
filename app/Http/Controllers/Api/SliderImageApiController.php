<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use App\Models\SliderSetting;
use Illuminate\Http\Request;

class SliderImageApiController extends BaseApiController
{
    /**
     *
     * @OA\Tag(
     *     name="Slider Images",
     *     description="API Endpoints of Slider Images"
     * )
     *
     */

    /**
     * @OA\Get(
     *      path="/slider-images",
     *      operationId="getSliderImages",
     *      tags={"Slider Images"},
     *      summary="Get list of Slider Images",
     *      description="Returns Slider Images",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Could not found",
     *      ),
     *  )
     */
    public function getSliderImages()
    {
        try {
            $data['slider_images'] = SliderImage::orderByImageOrder()->select("slot_one", "slot_two", "url", "image")->get();

            if ($data['slider_images']->count() > 0) {
                $data['settings'] = SliderSetting::get();
                return $this->sendResponse($data, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
