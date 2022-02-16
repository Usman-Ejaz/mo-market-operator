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
     * @OA\Get(
     *      path="/get-jobs",
     *      operationId="getPublishedJobs",
     *      tags={"Jobs & Job Applications"},
     *      summary="Get list of Published Jobs",
     *      description="Returns list of Jobs",
     *      security={{"BearerAppKey": {}}},
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
    public function getPublishedJobs()
    {
        try {
            $jobs = Job::published()->withCount("applications")->latest()->get();

            if ($jobs->count() > 0) {
                return $this->sendResponse($jobs, "Success");
            } else {
                return $this->sendError("Error", ['errors' => 'Could not found jobs'], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
        }         
    }

    /**
     * 
     * @OA\Tag(
     *     name="Jobs & Job Applications",
     *     description="API Endpoints of Jobs & Job Applications"
     * )
     * 
     * @OA\Get(
     *      path="/show-job/{slug}",
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
            $job = Job::published()->where("slug", "=", $slug)->first();

            if ($job) {
                return $this->sendResponse($job, "Success");
            } else {
                return $this->sendError("Could not found jobs", [], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
        }
    }

    public function submitApplication (Request $request) {
        $validator = Validator::make($request->all(), $this->getApplicationRules());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $job = Job::published()->where("slug", "=", $request->slug)->select("id")->first();

            if ($job) {
                $application = Application::create($validator->validate());
                $application->job_id = $job->id;
                if ($request->hasFile("resume")) {
                    $file = $request->file('resume');
                    $file_name = $file->hashName();
                    $file->storeAs(config('filepaths.applicationsPath.internal_path'), $file_name);
                    $application->resume = $file_name;
                }
                $application->save();

                return $this->sendResponse([], "Application submitted successfully");
            } else {
                return $this->sendError("Error", ["errors" => "Could not find the job."], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
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
            'resume' => 'required|file|mimes:doc,docx,pdf|max:20000'
        ];
    }
}
