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

    public function category(Request $request)
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
            return response()->json(['error' => true, 'message' => 'No search criteria provided.'], 400);
        }

        $result = $this->ckanService->search($params);

        if (isset($result['error']) && $result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 500);
        }

        return view('frontpage.category', [
            'selected_group' => $selected_group,
            'groups' => $groups,
            'datasets' => $result['result']['results']
        ]);
    }


    public function data($id)
    {
        $groups = $this->getGroups();
    
        $response = $this->ckanService->getDataset($id);
    
        if (isset($response['error']) && $response['error']) {
            return response()->json(['error' => true, 'message' => $response['message']], 500);
        }
    
    
        return view('frontpage.data', [
            'groups' => $groups,
            'dataset' => $response['result']
        ]);
    }
    
}
