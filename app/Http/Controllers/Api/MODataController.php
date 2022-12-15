<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetMODataAttachmentRequest;
use App\Models\MOData;
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
        $moData = MOData::whereNotNull('external_graph_id')->findOrFail($moDatumID);
        $cacheInstance = Cache::store('database');
        $cacheKey = "mo_data_graph_$moDatumID";

        if (!$cacheInstance->has($cacheKey)) {
            return (object)[];
        }

        return $cacheInstance->get($cacheKey);
    }

    /**
     *
     * @OA\Get(
     *      path="/mo-data/{id}/files",
     *      operationId="showSpecficMODataFiles",
     *      tags={"Market Operational Data"},
     *      summary="Get specific MO Data files",
     *      description="Returns single MO Data files based on filters",
     *      security={{"BearerAppKey": {}}},
     *      
     *      @OA\Parameter(
     *          name="id",
     *          description="MO data id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="month",
     *          description="Files for only this month",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"}
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="year",
     *          description="Files for only this year",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     *  )
     */
    public function files($moDatumID, GetMODataAttachmentRequest $request)
    {
        /** @var MOData $moDatum */
        $moDatum = MOData::findOrFail($moDatumID);
        $moDataFileQuery = $this->applyFileFilters($request, $moDatum->files());

        return $moDataFileQuery->orderBy("date", "desc")->paginate(10)->appends($request->all());
    }

    private function applyFileFilters($request, $moDataFileQuery)
    {
        if ($request->has('month')) {
            $moDataFileQuery->forMonth($request->month);
        }

        if ($request->has('year')) {
            $moDataFileQuery->forYear($request->year);
        }
        return $moDataFileQuery;
    }
}
