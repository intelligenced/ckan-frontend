<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

class CkanApiService {
    protected $ckanApiPath;
    protected $ckanInternalPath;
    protected $ckanPublicPath;
    protected $ckanApiToken;
    protected $ckanSeedOrganisationId;
    protected $ckanContentType = "application/json";

    public function __construct() {
        $this->ckanApiToken = env('CKAN_API_TOKEN');
        $this->ckanInternalPath = env('CKAN_INTERNAL_URL');
        $this->ckanPublicPath = env('CKAN_PUBLIC_URL');
        $this->ckanSeedOrganisationId = env('CKAN_SEED_ORGANISATION_ID');
        $this->ckanApiPath = $this->ckanInternalPath."/api/3/action/";
    }

    private function handleErrorResponse($response) {
        if ($response->failed()) {
            $error = $response->json();
            dd($error);
            $message = $error['error']['message'] ?? 'An unknown error occurred.';
            throw new Exception("Error Processing Request: " . $message);
        }
        return $response->json();
    }


    public function getCkanRequest($endpoint, $params) {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Content-Type' => $this->ckanContentType,
            ])->get($this->ckanApiPath . $endpoint, $params);
        
            return $this->handleErrorResponse($response);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => 'Network error or request timed out: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function postCkanRequest($endpoint, $body) {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Content-Type' => $this->ckanContentType,
            ])->post($this->ckanApiPath . $endpoint, $body);
        
            return $this->handleErrorResponse($response);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => 'Network error or request timed out: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function postCkanRequestMultipart($endpoint, $body, $filePath) {
        try {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($filePath);

            $body[] = [
                'name'     => 'resources[0][format]',
                'contents' => strtoupper($extension)
            ];
            $body[] = [
                'name'     => 'resources[0][mimetype]',
                'contents' => $mimeType
            ];
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Accept' => 'application/json',
            ])->attach('resources[0][upload]', file_get_contents($filePath), basename($filePath),['Content-Type' => $mimeType])
            ->post($this->ckanApiPath . $endpoint, $body);
    
            return $this->handleErrorResponse($response);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => 'Network error or request timed out: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
    
    


    public function search($params){
        return $this->getCkanRequest('package_search', $params);
    }

    public function getGroups(){
        $params = ['all_fields' => true];
        return $this->getCkanRequest('group_list', $params);
    }

    public function getTags() {
        $params = ['all_fields' => true];
        return $this->getCkanRequest('tag_list', $params);
    }

    public function getOrganisations() {
        $params = ['all_fields' => true];
        return $this->getCkanRequest('organization_list', $params);
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
    
    public function createDataset($data, $filePath) {

        $body = [
            [
                'name'     => 'name',
                'contents' => strtolower(str_replace(' ', '_', $data['title']))

            ],
            [
                'name'     => 'title',
                'contents' => $data['title']
            ],
            [
                'name'     => 'notes',
                'contents' => $data['description']
            ],
            [
                'name'     => 'owner_org',
                'contents' =>  $this->ckanSeedOrganisationId,
            ],
        ];
    
        if (isset($data['group_id'])) {
            $body[] = [
                'name'     => 'groups[0][id]',
                'contents' => $data['group_id']
            ];
        }

    
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $index => $tag) {
                $body[] = [
                    'name'     => "tags[$index][name]",
                    'contents' => $tag
                ];
            }
        }
    
        if (isset($data['extras'])) {
            foreach ($data['extras'] as $key => $value) {
                $body[] = [
                    'name'     => "extras[$key][key]",
                    'contents' => $key
                ];
                $body[] = [
                    'name'     => "extras[$key][value]",
                    'contents' => $value
                ];
            }
        }


    
        return $this->postCkanRequestMultipart('package_create', $body, $filePath);
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

    public function getDatasetWithResources($id) {
        $dataset = $this->getDataset($id);
    
        if (isset($dataset['error']) && $dataset['error']) {
            return $dataset;
        }
    
        if (!isset($dataset['result']['resources'])) {
            return [
                'error' => true,
                'message' => 'No resources found in the dataset.'
            ];
        }
    
        foreach ($dataset['result']['resources'] as &$resource) {
            $viewsResponse = $this->getResourceViews($resource['id']);

            // $resource['embed_url'] = 'http://ckan.localhost/dataset/' . $id . '/resource/' . $resource['id'] . '/view/' . $resource['views'][0]['id'];
            
            if (!isset($viewsResponse['error'])) {
                $resource['views'] = $viewsResponse['result'] ?? [];
                $resource['embed_url'] = !empty($resource['views']) ? $this->ckanPublicPath . '/dataset/' . $id . '/resource/' . $resource['id'] . '/view/' . $resource['views'][0]['id'] : null;
            }

            $resource['api_url'] = isset($resource['datastore_active']) && $resource['datastore_active']
                ? $this->ckanApiPath . "datastore_search?resource_id=" . $resource['id']
                : null;
        }
    
        return $dataset;
    }
    
}
