<?php

namespace App\Repositories;

use App\Models\RequirementService;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RequirementServiceRepository
 * @package App\Repositories
 */
class RequirementServiceRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return RequirementService::class;
    }
}
