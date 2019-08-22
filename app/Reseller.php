<?php

namespace Tagydes;

use Illuminate\Database\Eloquent\Model;
use Tagydes\Support\Enum\ResellerStatus;
use Tagydes\Branch;

class Reseller extends Model
{
    protected $fillable = [
        'company_name', 'address_1', 'address_2', 'city', 'nif', 'country', 'postal_code', 'status', 'main_office', 'branch_id'
    ];

    public function isActive()
    {
        return $this->status == ResellerStatus::ACTIVE; 
    }

    public function users()
    {
    	return $this->belongsToMany('Tagydes\User', 'reseller_user');
    }

    public function countries()
    {
    	return $this->hasOne('Tagydes\Country', 'id', 'country');
    }

    public function companies(){
        return $this->hasMany('Tagydes\Customer', 'reseller', 'id');
    }

    public function getMainUser()
    {
        return $this->users()->first();
    }

    public function isABranchOffice() {
        return !empty($this->main_office);
    }

    public function getBranchName($id){
        $branch = Branch::where('id',$id)->get();
        if($branch->first()){
            return $branch->first()->title;
        }
        return "";
    }
}
