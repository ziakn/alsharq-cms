<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Category;
use Auth;
use DB;
class CategoryController extends Controller
{

    public function index(Request $request)
    {
      $data=Category::orderBy('id','DESC');
      if(isset($request->show) && !empty($request->show))
      {
          $show=$request->show;
          $data=$data->paginate($show);
      }
      else
      {
          $data=$data->get();
      }
      return $data;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        $auth_id=Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            $response['data'] = $validator->errors();
            return response()->json($response);
        }

        DB::beginTransaction();
        try {  
            $response['data']=Category::create(
                [
                    'user_id' => $auth_id,
                    'name' => $request->name,
                    'image' => $request->image,
                    'description' => $request->description,
                    'status' => 1,
                ]
            );
            $response['status'] = true;
            DB::commit();
        } catch (\Exception $e) {
            $response['data']=$e->getMessage(). ', Line: '.$e->getLine();
            $response['status'] = false;
            DB::rollback();
        }
            
        return response()->json($response);
    }

    public function show($id)
    {
        $data=Category::find($id);
        return $data;
    }

    public function edit(Request $request, $id)
    {
    }

    public function update(Request $request, $id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            $response['data'] = $validator->errors();
            return response()->json($response);
        }
        $request->validate([
        ]);
        DB::beginTransaction();
        try {
            $response['data']=Category::where('id',$id)
            ->update([
                'name' => $request->name,
                'image' => $request->image,
                'description' => $request->description,
                'status' => $request->status,
            ]);
            DB::commit();
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            $response['status'] = false;
            DB::rollback();
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] = Category::find($id);
        if($response['data'])
        {
            $branchcounter= \App\Pages::where('category_id',$id)->count();
            if($branchcounter==0)
            {
                $response['data']=$response['data']->delete();
                $response['status']=true;
            }
            else
            {
                $response['data']="Pages exist under this category";

            }   
        }
        else
        {
            $response['data']="Pages not Exist";  
        }
        return response()->json($response);

    }

    
}
