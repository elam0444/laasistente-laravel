<?php

namespace App\Repositories;

use App\Models\Address;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AddressRepository
 * @package App\Repositories
 */
class AddressRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Address::class;
    }
}
