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

    private function getGroups(){
        $response = $this->ckanService->getGroups();
        return $response['result'];
    }

    private function getTags(){
        $response  = $this->ckanService->getTags();
        return $response['result'];
    }
    private function getOrganisations(){
        $response  = $this->ckanService->getOrganisations();
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
        $tags = $this->getTags();
        $organisations = $this->getOrganisations();
    
        $selected_group_name = $request->input('group');
        $selected_group = collect($groups)->firstWhere('name', $selected_group_name);
    
        $params = [];
        $searchTerms = [];
    
        if ($request->has('group')) {
            $searchTerms[] = 'groups:' . $request->input('group');
        }
    
        if ($request->has('tag')) {
            $searchTerms[] = 'tags:' . $request->input('tag');
        }
    
        if ($request->has('name')) {
            $searchTerms[] = 'name:*' . $request->input('name') . '*'; 
        }

        if ($request->has('organization')) {
            $searchTerms[] = 'organization:' . $request->input('organization'); 
        }
    
        if (!empty($searchTerms)) {
            $params['q'] = implode(' AND ', $searchTerms);
        } else {
            $params['sort'] = 'metadata_modified desc';
            $params['rows'] = 10; 
        }
    
        $result = $this->ckanService->search($params);
    
        if (isset($result['error']) && $result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 500);
        }
    
        return view('frontpage.explore', [
            'selected_group' => $selected_group,
            'groups' => $groups,
            'tags' => $tags,
            'organisations' => $organisations,
            'datasets' => $result['result']['results']
        ]);
    }
    

    public function data($id)
    {
        $groups = $this->getGroups();
        $tags = $this->getTags();
        $organisations = $this->getOrganisations();
    
        $response = $this->ckanService->getDatasetWithResources($id);
        
    
        if (isset($response['error']) && $response['error']) {
            return response()->json(['error' => true, 'message' => $response['message']], 500);
        }
    
    
        return view('frontpage.data', [
            'groups' => $groups,
            'tags' => $tags,
            'organisations' => $organisations,
            'dataset' => $response['result']
        ]);
    }
    
}
