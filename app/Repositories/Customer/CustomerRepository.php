<?php

namespace Tagydes\Repositories\Customer;

use Carbon\Carbon;
use Tagydes\Customer;

interface CustomerRepository
{
    
    public function paginate($perPage, $search = null, $status = null, $reseller = null, $branch_id);
    
    public function all();

    public function create(array  $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);

    public function countcustomers();
    
}