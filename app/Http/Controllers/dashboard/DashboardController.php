<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\User;
use Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $response=array();
        $auth=Auth::user();
        $response['newAdded']=User::whereDate('created_at', Carbon::today())->count();
        $response['totaluser']=User::count();
        $response['todaycategory']=Category::whereDate('created_at', Carbon::today())->count();
        $response['totalcategory']=Category::count();
       
        return $response;
    }
  
}
