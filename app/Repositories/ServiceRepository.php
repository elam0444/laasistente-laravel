<?php

namespace App\Repositories;

use App\Models\Service;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ServiceRepository
 * @package App\Repositories
 */
class ServiceRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Service::class;
    }
}
