<?php

namespace Tagydes\Http\Controllers\web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tagydes\Customer;
use Tagydes\Http\Controllers\Controller;
use Tagydes\Http\Controllers\Web\UsersController;
use Tagydes\Http\Requests\User\CreateUserRequest;
use Tagydes\Repositories\Country\CountryRepository;
use Tagydes\Repositories\Customer\CustomerRepository;
use Tagydes\Repositories\User\UserRepository;
use Tagydes\Support\Enum\CustomerStatus;
use Tagydes\Http\Requests\Customer\CreateCustomerRequest;
use Tagydes\Http\Requests\Customer\UpdateDetailsRequest;
use DB;
use Tagydes\User;
use Tagydes\Branch;

class CustomersController extends Controller
{
    
    public function __construct(CustomerRepository $customers, UserRepository $users, UsersController $controller)
    {

//        $this->middleware('auth');
        //$this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        //$this->middleware('permission:customer.manage');
        $this->customers = $customers;        
        $this->users = $users;
        $this->userController = $controller;
    }

    public function index(Customer $customer = null, Request $request)
    {
                        
        $user = auth()->user();      
        $created_by = 0;

        if($user->getRole() != 1){ // if not admin


            if($user->getRole() == 5){
                $status = Input::get('status');
                $search = Input::get('search');
                $query = Customer::query();                        
                if ($status) {            
                    $query->where('status', $status);
                }        
                    
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('company_name', "like", "%{$search}%");
                    });
                }
                
                $query->join('user_branch', 'user_branch.branch_id', 'customers.branch_id');
                $query->join('users', 'users.id', 'user_branch.user_id');
                $query->where('user_id', auth()->user()->getId());      
                $query->select('customers.*');         
                $customers = $query->orderBy('user_id', 'desc')
                ->paginate(20); 
            }else{
                $created_by = $user->getId();
                $parent = User::where('id', $user->getCreatedById())->get();
                if($parent->first()){            
                    if($parent->first()->role_id != 1){
                        $created_by = $parent->first()->id;  
                    }
                }
    
                $customers = $this->customers->paginate(
                    $perPage = 20,
                    Input::get('search'),
                    Input::get('status'),
                    $created_by
                );
            }


            

            



        }else{
            $customers = $this->customers->paginate(
                $perPage = 20,
                Input::get('search'),
                Input::get('status')
            );
        }

        $statuses = ['' => trans('app.all')] + CustomerStatus::lists();
        return view('customer.list', compact('customers', 'statuses', 'customer'));
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
        return view('customer.add', compact('countries', 'statuses', 'edit', 'view', 'create', 'branches'));

    }

    
    public function store(CreateUserRequest $request)
    {
        // When user is created by administrator, we will set his
        // status to Active by default.
        $data = $request->all() + ['status' => CustomerStatus::ACTIVE];

        $data['country_id'] = $data['country'];
        
        // Username should be updated only if it is provided.
        // So, if it is an empty string, then we just leave it as it is.
        if (trim($data['username']) == '') {
            $data['username'] = null;
        }

        $data['role_id'] = 4;
        $data['reseller'] = $request->user()->resellers()->first()->id;


        //if ($user = $this->userController->store($request, $data)) {
        if ($customer = $this->customers->create($data)) {
            
            $data['associated_id'] = $customer->id;
            $u = auth()->user();
            $data['created_by'] = $u->getId();
            //$user = $this->userController->store($request, $data);
            $user =  User::create($data);
            $customer->users()->attach($user->id);
            return redirect()->route('customer.list')->withSuccess(trans('app.customer_created'));    
        } else {
            return redirect()->route('customer.list')->withErrors(trans('app.customer_error'));
        }
        // $reseller = $this->reseller->create($data);

        
    }

    public function show(Customer $customer, CountryRepository $countryRepository)
    {
        // Begin variables to form the reason to personalizate actions and buttons
        $edit = false;
        $view = true;
        $create = false;
        // End variables to form the reason to personalizate actions and buttons

        $countries = $this->parseCountries($countryRepository);

        $statuses = $this->getStatuses();
        return view('customer.view', compact('customer', 'statuses', 'countries', 'edit', 'view', 'create'));
    }

    public function edit(Customer $customer, CountryRepository $countryRepository)
    {
        
        $edit = true;
        $view = false;
        $create = false;
        // End variables to form the reason to personalizate actions and buttons

        $countries = $this->parseCountries($countryRepository);
        $statuses = $this->getStatuses();
        $branches = $this->parseBranch();

        return view(
            'customer.edit',
            compact('edit', 'view', 'create', 'customer', 'countries', 'socialLogins', 'statuses', 'branches')
        );
    }

    public function update(Customer $customer, UpdateDetailsRequest $request)
    {
        $data = $request->all();
        $id = DB::table('customer_user')->where('customer_id', $customer->id)->value('user_id');
        DB::table('users')
            ->where('id', $id)
            ->update(['status' => $data['status']]);       
            // var_dump($customer->id);
            // var_dump($id);
            // var_dump($customer->status);
            // var_dump($customer);
        $this->customers->update($customer->id, $data);        
        return redirect('customer')->withSuccess(trans('app.customer_updated'));
    }

    public function destroy(Customer $customer)
    {
        //
    }

    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => 'Select a Country'] + $countryRepository->lists()->toArray();
    }

    private function getStatuses()
    {
        return CustomerStatus::lists();
    }

    public function delete(Customer $customer){        
        // var_dump($customer->id);
        Customer::where('id',$customer->id)->delete();
        User::where('associated_id')->where('role_id','4')->delete();
        return redirect()->route('customer.list')->withSuccess(trans('deleted'));    
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


}
