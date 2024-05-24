<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CkanApiService;

class FrontpageController extends Controller
{
    protected $ckanService;

    public function __construct(CkanApiService $ckanService)
    {
        $this->ckanService = $ckanService;
    }

    private function getGroups()
    {
        $response = $this->ckanService->getGroups();
        return $response['result'];
    }

    public function index()
    {
        $groups = $this->getGroups();

        return view('frontpage.index', ['groups' => $groups]);
    }

    public function explore(Request $request)
    {
        $groups = $this->getGroups();

        $selected_group_name = $request->input('group');
        $selected_group = collect($groups)->firstWhere('name', $selected_group_name);

        $params = [];

        if ($request->has('group')) {
            $params['q'] = 'groups:' . $request->input('group');
        }

        if ($request->has('tag')) {
            $params['q'] = 'tags:' . $request->input('tag');
        }

        if ($request->has('name')) {
            $params['q'] = 'name:' . $request->input('name');
        }

        if (empty($params)) {
            $params['sort'] = 'metadata_modified desc'; // Assuming 'metadata_modified' is the relevant field for sorting
            $params['rows'] = 10; // You can adjust the number of rows as necessary
        }

        $result = $this->ckanService->search($params);

        if (isset($result['error']) && $result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 500);
        }

        return view('frontpage.explore', [
            'selected_group' => $selected_group,
            'groups' => $groups,
            'datasets' => $result['result']['results']
        ]);
    }


    public function data($id)
    {
        $groups = $this->getGroups();
    
        $response = $this->ckanService->getDatasetWithResources($id);
        
    
        if (isset($response['error']) && $response['error']) {
            return response()->json(['error' => true, 'message' => $response['message']], 500);
        }
    
    
        return view('frontpage.data', [
            'groups' => $groups,
            'dataset' => $response['result']
        ]);
    }
    
}
