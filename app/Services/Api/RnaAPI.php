<?php
namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RnaAPI
{
    // Enlevez le slash final
    private $baseUrl = 'https://hub.huwise.com/api/explore/v2.1/catalog/datasets/ref-france-association-repertoire-national/records';
    
    public function searchAssociations(int $page = 0, int $limit = 10, array $params = [])
    {
        $queryParams = array_merge($params, [
            'offset' => $page * $limit, // Changé de 'page' à 'offset'
            'limit' => $limit,
        ]);
        
        try {
            //a changer en ::get en production
            $response = Http::withoutVerifying()->get($this->baseUrl, $queryParams);
            
            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('RNA API Error: ' . $response->status() . ' - ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('RNA API Exception: ' . $e->getMessage());
            return null;
        }
    }
}