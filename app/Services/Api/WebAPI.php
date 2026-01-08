<?php
namespace App\Services\Api;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebAPI{
    static public function isUrlValid($url){
        try {
            $response = Http::withoutVerifying()->get($url);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WebAPI Exception: ' . $e->getMessage());
            return false;
        }
    }
}