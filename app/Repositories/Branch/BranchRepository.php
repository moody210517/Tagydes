<?php

namespace Tagydes\Repositories\Branch;

use Carbon\Carbon;
use Tagydes\Reseller;

interface BranchRepository
{
    
    public function paginate($perPage, $search = null, $created_by = -1);
    
    public function all();

    public function create(array  $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function status($id);

   
}