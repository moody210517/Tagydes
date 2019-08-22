<?php

namespace Tagydes\Http\Controllers\Web;

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
use Tagydes\Repositories\Branch\BranchRepository;
use Tagydes\Repositories\Customer\CustomerRepository;
use Tagydes\Repositories\Session\SessionRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Reseller;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\Support\Enum\ResellerStatus;
use Tagydes\Support\Enum\UserStatus;
use Tagydes\User;
use DB;
use Tagydes\Branch;
use Tagydes\Customer;
use Tagydes\Support\Enum\CustomerStatus;

class ResellersController extends Controller
{
    private $resellers;
    private $users;
    private $userController;
    private $customers;
    public function __construct(ResellerRepository $resellers, UserRepository $users, UsersController $controller, CustomerRepository $customers)
    { 
        
        $this->resellers = $resellers;
        $this->users = $users;
        $this->userController = $controller;
        $this->customers = $customers;
    }
        
    public function index(Reseller $reseller = null, Request $request)
    {        
        if ($reseller) {
            $userReseller = $request->user()->resellers()->first();
            $request->user()->role_id > 1 ? $this->verifyCanManageReseller($userReseller, $reseller) : '';
            $resellers = $this->resellers->paginate(
                $perPage = 20,
                Input::get('search'),
                Input::get('status'),
                $reseller->id
            );
        } else {

            $role = auth()->user()->getRole();
            if($role == 5){

                $status = Input::get('status');
                $search = Input::get('search');
                $query = Reseller::query();                        
                if ($status) {            
                    $query->where('status', $status);
                }        
                 
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('company_name', "like", "%{$search}%");
                    });
                }

                $query->join('user_branch', 'user_branch.branch_id', 'resellers.branch_id');
                $query->join('users', 'users.id', 'user_branch.user_id');
                $query->where('user_id', auth()->user()->getId());      
                $query->select('resellers.*');         
                $resellers = $query->orderBy('user_id', 'desc')
                ->paginate(20); 
                
            }else{
                $resellers = $this->resellers->paginate(
                    $perPage = 20,
                    Input::get('search'),
                    Input::get('status')
                );
            }
        }
        
        $statuses = ['' => trans('app.all')] + ResellerStatus::lists();
        return view('reseller.list', compact('resellers', 'statuses', 'reseller' ));
    }

    public function branchlist(Reseller $reseller = null, Customer $customer = null,  Request $request)
    {        
        $branch_id = $request->input('branch_id');
        $branch = Branch::where('id',$branch_id)->get();
        $user = auth()->user();
        if($user->getRole() == 1 || ($user->getRole() == 5 && $user->getAssociateId() == 0)  ){
            $resellers = $this->resellers->paginate(
                $perPage = 20,
                Input::get('search'),
                Input::get('status'),
                null,
                $branch_id
            );    
            $statuses = ['' => trans('app.all')] + ResellerStatus::lists();
            return view('reseller.list', compact('resellers', 'statuses', 'reseller', 'branch'));

        }else if($user->getRole() == 2  || ($user->getRole() == 5 && $user->getAssociateId() != 0)){

            $customers = $this->customers->paginate(
                $perPage = 20,
                Input::get('search'),
                Input::get('status'),
                null,
                $branch_id
            );

            $statuses = ['' => trans('app.all')] + CustomerStatus::lists();
            return view('customer.list', compact('customers', 'statuses', 'customer', 'branch'));

        }
                
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
        $countries = $this->parseCountries($countryRepository);        
        $statuses = $this->getStatuses();
        $branches = $this->parseBranch();
        return view('reseller.add', compact('countries', 'statuses', 'edit', 'view', 'create', 'branches'));
    }

    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => 'Select a Country'] + $countryRepository->lists()->toArray();
    }

    private function parseBranch()
    {
        $user = auth()->user();
        $created_by =  $user->getId();
        if($user->getRole() == 5 ){
            $created_by = $user->getCreatedById();
        }
        $branches = Branch::where('created_by', $created_by )->pluck('title','id');  
        return $branches;
    }
    private function parseBranchObj()
    {
        $user = auth()->user();
        $created_by =  $user->getId();
        if($user->getRole() == 5 ){
            $created_by = $user->getCreatedById();
        }
        $branches = Branch::where('created_by', $created_by )->get();
        return $branches;
    }


    public function store(CreateUserRequest $request)
    {
  

        // When user is created by administrator, we will set his
        // status to Active by default.
        $data = $request->all() + ['status' => ResellerStatus::ACTIVE];

        $data['country_id'] = $data['country'];
        
        // Username should be updated only if it is provided.
        // So, if it is an empty string, then we just leave it as it is.
        if (trim($data['username']) == '') {
            $data['username'] = null;
        }        
        
        $data['role_id'] = 2;
        $data['main_office'] = auth()->user()->getId();
                    
        if ( $reseller = $this->resellers->create( $data )) {

            $data['associated_id'] = $reseller->id;
            $user = $this->userController->store($request, $data);            
            $reseller->users()->attach($user->id);
            return redirect()->route('reseller.list')->withSuccess(trans('app.reseller_created'));    
        } else {
            return redirect()->route('reseller.list')->withErrors(trans('app.reseller_error'));
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

    public function edit(Reseller $reseller, CountryRepository $countryRepository)
    {
        // Begin variables to form the reason to personalizate actions and buttons
        $edit = true;
        $view = false;
        $create = false;
        // End variables to form the reason to personalizate actions and buttons

        $countries = $this->parseCountries($countryRepository);
        $statuses = $this->getStatuses();
        
        $branches = $this->parseBranch();

        
        return view(
            'reseller.edit',
            compact('edit', 'view', 'create', 'reseller', 'countries', 'socialLogins', 'statuses', 'branches')
        );
    }

    public function update(Reseller $reseller, UpdateDetailsRequest $request)
    {
        $data = $request->all();

        $this->resellers->update($reseller->id, $data);
        
        return redirect('reseller')->withSuccess(trans('app.reseller_updated'));
    }

    public function destroy(Reseller $reseller)
    {
        //
    }

    public function delete(Reseller $reseller)
    {
        // 
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
