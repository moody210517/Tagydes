<?php

namespace Tagydes\Repositories\News;

use Carbon\Carbon;
use DB;
use Illuminate\Database\SQLiteConnection;
use Tagydes\Reseller;
use Tagydes\News;
use Tagydes\Support\Enum\ResellerStatus;

class EloquentNews implements NewsRepository
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
        return Reseller::whereNull('main_office')->count();
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
    public function paginate($perPage, $search = null)
    {
        $query = News::query();

        //$query->where('role_id', $status);

     

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', "like", "%{$search}%");
            });
        }

  

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

    
        return $result;
    }

}
