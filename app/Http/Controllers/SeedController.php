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
        $result = $ckanService->createGroup(
            'example-name', 
            'Example Title', 
            'This is a description.',
            'http://example.com/image.jpg'
        );
        
        return response()->json($result);
    }
}
