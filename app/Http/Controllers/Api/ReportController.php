<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Database\Eloquent\Builder;
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
     * 
     *      @OA\Parameter(
     *          name="month",
     *          description="Data for only this month",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"}
     *          )
     *      ),
     * 
     * 
     *      @OA\Parameter(
     *          name="year",
     *          description="Data for only this year",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="type",
     *          description="Data for only this type",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"monthly", "annual", "archive"}
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
     *      )
     *  )
     */
    public function billingAndSettlement(Request $request)
    {
        /** @var ReportCategory $reportCategory */
        $reportCategory = ReportCategory::firstWhere('name', 'Billing and Settlement');
        $reportsQuery = $reportCategory->reports();
        if ($request->has('month')) {
            $reportsQuery->whereHas('filledAttributes', function ($q) use (&$request) {
                return $q->where('name', 'Settlement Month')->where('report_attribute_values.value', $request->month);
            });
        }

        if ($request->has('year')) {
            $reportsQuery->whereHas('filledAttributes', function ($q) use (&$request) {
                return $q->where('name', 'Settlement Year')->where('report_attribute_values.value', $request->year);
            });
        }

        if ($request->has('type')) {
            $subCategories = [];
            switch ($request->type) {
                case "monthly":
                    $subCategories = ["Monthly ESS", "Monthly FSS", "Monthly PSS"];
                    break;
                case "Annual":
                    $subCategories = ["Annually"];
                    break;
                case "Archived":
                    $subCategories = ["Archive"];
                    break;
            }

            $reportsQuery->whereHas('subCategory', function ($q) use (&$subCategories) {
                return $q->whereIn('name', $subCategories);
            });
        }

        $reports = $reportsQuery->with('subCategory.category', 'filledAttributes')->paginate(10);
        return $reports;
    }


    /**
     *
     * @OA\Get(
     *      path="/reports/billing-and-settlement/info",
     *      operationId="billingAndSettlementInfo",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get sub categories and attributes info for billing and settlement",
     *      description="Returns billing and settlement info",
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
    public function billingAndSettlementInfo()
    {
        return ReportCategory::with(['subCategories.attributes.type'])->firstWhere('name', 'Billing and Settlement');
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
