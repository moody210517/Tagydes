<?php

namespace Tagydes\Http\Controllers\Web;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tagydes\Http\Controllers\Controller;
use Tagydes\Http\Controllers\Web\UsersController;
use Tagydes\Http\Requests\Reseller\CreateResellerRequest;
use Tagydes\Http\Requests\Reseller\UpdateDetailsRequest;
use Tagydes\Http\Requests\User\CreateUserRequest;
use Tagydes\Repositories\Activity\ActivityRepository;
use Tagydes\Repositories\Country\CountryRepository;
use Tagydes\Repositories\Reseller\ResellerRepository;
use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Repositories\Branch\BranchRepository;
use Tagydes\Reseller;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\Support\Enum\ResellerStatus;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\User;
use Tagydes\Branch;
use DB;

class BranchController extends Controller
{
    private $branch;
    private $users;
    private $userController;

    public function __construct(BranchRepository $branch, UserRepository $users, UsersController $controller)
    {         
        $this->branch = $branch;
        $this->users = $users;
        $this->userController = $controller;                        
    }
        
    public function index(Branch $branch)
    {           
        $user = auth()->user();
        $created_by = -1;
        if($user->getRole() == 1 || $user->getRole() == 2){
            $created_by = $user->getId();
        }else if($user->getRole() == 5){
            $created_by = $user->getCreatedById();
        }

        $branches = $this->branch->paginate(
            $perPage = 20,
            Input::get('search'),
            $created_by             
        );        
        
        return view('branch.list', compact('branches'));
    }

    public function status(Reseller $reseller)
    {        
        $uid = DB::table('reseller_user')->where('reseller_id', $reseller->id)->value('user_id');
        $this->users->status($uid);
        $reseller = $this->resellers->status($reseller->id);
        
        if ($reseller->isActive())
            return redirect()->route('reseller.list')->withSuccess(trans('app.reseller_enabled_success'));

        return redirect()->route('reseller.list')->withSuccess(trans('app.reseller_disabled_success'));    
    }

    public function create(CountryRepository $countryRepository)
    {        
        // Begin variables to form the reason to personalizate actions and buttons
        $edit = false;
        $view = false;
        $create = true;
        // End variables to form the reason to personalizate actions and buttons                


        $user = auth()->user();
        $perPage = 20;
        $query = User::query();            
        if($user->getRole() == 1){ // if admin
            $query->where('role_id', 1);
            $query->where('id', $user->getId());
            $query->orWhere('role_id', 5);
        }else if($user->getRole() == 2){
            $query->where('role_id', 2);
            $query->where('associated_id', $user->getAssociateId());
            $query->orWhere('role_id', 5);            
        }                
        $query->where('created_by', $user->getId());
        $users = $query->orderBy('id', 'desc')
        ->paginate($perPage);
        
        return view('branch.add', compact('countries',  'edit', 'view', 'create', 'users'));
    }

    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => 'Select a Country'] + $countryRepository->lists()->toArray();
    }

    public function store(Request $request)
    {
       
        // When user is created by administrator, we will set his
        // status to Active by default.
        $data = $request->all() + ['status' => ResellerStatus::ACTIVE];
        $user = auth()->user();
        $data['type'] = $user->getRole();
        $data['created_by'] = $user->getId();

        if ( $branch =  $this->branch->create( $data )) {
            if(!empty($_POST['user_lists'])) {             
                DB::table('user_branch')->where('branch_id', $branch->id)->delete();
                foreach($_POST['user_lists'] as $user_id) {                                        
                    DB::table('user_branch')->insert(
                        array('user_id' => $user_id, 'branch_id' => $branch->id)
                    );  
                }
            }
            return redirect()->route('branch.list')->withSuccess(trans('app.reseller_created'));    
        } else {
            return redirect()->route('branch.list')->withErrors(trans('app.reseller_error'));
        }                
    }

    public function show(Reseller $reseller, CountryRepository $countryRepository)
    {
        // Begin variables to form the reason to personalizate actions and buttons
        $edit = false;
        $view = true;
        $create = false;
        // End variables to form the reason to personalizate actions and buttons

        $countries = $this->parseCountries($countryRepository);
        $statuses = $this->getStatuses();
        return view('reseller.view', compact('reseller', 'statuses', 'countries', 'edit', 'view', 'create'));
    }

    public function edit(Branch $branch, CountryRepository $countryRepository)
    {
        // Begin variables to form the reason to personalizate actions and buttons
        $edit = true;
        $view = false;
        $create = false;
        // End variables to form the reason to personalizate actions and buttons    
        $branch = $branch->find($branch->id);


        $user = auth()->user();
        $perPage = 20;
        $query = User::query();            
        if($user->getRole() == 1){ // if admin
            
            $query->where('role_id', 1);
            $query->orWhere('role_id', 5);     

        }else if($user->getRole() == 2){
            
            $query->where('role_id', 2);
            $query->where('associated_id', $user->getAssociateId());
            $query->orWhere('role_id', 5);
            $query->where('associated_id', $user->getAssociateId());

        }
        $query->where('created_by', $user->getId());        
        $users = $query->orderBy('users.id', 'desc')
        ->paginate($perPage);

        return view(
            'branch.edit',
            compact('edit', 'view', 'create','branch', 'users')
        );
    }


    public function update(Branch $branch, UpdateDetailsRequest $request)
    {
        var_dump($branch);
        var_dump($request);        
        // $data = $request->all();
        // $this->branch->update($branch->id, $data);                      
        // return redirect()->route('branch.list')
        // ->withSuccess(trans('app.branch_deleted'));
    }


    public function updateBranch(Branch $branch, Request $request){

        $data = $request->all();
        $this->branch->update(Input::get('branch_id'), $data);
            
        var_dump($request->input('user_lists'));

        if(!empty($_POST['user_lists'])) {                          
            DB::table('user_branch')->where('branch_id', Input::get('branch_id'))->delete();
            foreach($request->get('user_lists') as $user_id) {                                        
                DB::table('user_branch')->insert(
                    array('user_id' => $user_id, 'branch_id' => Input::get('branch_id'))
                );
            }
        }

      //  $roles = $request->get('user_lists');

        // $chb = $_POST['user_lists'];
        // $num = $_POST['num'];
        // $result = array();
        // for($i=0; $i<$num; $i++) {
        //     $user_id = $chb[$i];
        //     DB::table('user_branch')->where('branch_id', Input::get('branch_id'))->delete();
        //     foreach($_POST['user_lists'] as $user_id) {                                        
        //         DB::table('user_branch')->insert(
        //             array('user_id' => $user_id, 'branch_id' => Input::get('branch_id'))
        //         );
        //     }
        // }
    
          return redirect()->route('branch.list')
          ->withSuccess(trans('app.branch_deleted'));
    }

    public function destroy(Reseller $reseller)
    {
        //
    }

    public function delete(Branch $branch)
    {                
        $check = DB::table('user_branch')->where('branch_id', $branch->id)->get();
        if($check->first()){
            return redirect()->route('branch.list')
            ->withSuccess(trans('Can not delete this branch'));
        }else{
            $this->branch->delete($branch->id);
        }
        
        return redirect()->route('branch.list')
        ->withSuccess(trans('app.branch_deleted'));
    }

    private function verifyCanManageReseller(Reseller $userReseller, Reseller $reseller) {
        abort_unless($userReseller->id == $reseller->id, 403);
        return true;
    }

    private function getStatuses()
    {
        return ResellerStatus::lists();
    }
}
