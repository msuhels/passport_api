<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::all();
        return response()->json([
        "success" => true,
        "message" => "Project List",
        "data" => $project
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|max:100',
            'introduction' => 'required|max:255',
            'location' => 'required|max:100',
            'cost' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
    
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);        
        }

        $file = $request->file('image');
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('assets/images/'), $imageName);
        $path='assets/images/'.$imageName;

        // $project = Project::create($input);
            $project = new Project;
            $project->name = $input['name'];
            $project->introduction = $input['introduction'];
            $project->location = $input['location'];
            $project->cost = $input['cost'];
            $project->image = $path;
            $project->save();

            return response()->json([
            "success" => true,
            "message" => "Project created successfully.",
            "data" => $project
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return response(['project' => new ProjectResource($project), 'message' => 'Retrieved successfully'], 200);

        $project = Project::find($id);
        if (is_null($project)) {
             return response()->json(['error'=>'Project is not found'], 401); 
        }
        return response()->json([
        "success" => true,
        "message" => "Project retrieved successfully.",
        "data" => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
       
        $input = $request->all();
        // print_r($input);die();
        $project1 = Project::find($project->id);
        // print_r($project1);die();
        if (is_null($project1)) {
             return response()->json(['error'=>'Project is not found'], 401); 
        }
        $validator = Validator::make($input, [
            'name' => 'required|max:100',
            'introduction' => 'required|max:255',
            'location' => 'required|max:100',
            'cost' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        // if($validator->fails()){
        //    return response()->json(['error'=>$validator->errors()], 401);        
        // }
        if(isset($request->image)){
            $file = $request->file('image');
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('assets/images/'), $imageName);
            $path='assets/images/'.$imageName;
         }else{
            $path = $project1->image;
         }

        $project1->name = $input['name'];
        $project1->introduction = $input['introduction'];
        $project1->location = $input['location'];
        $project1->cost = $input['cost'];
        $project1->image = $path;
        $project1->save();
        return response()->json([
            "success" => true,
            "message" => "project updated successfully.",
            "data" => $project1
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json([
        "success" => true,
        "message" => "Project deleted successfully.",
        "data" => $project
        ]);
    }
}