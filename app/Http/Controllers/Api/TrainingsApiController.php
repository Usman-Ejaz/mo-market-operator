<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingsApiController extends BaseApiController
{

    /**
     * @OA\Tag(
     *     name="Trainings",
     *     description="API Endpoints of Trainings"
     * )
     *
     * @OA\Get(
     *      path="/trainings",
     *      operationId="getTrainings",
     *      tags={"Trainings"},
     *      summary="Get list of Training",
     *      description="Returns Training",
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
     *
     */
    public function getTrainings(Request $request)
    {
        try {

            $status = 1;
            if ($request->query('previous') && $request->get('previous') == 'true') {
                $status = 0;
            }

            $trainings = Training::where('status', '=', $status)->applyFilters()->select('title', 'slug', 'short_description', 'topics', 'location', 'target_audience')->get();

            if ($trainings->count() > 0) {
                return $this->sendResponse($trainings, __('messages.success'));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/trainings/{slug}",
     *      operationId="getTrainingDetails",
     *      tags={"Trainings"},
     *      summary="Get Specific training against slug",
     *      description="Returns list of training",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="Training slug",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=402,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     *
     */
    public function getTrainingDetails($slug)
    {
        try {
            $training = Training::whereSlug($slug)->select('title', 'topics', 'location', 'target_audience', 'description', 'attachment', 'start_datetime', 'end_datetime')->first();

            if ($training) {
                return $this->sendResponse($training, __('messages.success'));
            } else {
                return $this->sendResponse(null, __('messages.data_not_found'), HTTP_NOT_FOUND);
            }

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
