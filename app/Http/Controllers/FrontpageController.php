<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CkanApiService;

class FrontpageController extends Controller
{

    public function index(){
        $ckanService = new CkanApiService();

        $response = $ckanService->getGroups();
        $groups = $response['result'];

        return view('frontpage.index', ['groups' => $groups]);
    }
    //
}
