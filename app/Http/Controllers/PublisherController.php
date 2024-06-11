<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Dataset;
use App\Services\CkanApiService;
use Auth;

class PublisherController extends Controller
{

    protected $ckanService;

    public function __construct(CkanApiService $ckanService)
    {
        $this->ckanService = $ckanService;
    }


    public function create(): View
    {
        $groups = collect($this->ckanService->getGroups()['result'])->pluck('display_name','id');
        return view('publisher.create', ['groups' => $groups]);
    }
    //

    public function index(): View {
        $datasets = Dataset::where('organisation_id', Auth::user()->organisation_id)->get();
        return view('publisher.index',['datasets' => $datasets]);
    }

    public function store(Request $request)
    {
        $dataset = new Dataset;
        $dataset->name = $request->name;
        $dataset->is_embedable = $request->has('is_embedable') ? 1 : 0;
        $dataset->is_published = $request->has('is_published') ? 1 : 0;
        $dataset->is_api = $request->has('is_api') ? 1 : 0;        
        $dataset->organisation_id = Auth::user()->organisation_id;

        $datasetData = [
            'title' => $request->name,
            'description' => $request->description,
            'tags' => $request->tags,
            'group_id' => $request->group_id
        ];

        $result = $this->ckanService->createDataset($datasetData);

        if (isset($result['result']) && isset($result['result']['id'])) {
            $packageId = $result['result']['id'];
            $dataset->dataset_id = $packageId;

            $result = $this->ckanService->uploadResourceFromUploadedFile($request->file('file'), $packageId);
            if (isset($result['success']) && $result['success'] == true) {
                $dataset->save();
            }else{
                $this->ckanService->deleteDataset($packageId);
            }
        }

        return redirect()->back();

    }


    public function update($id, Request $request)
    {
        $dataset = Dataset::find($id);
        if (!$dataset) {
            return back()->withErrors('Dataset not found.');
        }
    
        $initialIsPublished = $dataset->is_published;
    
        $dataset->is_embedable = $request->is_embedable;
        $dataset->is_published = $request->is_published;
        $dataset->is_api = $request->is_api;
        $dataset->update();
    
        if ($initialIsPublished != $request->is_published) {
            $newState = $request->is_published ? 'active' : 'inactive';
            $result = $this->ckanService->updateDatasetState($dataset->dataset_id, $newState);
        }
    
        return redirect()->back();
    }


    public function destroy($id){
        $dataset = Dataset::find($id);
        if (!$dataset) {
            return back()->withErrors('Dataset not found.');
        }

        $result = $this->ckanService->deleteDataset($dataset->dataset_id);
        $dataset->delete();

        return redirect()->back();

    }
    
}
     