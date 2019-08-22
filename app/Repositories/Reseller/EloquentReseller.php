<?php

namespace Tagydes\Repositories\Reseller;

use Carbon\Carbon;
use DB;
use Illuminate\Database\SQLiteConnection;
use Tagydes\Reseller;
use Tagydes\Support\Enum\ResellerStatus;

class EloquentReseller implements ResellerRepository
{
    // protected $reseller;

    // public function __construct(Reseller $reseller)
    // {
    //     $this->reseller = $reseller;
    // }

    public function all()
    {
        $this->reseller->all();
    }

    public function countMainOffice()
    {
        $user = auth()->user();
        return Reseller::where('main_office', $user->getId())->count();
    }

    public function status($id)
    {
        $reseller = $this->find($id);

        $reseller->status = $reseller->isActive() ? ResellerStatus::INACTIVE : ResellerStatus::ACTIVE;

        $reseller->update();
        return $reseller;
    }

    public function create(array $data)
    {
        return Reseller::create($data);
    }

    public function update($id, array $data)
    {

        $reseller = $this->find($id);

        $reseller->update($data);

        return $reseller;
    }

    public function delete($id)
    {
        return $this->reseller->destroy($id);
    }

    public function find($id)
    {
        // if (null == $reseller = $this->reseller->find($id)) {
        //     throw new ModelNotFoundException("Reseller not found");
        // }
        // return $reseller;
        return Reseller::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null, $status = null, $reseller = null, $branch_id = null)
    {
        $query = Reseller::query();

        //$query->where('role_id', $status);

        if ($status) {            
            $query->where('status', $status);
        }

        if ($branch_id) {            
            $query->where('branch_id', $branch_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', "like", "%{$search}%");
            });
        }

        if ($reseller) {

            $reseller_user = DB::table('reseller_user')->where('user_id', $reseller)->get();
            if($reseller_user->first()){
                $query->where('id', $reseller_user->first()->reseller_id);
            }            
        } 

        $result = $query->orderBy('id', 'desc')
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
