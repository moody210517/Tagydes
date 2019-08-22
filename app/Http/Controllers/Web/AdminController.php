<?php

namespace Tagydes\Http\Controllers\Web;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tagydes\Customer;
use Tagydes\Events\User\Banned;
use Tagydes\Events\User\Deleted;
use Tagydes\Events\User\UpdatedByAdmin;
use Tagydes\Http\Controllers\Controller;
use Tagydes\Http\Requests\User\CreateUserRequest;
use Tagydes\Http\Requests\User\UpdateDetailsRequest;
use Tagydes\Http\Requests\User\UpdateLoginDetailsRequest;
use Tagydes\Lib\ResellerOrCustomer;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Country\CountryRepository;
use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Reseller;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\User;
use Tagydes\Role;
use Tagydes\News;

/**
 * Class UsersController
 * @package Tagydes\Http\Controllers
 */
class AdminController extends Controller
{

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        //$this->middleware('permission:users.manage');        
    }
    
    public function news(Request $request)
    {

        $news = News::paginate(
            $perPage = 20,
            Input::get('search')            
        );
        return view('news.list', compact('news'));        
    }

    
    public function createNews(Request $request)
    {            
        
        if($request->has('title')){
            // get image             
            $title = $request->input('title');
            $desc = $request->input('description');
            $file = $request->file('image');  
            $video = $request->input('video');

            if($file){
                $fileName =  $file->getClientOriginalName();            
                $extension = $file->getClientOriginalExtension();
                $realPath = $file->getRealPath();        
                $fileSize = $file->getSize();
                $fileMimeType = $file->getMimeType();                        
                $destinationPath = 'uploads';
                $file->move($destinationPath,$file->getClientOriginalName());                               

                $news = new News();
                $news->title = $title;
                $news->description = $desc;
                $news->image = 'uploads/'.$fileName;
                $news->video = str_replace('watch?v=', 'embed/', $video);;
                $news->save();
            }else{
                $news = new News();
                $news->title = $title;
                $news->description = $desc;                
                $news->video = str_replace('watch?v=', 'embed/', $video);;
                $news->save();
            }            
            return redirect('news');
        }else{
            return view('news.add');   
        }                   
    }

    
    public function editNews(News $news)
    {                        
        return view('news.edit',compact('news'));        
    }

    public function deleteNews(News $news){
        
        News::where('id', $news->id)->delete();
        return redirect('news')->withSuccess(trans('deleted'));
    }

    public function updateNews(Request $request){
        $id = $request->input('id');
        $file = $request->file('image');  
        $data = [];
        if($file){
            $fileName =  $file->getClientOriginalName();            
            $extension = $file->getClientOriginalExtension();
            $realPath = $file->getRealPath();        
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();                        
            $destinationPath = 'uploads';
            $file->move($destinationPath,$file->getClientOriginalName());                                           
            $data = ['title'=>$request->input('title') , 'description' => $request->input('description') , 'video' => $request->input('video') , 'image' => 'uploads/'.$fileName ];
        }else{
            $data = ['title'=>$request->input('title') , 'description' => $request->input('description') , 'video' => $request->input('video')];
        }        
        News::where('id', $id)->update($data);
        return redirect('news')->withSuccess(trans('app.reseller_updated'));
    }

}
