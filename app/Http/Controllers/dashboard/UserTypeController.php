<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\UserType;
use Mail;
use Session;
use Redirect;
use DB;
use Carbon\Carbon;
class UserTypeController extends Controller
{
   
    public function index()
    {
        $auth=Auth::user();
        $data=UserType::orderBy('id', 'DESC');
        if($auth->user_type_id==1)      //for admin
        {
            $data=$data->whereNotin('id',[1,3,4]);
        }
        else if($auth->user_type_id==2)
        {
            $data=$data->wherein('id',[3,4]);   // BDM
        }
        else if($auth->user_type_id==3)
        {
            $data=$data->wherein('id',[4]);   // For Executive
        }
        else if($auth->user_type_id==3)
        {
            $data=$data->wherein('id',[4]);   // For Executive
        }

        else if($auth->user_type_id==5)
        {
            $data=$data->wherein('id',[6]);   // For Executive
        }
        
        $data=$data->get();
        return $data;
    }

    public  function userTypeListForAdmin()
    {
        $auth=Auth::user();
        $data=UserType::orderBy('id', 'DESC')
        ->where('id','!=', 1)
        ->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        // dd($request->all());
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
        $auth_id=Auth::id();
        $response['data']=UserType::create(
            [
                'user_id' => $auth_id,
                'name' => $request->name
            ]
        );
        DB::commit();
        $response['status'] = true;
        }
        catch (\Exception $e) {
            $response['data']=$e->getMessage();
            $response['status'] = false;
            DB::rollback();
        }
        return response()->json($response);
    }

   
    public function show($id)
    {
        $data=UserType::find($id);
        return $data;
    }

   
    public function edit($id)
    {
       
    }

   
    public function update(Request $request, $id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
            $response['data']=UserType::where('id',$id)
             ->update(
            [
                'name' => $request->name
            ]
        );
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
        $response['data'] = UserType::find($id);
        
        if($response['data'])
        {
            $usercounter= \App\User::where('type',$id)->count();
            if($usercounter==0)
            {
                $response['data']=$response['data']->delete();
                $response['status']=true;
            }
            else
            {
                $response['data']="Users exist under this User Type";

            }   
        }
        else
        {
            $response['data']="Data Not deleted";  
        }
        return response()->json($response);
    }            

    
}
