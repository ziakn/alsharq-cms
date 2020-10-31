<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Privilige;
use App\Pages;
use Mail;
use App\PageUser;
use Session;
use Redirect;
use DB;
use Carbon\Carbon;
class PriviligeController extends Controller
{
    
    public function index()
    {
        $data = Privilige::get();
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
        DB::beginTransaction();
        try {
                Privilige::where('user_id',$request->user_id)
                ->update(
                    [
                        'pages'=> $request->pages,
                        'setting'=> $request->setting,
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

   
    public function show($id)
    {
        $data = Privilige::where('user_id', $id)->first();
        return $data;
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

  
    public function destroy($id)
    {
        //
    }

    public function getAdminUser()
    {
        $data = User::get();
        return $data;
    }

    public function getuserpages($id)
    {
        // dd($id);
        $data = Privilige::where('user_id',$id)
        ->first();
        return $data;
    }

    public function getuserType($id)
    {
        // dd($id);
        $data = User::find($id);
        return $data;
    }
}
