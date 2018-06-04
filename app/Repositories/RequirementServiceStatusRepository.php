<?php

namespace App\Repositories;

use App\Models\RequirementServiceStatus;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RequirementServiceStatusRepository
 * @package App\Repositories
 */
class RequirementServiceStatusRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return RequirementServiceStatus::class;
    }
}
