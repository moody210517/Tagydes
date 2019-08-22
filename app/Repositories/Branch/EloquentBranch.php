<?php

namespace Tagydes\Repositories\Branch;

use Carbon\Carbon;
use DB;
use Illuminate\Database\SQLiteConnection;
use Tagydes\Reseller;
use Tagydes\Branch;
use Tagydes\News;
use Tagydes\Support\Enum\ResellerStatus;

class EloquentBranch implements BranchRepository
{
    protected $branch;

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function all()
    {
        $this->reseller->all();
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
        return Branch::create($data);
    }

    public function update($id, array $data)
    {

        $branch = $this->find($id);
        $branch->update($data);
        return $branch;
    }

    public function delete($id)
    {
        return $this->branch->destroy($id);
    }

    public function find($id)
    {
        // if (null == $reseller = $this->reseller->find($id)) {
        //     throw new ModelNotFoundException("Reseller not found");
        // }

        // return $reseller;
        return Branch::find($id);
    }


    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null, $created_by = -1)
    {
        $query = Branch::query();
        if($created_by != -1){
            $query->where('created_by', $created_by);     
        }
        
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
