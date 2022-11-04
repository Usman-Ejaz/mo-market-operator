<?php

namespace App\Http\Controllers\Api;

use App\Clients\ISMOExternalAPIClient;
use App\Http\Controllers\Controller;
use App\Models\MOData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MODataController extends Controller
{
    /**
     *
     * @OA\Tag(
     *     name="Market Operational Data",
     *     description="API Endpoints of MO Data"
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *
     * @OA\Get(
     *      path="/mo-data",
     *      operationId="getAllMOData",
     *      description="API endpoints for MO Data",
     *      tags={"Market Operational Data"},
     *      summary="Get all mo data with files and extra attributes",
     *      description="Returns all mo data",
     *      security={{"BearerAppKey": {}}},
     *      
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
     */
    public function index()
    {
        return MOData::with(['files', 'extraAttributes'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     *
     * @OA\Get(
     *      path="/mo-data/{id}",
     *      operationId="showSpecificMOData",
     *      tags={"Market Operational Data"},
     *      summary="Get specific MO Data",
     *      description="Returns single MO Data with files and extra attributes",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="MO data id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     *  )
     */
    public function show($moDatumID)
    {
        // dd($moDatumID);
        return MOData::with(['files', 'extraAttributes'])->findOrFail($moDatumID);
    }


    /**
     *
     * @OA\Get(
     *      path="/mo-data/{id}/graph",
     *      operationId="showSpecficMODataGraph",
     *      tags={"Market Operational Data"},
     *      summary="Get specific MO Data graph",
     *      description="Returns single MO Data graph based on id param",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="MO data id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     *  )
     */
    public function getGraph($moDatumID)
    {
        $moData = MOData::findOrFail($moDatumID);

        $cacheKey = "mo_data_graph_$moDatumID";

        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, $this->fetchGraphData($moData), now()->endOfDay());
        }
        return Cache::get($cacheKey);
    }

    private function fetchGraphData(MOData $moData)
    {
        if ($moData->external_graph_id != null) {
            $client = ISMOExternalAPIClient::getClient();
            // dd("reached jere", $client);
            return json_decode($client->get('api/values/GetData', [
                'query' => [
                    'GraphId' => $moData->external_graph_id,
                ],
            ])->getBody()->getContents());
        }

        return (object)[];
    }
}
