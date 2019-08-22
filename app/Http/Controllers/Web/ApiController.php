<?php

namespace Tagydes\Http\Controllers\Web;

use Auth;
use Tagydes\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tagydes\Customer;
use Tagydes\User;
use Tagydes\Role;
use Tagydes\News;
use Response;
use DB;


/**
 * Class UsersController
 * @package Tagydes\Http\Controllers
 */
class ApiController extends Controller
{

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        //$this->middleware('permission:users.manage');        
    }
    
    public function udpateUserBranch(Request $request){
                    
        //var_dump($request->input('user_lists'));
        $checked = $request->input('checked');
        $user_id = $request->input('user_id');
        $branch_id = $request->input('branch_id');

        if( $user_id != null && $branch_id != null){            
            if(Input::get('checked') == 'true') {
                $row = DB::table('user_branch')->where('user_id', $user_id)->where('branch_id', $branch_id)->get();        
                if(!$row->first()){
                    DB::table('user_branch')->insert(
                        array('user_id' => Input::get('user_id'), 'branch_id' => $branch_id)
                    );
                }
            }else{
 
                DB::table('user_branch')->where('user_id',$user_id)->where('branch_id',$branch_id)->delete();     
            }
    
            $a = array('results'=>200, 'msg'=>$user_id);
            return Response::json($a);    
        }else{
            $a = array('results'=>300, 'msg'=>$request->all());
            return Response::json($a); 
        }
        
    }


    
}
