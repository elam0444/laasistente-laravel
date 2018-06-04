<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Requirement;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RequirementRepository
 * @package App\Repositories
 */
class RequirementRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Requirement::class;
    }
}
