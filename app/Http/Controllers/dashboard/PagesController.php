<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pages;
use App\User;
use App\Category;
use Auth;
use DB;
use Image;
use Carbon\Carbon;
class PagesController extends Controller
{
    
    public function index(Request $request)
    {
        $auth_id=Auth::id();
        $data=Pages::orderBy($request->sort_value,$request->sort_type)->with('user')->with('category');
        if(isset($request->category_id) && !empty($request->category_id))
        {
            $data=$data->where('category_id', $request->category_id);
    
        }
        if(isset($request->start) && !empty($request->start))
        {
            $start= new Carbon($request->start);
            $start=$start->startOfDay();
            $data=$data->where('created_at','>=',$start);
    
        } 
        if(isset($request->end) && !empty($request->end))
        {
            $end= new Carbon($request->end);
            $end=$end->endOfDay();
            $data=$data->where('created_at','<=',$end);
    
        } 
        
        if(isset($request->search) && !empty($request->search))
        {
            $search='%'.$request->search.'%';
            $data=$data->where('title','like',$search)
            ->orWhere('body',  'like', $search);
        }
      
        if(isset($request->show) && !empty($request->show))
        {
            $show=$request->show;
            $data=$data->paginate($show);
    
        }
        else
        {
            $data=$data->paginate(20);
        }
        return $data;
    }

    
    public function store(Request $request)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        $auth_id=Auth::id();
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            $response['data'] = $validator->errors();
            return response()->json($response);
        }

        DB::beginTransaction();
        try {  
            $response['data']=Pages::create(
                [
                    'user_id' => $auth_id,
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'image' => $request->image,
                    'body' => $request->body,
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
        $data=Pages::find($id);
        return $data;
    }

    public function edit(Request $request,$id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
           
                $response['data']=Pages::where('id',$id)
                ->update([
                    'status' => $request->status=='true'?1:0,
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

    public function update(Request $request, $id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            $response['data'] = $validator->errors();
            return response()->json($response);
        }
        $request->validate([
        ]);
        DB::beginTransaction();
        try {
            $auth_id=Auth::id();
            $response['data']=Pages::where('id',$id)
            ->update([
                    'user_id' => $auth_id,
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'image' => $request->image,
                    'body' => $request->body,
            ]);
            DB::commit();
            $response['data']=Pages::with('user')->with('category')->find($id); 
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
        $response['data'] = Pages::find($id);
        if($response['data'])
        {
            
           
                $response['data']=$response['data']->delete();
                $response['status']=true;
          
        }
        else
        {
            $response['data']="Pages not Exist";  
        }
        return response()->json($response);

    }

    
}
