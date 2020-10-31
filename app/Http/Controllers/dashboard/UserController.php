<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use App\User;
use App\Privilige;
use Session;
use Redirect;
use DateTime;
use Mail;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $data=User::orderBy('id','DESC')->with('type');    
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
    public function profile()
    {
        $data=Auth::user();
        return $data;
    }
    public function store(Request $request)
    {

        $response=array();
        $response['status']=false;
        $response['data'] ='';
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'mobile' => ['required', 'string','min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => false
        ],200);
        }

            DB::beginTransaction();
            try {
            
                $data=new User;
                $data->name=$request->input('name');
                $data->email=$request->input('email');
                $data->mobile=$request->input('mobile');
                $data->address=$request->input('address');
                $data->type=$request->input('type');
                $data->password=bcrypt($request->input('password'));
                $data->save(); 
                
                //to roles migration
                Privilige::create(
                    [
                        'user_id'=> $data->id,
                        'pages'=> 0,
                        'setting'=> 0,
                    ]
                );
                DB::commit();
                $response['data']=User::with('type')->find($data->id);  
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
        // dd($request->all());
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
           User::where('id',$id)
             ->update(
            [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'type' => $request->type,
            ]
        );
        DB::commit();
        $response['data']=User::with('type')->find($id);  
        $response['status'] = true;
        } catch (\Exception $e) {
            $response['data']=$e->getMessage();
            $response['status'] = false;
            DB::rollback();
        }
            
        return response()->json($response);
     }

     public function edit(Request $request,$id)
    {
        $response=array();
        $response['status']=false;
        $response['data'] ='';
        DB::beginTransaction();
        try {
           
                $response['data']=User::where('id',$id)
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

     public function logout()
     {
         $user_id = Auth::id();
         Auth::logout();
         Session::flush();
         return Redirect::to('/');
     }
     public function avatar(Request $request)
     {
         $user_id = Auth::id();
         //return $request->all();
         $request->file('myFile')->store('public/uploads/avatar');
         $pic= '/storage/uploads/avatar/'.$request->myFile->hashName();   
         $update=User::where('id', $user_id)->update([
             'image' => $pic
         ]);
         if($update)
         {
          return response()->json([
              'data' => $pic,
              'status' => true
          ],200);
          }
          return response()->json([
              'data' => 'Failed',
              'status' => false
          ],200);
     }
     public function updatepassword(Request $request)
     {
          $update=User::where('id',$request->id)->update(
             [
                 'password'=>bcrypt($request->password),
                 'status' => 2
             ]
          );
          $data['password'] =$request->password;
          $data['email'] =$request->email;
          Mail::send('mailview', $data, function($message) use ($request) {
              $message->to( $request->email , $request->name )
              ->subject('Agent Password for Homeyfi');
          });
         return $update;
     }
     public function changePass(Request $request)
     {
         
         $request->validate([
             'newPassword' => ['required'],
             'confirmPassword' => ['same:newPassword'],
         ]);
         if(!Hash::check($request->oldPassword,Auth::user()->password))
         {
             return response()->json(
                 [
                     'status'=> false,
                     'message'=> 'Current Password dose not matched'
                 ], 200);
         }
         else
         {                     
             $update=User::find(auth()->user()->id)->update(['password'=> Hash::make($request->newPassword)]);  
             if($update)   
             {
                 return response()->json(
                     [
                         'status'=> true,
                         'message'=> 'Successfuly Changed'
                     ], 200);
             } 
             else
             {
                 return response()->json(
                     [
                         'status'=> false,
                         'message'=> 'Failed, Try again'
                     ], 200);
             }
 
         }
     }

     public function destroy($id)
     {
         $response=array();
         $response['status']=false;
         $response['data'] = User::find($id);
         
         if($response['data'])
         {
             $pages= \App\Pages::where('user_id',$id)->count();
             $cat= \App\Category::where('user_id',$id)->count();
            
             if($pages==0 || $cat==0)
             {
                Privilige::where('user_id',$id)->delete();
                 $response['data']=$response['data']->delete();
                 $response['status']=true;
             }
             else
             {
                 if($cat > 0)
                 {
                    $response['data']="Users exist with multiple  Categories";
                 }
                 if($cat > 0)
                 {
                    $response['data']="Users exist with multiple  Pages";
                 }
                
 
             } 
         }
         else
         {
             $response['data']="Data Not deleted";  
         }
         return response()->json($response);
     }    
}
