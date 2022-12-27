<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\GetComplaintsRequest;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\Client;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintDepartment;
use Illuminate\Http\UploadedFile;

class ComplaintController extends BaseApiController
{
    /**
     *
     * @OA\Tag(
     *     name="Complaints",
     *     description="API endpoints for complaints"
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *   tags={"Complaints"},
     *   path="/complaints",
     *   summary="Complaint index",
     *   operationId="getComplaints",
     *   description="API endpoints for complaints",
     *   security={{"BearerAppKey": {}}},
     *   @OA\Parameter(
     *      name="page",
     *      description="Page number",
     *      required=false,
     *      in="query",
     *      @OA\Schema(
     *         type="integer"
     *      )
     *   ),
     *   
     *   @OA\Parameter(
     *      name="complaint_department_id",
     *      description="Filter based on department ID",
     *      required=false,
     *      in="query",
     *      @OA\Schema(
     *         type="integer"
     *      )
     *   ),
     *   
     *   @OA\Parameter(
     *      name="status",
     *      description="Filter based on complaint status",
     *      required=false,
     *      in="query",
     *      @OA\Schema(
     *         type="string",
     *         enum={"unresolved", "hold", "solved"}
     *      )
     *   ),
     *   
     *   @OA\Parameter(
     *      name="search",
     *      description="Filter based on text search in message and subject",
     *      required=false,
     *      in="query",
     *      @OA\Schema(
     *         type="string",
     *      )
     *   ),
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
     * )
     */
    public function index(GetComplaintsRequest $request)
    {
        /** @var Client $client */
        $client = auth()->user();
        $query = $client->complaints();
        return $this->applyfilters($query, $request)->with('department', 'attachments')->paginate(10);
    }

    private function applyfilters($query, GetComplaintsRequest $request)
    {
        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('complaint_department_id')) {
            $query->forDepartment($request->complaint_department_id);
        }

        if ($request->has('status')) {
            $query->withStatus($request->status);
        }

        return $query;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     *
     * @OA\Post(
     *      path="/complaints",
     *      operationId="storeComplaint",
     *      description="API endpoints for complaints",
     *      tags={"Complaints"},
     *      summary="Store a complaint",
     *      security={{"BearerAppKey": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"subject", "message", "complaint_department_id"},
     *                 @OA\Property(
     *                     description="Subject of the complaint",
     *                     property="subject",
     *                     type="string",
     *                 ),
     *                 
     *                 @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="Message of the complaint"
     *                 ),
     *                 
     *                 @OA\Property(
     *                   property="complaint_department_id",
     *                   type="integer",
     *                   description="ID of the complaint department this complaint is addressed to."
     *                 ),
     *                 
     *                 @OA\Property(
     *                   property="attachments[]",
     *                   type="array",
     *                   @OA\Items(
     *                     type="file",
     *                     format="binary",
     *                   ),
     *                   description="Attachment for complaint"
     *                 )
     *             )
     *         )
     *     ),
     *     
     *      @OA\Response(
     *          response=201,
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
    public function store(StoreComplaintRequest $request)
    {
        /** @var Client $client */
        // dd($request->attachments);
        $client = auth()->user();
        $complaint = $client->complaints()->create($request->validated());
        if ($request->has('attachments')) {
            $this->storeAttachments($complaint, $request);
        }

        return $complaint->load(['department', 'attachments']);
    }

    private function storeAttachments(Complaint $complaint, StoreComplaintRequest $request)
    {
        $filesToStore = collect($request->attachments)->map(function (UploadedFile $file) {
            $fileStoredName = storeFile(ComplaintAttachment::STORAGE_DIRECTORY, $file);
            return [
                'file_path' => config('app.url') . '/storage/uploads/' . ComplaintAttachment::STORAGE_DIRECTORY . $fileStoredName,
            ];
        });

        $complaint->attachments()->createMany($filesToStore);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *   tags={"Complaints"},
     *   path="/complaints/{id}",
     *   summary="Get a specific complaint",
     *   security={{"BearerAppKey": {}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="ID of the complaint",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id)
    {
        $complaint = Complaint::forClient(auth()->user())->with(['department', 'attachments'])->first();
        if ($complaint) {
            return $complaint;
        }

        return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
    }


    /**
     * @OA\Get(
     *   tags={"Complaints"},
     *   path="/complaints/departments",
     *   summary="Get all complaint departments",
     *   security={{"BearerAppKey": {}}},
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getDepartments()
    {
        return ComplaintDepartment::all();
    }
}
