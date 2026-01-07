<?php
namespace App\Services\Api;

class RnaAPI
{
    private $baseUrl = 'https://hub.huwise.com/api/explore/v2.1/catalog/datasets/ref-france-association-repertoire-national/records/';

    public function searchAssociations(int $page=0,int $limit = 10,array $params = [])
    {
        $baseUrl = $this->baseUrl.'?page='.$page.'&limit='.$limit;
        try {
            $response = Http::get($this->baseUrl, $params);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Erreur lors de la requête: ' . $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('RNA API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
?>