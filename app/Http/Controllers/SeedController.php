<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Services\CkanApiService;

class SeedController extends Controller
{
    //

    public function seedGroups(){

        $ckanService = new CkanApiService();

        $filePath = database_path('seeders/groups.json');
        $json = file_get_contents($filePath);
        $groups = json_decode($json, true)['categories'];

        $results = [];

        foreach ($groups as $group) {
            $result = $ckanService->createGroup(
                $group['name'],
                $group['title'],
                $group['description'],
                isset($group['image_url']) ? $group['image_url'] : null
            );
            $results[] = $result;
        }

        return response()->json($result);
    }
    public function seedDatasets() {
        $ckanService = new CkanApiService();
        $filePath = database_path('seeders/dataSeeder.csv');
        $groupsResponse = $ckanService->getGroups();
    
        if (!isset($groupsResponse['result'])) {
            throw new Exception("Failed to fetch groups: " . $groupsResponse['message']);
        }
    
        $groups = collect($groupsResponse['result']);
        
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle); 
    
            if (isset($header[0]) && strpos($header[0], "\xEF\xBB\xBF") === 0) {
                $header[0] = substr($header[0], 3);
            }
            
            echo "Starting dataset seeding process...\n<br>";
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row = array_combine($header, $data);
                $datasetFilePath = database_path('seeders/data/'.$row['file']);
                $group = $groups->firstWhere('name', $row['group']);
    
                if (!$group) {
                    echo "Group not found for " . $row['title'] . ", skipping dataset.\n<br>";
                    continue;
                }
    
                $datasetData = [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'tags' => $row['tags'],
                    'group_id' => $group['id'] ?? null
                ];
    
                echo "Creating dataset: " . $row['title'] . "...\n<br>";
                $result = $ckanService->createDataset($datasetData);
    
                if (isset($result['result']) && isset($result['result']['id'])) {
                    $packageId = $result['result']['id'];
                    echo "Uploading resource for dataset: " . $row['title'] . "...\n<br>";
                    $result = $ckanService->uploadResource($packageId, $datasetFilePath);
    
                    if (isset($result['error']) && $result['error']) {
                        echo "Error uploading resource for " . $row['title'] . ": " . $result['message'] . "\n<br>";
                        continue;
                    } else {
                        echo "Resource uploaded successfully for " . $row['title'] . "\n<br>";
                    }
                } else {
                    echo "Error creating dataset " . $row['title'] . ": " . $result['message'] . "\n<br>";
                    continue;
                }
            }
            fclose($handle);
            echo "Dataset seeding process completed.\n<br>";
        } else {
            throw new Exception("Unable to open file at path: $filePath");
        }
    }


    public function deseedDatasets() {
        $ckanService = new CkanApiService();
        $datasetsResponse = $ckanService->search(['rows' => 1000]); 
    
        if (isset($datasetsResponse['error']) && $datasetsResponse['error']) {
            throw new Exception("Failed to fetch datasets: " . $datasetsResponse['message']);
        }
    
        $datasets = $datasetsResponse['result']['results'];
    
        echo "Starting deletion of all datasets...\n<br>";
    
        foreach ($datasets as $dataset) {
            $datasetId = $dataset['id'];
            $deleteResponse = $ckanService->deleteDataset($datasetId);
    
            if (isset($deleteResponse['error']) && $deleteResponse['error']) {
                echo "Failed to delete dataset ID {$datasetId}: " . $deleteResponse['message'] . "\n<br>";
            } else {
                echo "Successfully deleted dataset ID {$datasetId}\n<br>";
            }
        }
    
        echo "All datasets have been deleted.\n<br>";
    }
    
    
    
    
    
}
