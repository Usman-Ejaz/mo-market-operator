<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManagerResource;
use App\Models\Manager;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamsApiController extends BaseApiController
{
    /**
     *
     * @OA\Tag(
     *     name="Teams",
     *     description="API Endpoints of Teams"
     * )
     *
     *
     * @OA\Get(
     *      path="/managers",
     *      operationId="getManagers",
     *      tags={"Teams"},
     *      summary="Get list of Managers",
     *      description="Returns Managers",
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
    public function getManagers()
    {
        try {
            $managers = Manager::select('id', 'name', 'description', 'designation', 'image')->sortByOrder()->get();

            if ($managers->count() > 0) {
                return $this->sendResponse(ManagerResource::collection($managers), __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     *
     * @OA\Tag(
     *     name="Teams",
     *     description="API Endpoints of Teams"
     * )
     *
     *
     * @OA\Get(
     *      path="/team/{manager_id}",
     *      operationId="getTeam",
     *      tags={"Teams"},
     *      summary="Get list of Teams",
     *      description="Returns Teams",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="manager_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function getTeam($manager_id)
    {
        try {
            $manager_id = decodeBase64($manager_id);

            $teamMembers = TeamMember::select('name', 'description', 'designation', 'image')->where('manager_id', '=', $manager_id)->sortByOrder()->get();

            $manager = Manager::select('name', 'description', 'designation', 'image')->where('id', '=', $manager_id)->first();

            if ($teamMembers->count() > 0) {
                $teamMembers->prepend($manager);
                return $this->sendResponse($teamMembers, __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
