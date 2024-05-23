<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Services\CkanApiService;

class SeedController extends Controller
{
    //

    public function seedGroup(){

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
}
