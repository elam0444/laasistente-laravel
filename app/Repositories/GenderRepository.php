<?php

namespace App\Repositories;

use App\Models\Gender;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class GenderRepository
 * @package App\Repositories
 */
class GenderRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Gender::class;
    }
}
