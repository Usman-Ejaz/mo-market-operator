<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     *
     * @OA\Tag(
     *     name="Reports",
     *     description="API endpoints for reports"
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Report::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *
     * @OA\Get(
     *      path="/reports/billing-and-settlement",
     *      operationId="billingAndSettlement",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get all biling and settlement reports paginated",
     *      description="Returns billing and settlement reports paginated",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="page",
     *          description="Page number",
     *          required=false,
     *          in="query",
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
     *      )
     *  )
     */
    public function billingAndSettlement()
    {
        /** @var ReportCategory $reportCategory */
        $reportCategory = ReportCategory::firstWhere('name', 'Billing and Settlement');
        $reports = $reportCategory->reports()->with('subCategory.category', 'filledAttributes')->paginate(10);
        return $reports;
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
     *      path="/reports/{id}",
     *      operationId="showReportByID",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get a specific report with details",
     *      description="Returns report with the given id with all details",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the report",
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
     *      )
     *  )
     */
    public function show($id)
    {
        return Report::with('subCategory.category', 'filledAttributes', 'attachments')->firstWhere('id', $id);
    }
}
