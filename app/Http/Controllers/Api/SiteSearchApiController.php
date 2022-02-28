<?php

namespace App\Http\Controllers\Api;

use App\Events\SiteSearchEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSearchApiController extends Controller
{
    const SEARCHABLE_MODELS = ['News.php', 'Job.php', 'Post.php', 'Faq.php'];

    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'required',
                'type' => 'sometimes|string',
            ]);
    
            if ($validator->fails()) {
                return $this->sendError("Error", ['errors' => $validator->errors()], 400);
            }
    
            // Log search keywords
            event(new SiteSearchEvent($request->search));

            $keyword = $request->search;

            // $result = collect(self::SEARCHABLE_MODELS)->filter(function ($filename) {
                
            //     $reflection = new \ReflectionClass($this->modelNamespacePrefix() . $filename);
            //     $isModel = $reflection->isSubclassOf(Model::class);
            //     $searchable = $reflection->hasMethod('search');
            //     return $isModel && $searchable;

            // })->map(function ($classname) use ($keyword) {
            //     $model = app($this->modelNamespacePrefix() . $classname);
            //     $fields = array_filter($model::SEARCHABLE_FIELDS, fn($field) => $field !== 'id');
            //     $model::search($keyword)->get()->map(function ($modelRecord) use ($model, $fields) {
            //         return $modelRecord->only($fields);
            //     });
            // });

            dd($result);

        } catch (\Exception $ex) {

        }        
    }

    private function modelNamespacePrefix() {
        return app()->getNamespace() . 'Models\\';
    }
}
