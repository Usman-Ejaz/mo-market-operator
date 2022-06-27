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
    public function getTrainings()
    {
        try {
            $trainings = Training::select('title', 'slug', 'short_description', 'topics', 'location', 'status', 'target_audience')->applyFilters()->get();

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
            $training = Training::whereSlug($slug)->select('title', 'slug', 'topics', 'location', 'target_audience', 'description', 'status', 'attachment', 'start_date', 'end_date')->first();

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
