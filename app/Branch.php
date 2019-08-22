<?php

namespace Tagydes;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $table = 'branch';
    protected $fillable = ['id', 'title', 'address', 'type', 'created_by'];

    public function getCreator(){
        return $this->hasOne('Tagydes\User','id', 'created_by');
    }
    public function getType($type){
        if($type == 1){
            return "Admin Level";
        }else{
            return "Reseller Level";
        }
    }


}
