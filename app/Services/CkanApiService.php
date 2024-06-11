<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;
use Illuminate\Http\UploadedFile;

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
    
    public function uploadResource($packageId, $filePath) {
        try {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($filePath);
            $fileName = basename($filePath);
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Accept' => 'application/json'
            ])->attach(
                'upload', fopen($filePath, 'r'), $fileName,
                ['Content-Type' => $mimeType]  
            )->post($this->ckanApiPath . 'resource_create', [
                'package_id' => $packageId,   
                'name' => $fileName,         
                'format' => strtoupper($extension),
                'mimetype' => $mimeType
            ]);
            fclose(fopen($filePath, 'r')); 
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


    public function uploadResourceFromUploadedFile(UploadedFile $file, $packageId) {
        if (!$file->isValid()) {
            return [
                'error' => true,
                'message' => 'Invalid file upload.'
            ];
        }
    
        try {
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $fileName = $file->getClientOriginalName();
    
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
                'Accept' => 'application/json'
            ])->attach(
                'upload', file_get_contents($file->path()), $fileName
            )->post($this->ckanApiPath . 'resource_create', [
                'package_id' => $packageId,
                'name' => $fileName,
                'format' => strtoupper($extension),
                'mimetype' => $mimeType
            ]);
    
            if (isset($response->json()['success']) && $response->json()['success']) {
                return ['success' => true, 'message' => 'File uploaded successfully.'];
            } else {
                return ['error' => true, 'message' => 'Failed to upload file.'];
            }
    
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => 'Network error or request timed out: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteDataset($datasetId) {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->ckanApiToken,
            ])->post($this->ckanApiPath . 'package_delete', ['id' => $datasetId]);
    
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
    
    public function createDataset($data) {
        $sanitizedTitle = strtolower($data['title']);
        $sanitizedTitle = preg_replace('/[^a-z0-9_\-]/', '_', $sanitizedTitle); 

        $dataset = [
            'name' => $sanitizedTitle,
            'title' => $data['title'],
            'notes' => $data['description'],
            'owner_org' => $this->ckanSeedOrganisationId,
            'groups' => [
                ['id' => $data['group_id']]
            ]
        ];

        if (isset($data['tags']) && is_string($data['tags'])) {
            $tagsArray = explode(',', $data['tags']);
            $dataset['tags'] = array_map(function($tag) {
                return ['name' => trim($tag)]; 
            }, $tagsArray);
        }
        if (isset($data['extras']) && is_array($data['extras'])) {
            $dataset['extras'] = array_map(function($key, $value) {
                return ['key' => $key, 'value' => $value];
            }, array_keys($data['extras']), $data['extras']);
        }
    
        return $this->postCkanRequest('package_create', $dataset);
    }

    public function updateDatasetState($id, $state) {
        if (!in_array($state, ['active', 'inactive', 'deleted'])) {
            return [
                'error' => true,
                'message' => "Invalid state value. Allowed values are 'active', 'inactive', or 'deleted'."
            ];
        }

        $dataset = $this->getDataset($id)['result'];
        $dataset['state'] = $state;
    
        return $this->postCkanRequest('package_update', $dataset);
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
