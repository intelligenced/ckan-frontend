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
        $groups =  collect($ckanService->getGroups()['result']);
    
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle); 
    
            if (isset($header[0]) && strpos($header[0], "\xEF\xBB\xBF") === 0) {
                $header[0] = substr($header[0], 3);
            }
    
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row = array_combine($header, $data);
    
                $datasetFilePath = database_path('seeders/data/'.$row['file']);
                $group = $groups->firstWhere('name', $row['group']);

                $datasetData = [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'group_id' => $group['id']
                 ];

    

                $result = $ckanService->createDataset($datasetData, $datasetFilePath);
                dd($result);
    
                // if (isset($result['error']) && $result['error']) {
                //     continue;
                // }
            }
            fclose($handle);
        } else {
            throw new Exception("Unable to open file at path");
        }
    }
    
    
}
