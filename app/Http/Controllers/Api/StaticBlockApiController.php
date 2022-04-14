<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticBlock;
use Exception;
use Illuminate\Http\Request;

class StaticBlockApiController extends BaseApiController
{
    /**
     * 
     * @OA\Tag(
     *     name="Static Blocks",
     *     description="API Endpoints of Static Blocks"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/static-blocks",
     *      operationId="show",
     *      tags={"Static Blocks"},
     *      summary="Get list of Static Blocks",
     *      description="Returns Static Blocks",
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
    public function show()
    {
        try {
            $staticBlocks = StaticBlock::select('name', 'contents', 'identifier')->get();

            $arr = [];
            foreach ($staticBlocks as $item) {
                $arr[$item->identifier] = [
                    'name' => $item->name,
                    'contents' => $item->contents
                ];
            }

            if ($staticBlocks->count() > 0) {
                return $this->sendResponse($arr, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
