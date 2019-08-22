<?php

namespace Tagydes\Repositories\Reseller;

use Carbon\Carbon;
use Tagydes\Reseller;

interface ResellerRepository
{
    
    public function paginate($perPage, $search = null, $status = null, $reseller = null, $branch_id = null);
    
    public function all();

    public function create(array  $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function status($id);

    public function countMainOffice();
    
}