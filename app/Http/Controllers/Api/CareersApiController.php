<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CareersApiController extends BaseApiController
{
    /**
     * 
     * @OA\Tag(
     *     name="Jobs & Job Applications",
     *     description="API Endpoints of Jobs & Job Applications"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/jobs",
     *      operationId="getPublishedJobs",
     *      tags={"Jobs & Job Applications"},
     *      summary="Get list of Published Jobs",
     *      description="Returns list of Jobs",
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
     *      )
     *  )
     */
    public function getPublishedJobs()
    {
        try {
            $jobs = Job::published()->applyFilters()->get();

            if ($jobs->count() > 0) {
                return $this->sendResponse($jobs, "Success");
            } else {
                return $this->sendError(__('messages.data_not_found'), [], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }         
    }

    /**
     * 
     * @OA\Get(
     *      path="/job/{slug}",
     *      operationId="showSingleJob",
     *      tags={"Jobs & Job Applications"},
     *      summary="Get Specific Job against slug",
     *      description="Returns list of Jobs",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="Job slug",
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
     */
    public function showSingleJob($slug)
    {
        try {
            $job = Job::published()->where("slug", "=", $slug)->withCount('applications')->first();

            if ($job) {
                return $this->sendResponse($job, "Success");
            } else {
                return $this->sendError(__('messages.data_not_found'), null, 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Post(
     *      path="/submit-job-application",
     *      operationId="submitApplication",
     *      tags={"Jobs & Job Applications"},
     *      summary="Submit Job Application",
     *      description="Submit Job Application in the resource",
     *      security={{"BearerAppKey": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      title="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="gender",
     *                      title="gender",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      title="phone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="experience",
     *                      title="experience",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      title="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="degree_level",
     *                      title="degree_level",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="degree_title",
     *                      title="degree_title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      title="address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="job_slug",
     *                      title="job_slug",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="resume",
     *                      title="resume",
     *                      type="file"
     *                  ),
     *                  required={"name", "email", "gender", "city", "experience", "phone", "degree_level", "degree_title", "address", "job_slug", "resume"},
     *                  example={
     *                      "name": "John Doe", 
     *                      "email": "johndoe@email.com", 
     *                      "gender": "male", 
     *                      "experience": "2 Years", 
     *                      "phone": "03001234567", 
     *                      "degree_level": "Masters",
     *                      "degree_title": "MSC",
     *                      "address": "USA",
     *                      "city": "California",
     *                  }
     *             )
     *         )
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
    public function submitApplication (Request $request) {
        $validator = Validator::make($request->all(), $this->getApplicationRules(), $this->getApplicationMessages());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $job = Job::published()->where("slug", "=", $request->job_slug)->select("id")->first();

            if ($job) {
                $data = $validator->validate();
                unset($data['job_slug']);
                $data['job_id'] = $job->id;
                
                if ($request->hasFile("resume")) {
                    $data['resume'] = storeFile(Application::STORAGE_DIRECTORY, $request->file('resume'));
                }
                
                Application::create($data);

                return $this->sendResponse([], __('messages.success'));
            } else {
                return $this->sendError(__('messages.data_not_found'), null, 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    private function getApplicationRules() {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:applications,email',
            'gender' => 'required|string|min:3|max:15',
            'phone' => 'required|string|min:3|max:50',
            'experience' => 'required|string|min:3|max:255',
            'city' => 'required|string|min:3|max:255',
            'degree_level' => 'required|string|min:3|max:255',
            'degree_title' => 'required|string|min:3|max:255',
            'address' => 'required|string|min:3|max:500',
            'resume' => 'required|file|mimes:doc,docx,pdf|max:' . config('settings.maxDocumentSize'),
            'job_slug' => 'required|string|min:3'
        ];
    }

    private function getApplicationMessages() {
        return [
            'resume.max' => __('messages.max_file', ['limit' => '5 MB'])
        ];
    }
}
