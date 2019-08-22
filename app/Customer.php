<?php

namespace Tagydes;

use Illuminate\Database\Eloquent\Model;
use Tagydes\Support\Enum\CustomerStatus;
use Tagydes\Branch;

class Customer extends Model
{
    protected $fillable = [
        'company_name', 'address_1', 'address_2', 'city', 'nif', 'country', 'postal_code', 'status', 'reseller','branch_id'
    ];

    public function isActive()
    {
        return $this->status == CustomerStatus::ACTIVE; 
    }

    public function users()
    {
    	return $this->belongsToMany('Tagydes\User', 'customer_user');
    }

    public function countries()
    {
    	return $this->hasOne('Tagydes\Country', 'id', 'country');
    }

    public function resellers(){
        return $this->hasOne('Tagydes\Reseller','id', 'reseller');
    }

    public function getMainUser()
    {
        return $this->users()->first();
    }

    public function getBranchName($id){
        $branch = Branch::where('id',$id)->get();
        if($branch->first()){
            return $branch->first()->title;
        }
        return "";
    }
    
    public function getBranches($id){
        $row = Customer::join('users', 'users.associated_id', 'customers.reseller')
        ->where('customers.id', $id)
        ->select('users.*')
        ->get();
        if($row->first()){
            $created_by = $row->first()->id;
            $branches = Branch::where('created_by', $created_by)->get();
            return $branches;       
        }else{
            return [];
        }        
    }

}
