<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

class CkanApiService {
    protected $ckanBasePath;
    protected $ckanApiToken;
    protected $ckanContentType = "application/json";

    public function __construct() {
        $this->ckanApiToken = env('CKAN_API_TOKEN');
        $this->ckanBasePath = "http://ckan-docker-ckan-1:5000/api/3/action/";
    }

    public function getCkanRequest($endpoint, $params) {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Content-Type' => $this->ckanContentType,
            ])->get($this->ckanBasePath . $endpoint, $params);
        
            if ($response->successful()) {
                return $response->json();
            } else {
                $error = $response->body();
                throw new Exception("Error Processing Request: " . $error);
            }
        } catch (RequestException $e) {
            $error = $e->getResponse()->body();
            return response()->json(['error' => 'Request failed, please try again.'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function postCkanRequest($endpoint, $body) {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Content-Type' => $this->ckanContentType,
            ])->post($this->ckanBasePath . $endpoint, $body);
        
            if ($response->successful()) {
                return $response->json();
            } else {
                $error = $response->body();
                throw new Exception("Error Processing Request: " . $error);
            }
        } catch (RequestException $e) {
            $error = $e->getResponse()->body();
            return response()->json(['error' => 'Request failed, please try again.'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function search($params)
    {
        return $this->getCkanRequest('package_search', $params);
    }


    public function getGroups(){
        $params = [
            'all_fields' => true
        ];
        return $this->getCkanRequest('group_list', $params);
    }

    public function createGroup($name, $title, $description, $imageUrl = null) {
        $body = [
            'name' => $name,
            'title' => $title,
            'description' => $description,
            'image_url' => $imageUrl,
        ];
        return $this->postCkanRequest('group_create', $body);
    }

    public function getDataset($id) {
        $params = [
            'id' => $id
        ];
        return $this->getCkanRequest('package_show', $params);
    }

    public function getResourceViews($resource_id)
    {
        $params = [
            'id' => $resource_id
        ];
        return $this->getCkanRequest('resource_view_list', $params);
    }
}
