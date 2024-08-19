<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public static function findById(int $id): ?Customer
    {
        return Customer::find($id);
    }
}
