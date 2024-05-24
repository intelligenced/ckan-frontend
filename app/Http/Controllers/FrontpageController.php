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
    
        // Initialize the query parameters array.
        $params = [];
        $searchTerms = [];
    
        // Append group, tag, and name filters to the search query if they exist.
        if ($request->has('group')) {
            $searchTerms[] = 'groups:' . $request->input('group');
        }
    
        if ($request->has('tag')) {
            $searchTerms[] = 'tags:' . $request->input('tag');
        }
    
        if ($request->has('name')) {
            $searchTerms[] = 'name:*' . $request->input('name') . '*'; // Adding wildcards to allow partial matches.
        }
    
        // If specific search terms are added, join them with AND; otherwise, fetch latest datasets.
        if (!empty($searchTerms)) {
            $params['q'] = implode(' AND ', $searchTerms);
        } else {
            $params['sort'] = 'metadata_modified desc';
            $params['rows'] = 10; // Fetches the latest 10 datasets by default if no specific search criteria are provided.
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
