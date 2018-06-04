<?php

namespace App\Repositories;

use App\Models\ServiceCategory;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ServiceCategoryRepository
 * @package App\Repositories
 */
class ServiceCategoryRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ServiceCategory::class;
    }
}
