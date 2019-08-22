<?php

namespace Tagydes\Repositories\Customer;

use Tagydes\Customer;
use Carbon\Carbon;
use DB;
use Illuminate\Database\SQLiteConnection;

class EloquentCustomer implements CustomerRepository
{

    public function countcustomers()
    {
        return Customer::count();
    }

    // protected $customer;

    // public function __construct(Customer $customer)
    // {
    //     $this->customer = $customer;
    // }

    public function all()
    {
        $this->customer->all();
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($id, array $data)
    {

        $customer = $this->find($id);

        $customer->update($data);

        return $customer;
    }

    public function delete($id)
    {
        return $this->customer->destroy($id);
    }

    public function find($id)
    {
        // if (null == $customer = $this->customer->find($id)) {
        //     throw new ModelNotFoundException("Customer not found");
        // }

        // return $customer;
        return Customer::find($id);
    }


    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null, $status = null, $reseller = null, $branch_id = null)
    {
        $query = Customer::query();


        //$query->join('resellers', 'reseller', '=', 'resellers.id');

        if ($status) {            
            $query->where('status', $status);
        }

        if($branch_id != null){
            $query->where('branch_id', $branch_id);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', "like", "%{$search}%");
            });
        }
        if ($reseller) {
            $uid = DB::table('reseller_user')->where('user_id', $reseller)->value('reseller_id');
            $query->where('reseller', $uid);
        }

        //$query->select('customers.id', 'customers.city', 'customers.country', );

        $result = $query->orderBy('customers.id', 'desc')
            ->paginate($perPage);


         

                
            
        if ($search) {
            $result->appends(['search' => $search]);
        }

        if ($status) {
            $result->appends(['status' => $status]);
        }

        
    

        return $result;
    }

}
