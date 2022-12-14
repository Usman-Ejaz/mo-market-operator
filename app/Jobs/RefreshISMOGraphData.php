<?php

namespace App\Jobs;

use App\Clients\ISMOExternalAPIClient;
use App\Models\MOData;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RefreshISMOGraphData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->refreshNoCondition();
    }

    public function refreshNoCondition()
    {
        $moDatas = MOData::whereNotNull('external_graph_id')->get();
        $cacheInstance = Cache::store('database');

        foreach ($moDatas as $moData) {
            $moDatumID = $moData->id;
            $cacheKey = "mo_data_graph_$moDatumID";

            try {
                $graphDataFromAPI = $this->fetchGraphData($moData);
                $cacheInstance->put($cacheKey, $graphDataFromAPI);
            } catch (\Throwable $th) {
                continue;
            }
        }
    }

    private function refresh()
    {
        $moDatas = MOData::whereNotNull('external_graph_id')->get();

        foreach ($moDatas as $moData) {
            $moDatumID = $moData->id;
            $cacheInstance = Cache::store('database');
            $cacheKey = "mo_data_graph_$moDatumID";

            $graphDBCache = DB::table('cache')->where("key", "mo_cache$cacheKey")->first();
            $cacheExpired = true;
            if ($graphDBCache) {
                $cacheExpired = (($graphDBCache->expiration - now()->unix()) <= 0) ? true : false;
            }

            $graphData = (object)[];
            $expiration = now()->addMinutes(55);
            if ($cacheExpired) {
                try {
                    $graphData = $this->fetchGraphData($moData);
                    $expiration = now()->endOfDay();
                } catch (Exception $e) {
                    if ($graphDBCache) {
                        $graphData = unserialize($graphDBCache->value);
                    }
                }
                $cacheInstance->put($cacheKey, $graphData, $expiration);
            }
        }
    }

    private function fetchGraphData(MOData $moData)
    {
        if ($moData->external_graph_id != null) {
            $client = ISMOExternalAPIClient::getClient();
            return json_decode($client->get('api/values/GetData', [
                'query' => [
                    'GraphId' => $moData->external_graph_id,
                ],
            ])->getBody()->getContents());
        }

        return (object)[];
    }
}
