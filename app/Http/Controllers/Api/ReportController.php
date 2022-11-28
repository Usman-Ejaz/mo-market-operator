<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetBillingAndSettlementReports;
use App\Http\Requests\GetContractDetailsRequest;
use App\Http\Requests\GetFirmCapacityCertificateRequest;
use App\Http\Requests\GetMeteringDataRequest;
use App\Models\Report;
use App\Models\ReportCategory;

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
     *      summary="Get all billing and settlement reports paginated",
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
     *          name="tab",
     *          description="Data for only this tab",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"monthly", "annual", "archived"}
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
     *              enum={"pss", "fss", "ess"}
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
    public function billingAndSettlement(GetBillingAndSettlementReports $request)
    {
        /** @var ReportCategory $reportCategory */
        $reportCategory = ReportCategory::firstWhere('name', 'Billing and Settlement');
        $reportsQuery = $reportCategory->reports();
        if ($request->has('month')) {
            $reportsQuery->attributeWithValue('Settlement Month', $request->month);
        }

        if ($request->has('year')) {
            $reportsQuery->attributeWithValue('Settlement Year', $request->year);
        }

        if ($request->has('tab')) {
            $subCategories = [];
            switch ($request->tab) {
                case "monthly":
                    $subCategories = ["Monthly ESS", "Monthly FSS", "Monthly PSS"];
                    break;
                case "annual":
                    $subCategories = ["Annually"];
                    break;
                case "archived":
                    $subCategories = ["Archive"];
                    break;
            }

            $reportsQuery->forSubCategory($subCategories);
        }

        if ($request->has('type')) {
            switch ($request->type) {
                case 'pss':
                    $reportsQuery->forSubCategory(["Monthly PSS"]);
                    break;
                case 'fss':
                    $reportsQuery->forSubCategory(['Monthly FSS']);
                    break;
                case 'ess':
                    $reportsQuery->forSubCategory(['Monthly ESS']);
                    break;
            }
        }

        return $reportsQuery->with('subCategory.category', 'filledAttributes')->orderBy('id', 'desc')->paginate(10)->appends($request->all());
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/contract-details",
     *      operationId="contractDetails",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get all contract details reports paginated",
     *      description="Returns contract details reports paginated",
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
    public function contractDetails(GetContractDetailsRequest $request)
    {
        $reportsQuery = Report::forCategory(['Contract Details']);

        if ($request->has('month')) {
        }

        return $reportsQuery->with('subCategory.category', 'filledAttributes')->orderBy('id', 'desc')->paginate(10)->appends($request->all());
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/firm-capacity-certificate",
     *      operationId="firmCapacityCertificate",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get all firm capacity certificate reports paginated",
     *      description="Returns firm capacity certificate reports paginated",
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
    public function firmCapacityCertificate(GetFirmCapacityCertificateRequest $request)
    {
        $reportsQuery = Report::forCategory(['Firm Capacity Certificate']);

        return $reportsQuery->with('subCategory.category', 'filledAttributes')->orderBy('id', 'desc')->paginate(10)->appends($request->all());
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/metering-data",
     *      operationId="meteringData",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get all metering data reports paginated",
     *      description="Returns metering data reports paginated",
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
    public function meteringData(GetMeteringDataRequest $request)
    {
        $reportsQuery = Report::forCategory(['Metering Data']);
        return $reportsQuery->with('subCategory.category', 'filledAttributes')->orderBy('id', 'desc')->paginate(10)->appends($request->all());
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/info",
     *      operationId="reportsInfo",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get all categories with sub categories and attributes",
     *      description="Returns all categories with sub categories and attributes",
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
    public function info()
    {
        return ReportCategory::with('subCategories.attributes')->get();
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
     *
     * @OA\Get(
     *      path="/reports/contract-details/info",
     *      operationId="contractDetailsInfo",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get sub categories and attributes info for contract details",
     *      description="Returns contract details info",
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
    public function contractDetailsInfo()
    {
        return ReportCategory::with(['subCategories.attributes.type'])->firstWhere('name', 'Contract Details');
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/firm-capacity-certificate/info",
     *      operationId="firmCapacityCertificateInfo",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get sub categories and attributes info for firm capacity certificate",
     *      description="Returns firm capacity certificate info",
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
    public function firmCapacityCertificateInfo()
    {
        return ReportCategory::with(['subCategories.attributes.type'])->firstWhere('name', 'Firm Capacity Certificate');
    }

    /**
     *
     * @OA\Get(
     *      path="/reports/metering-data/info",
     *      operationId="meteringDataInfo",
     *      description="API endpoints for reports",
     *      tags={"Reports"},
     *      summary="Get sub categories and attributes info for metering data",
     *      description="Returns metering data info",
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
    public function meteringDataInfo()
    {
        return ReportCategory::with(['subCategories.attributes.type'])->firstWhere('name', 'Metering Data');
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
